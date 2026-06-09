<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Plugin\Paypal\Services\PaypalNvpClient;

class PaypalNvpClientTest extends TestCase
{
    public function test_set_express_checkout_parses_success_response_and_builds_checkout_url(): void
    {
        $history = [];
        $client  = $this->makeHttpClient(
            [new Response(200, [], 'ACK=Success&TOKEN=EC-123456')],
            $history
        );

        $nvpClient = new PaypalNvpClient($this->config(), $client);
        $result    = $nvpClient->setExpressCheckout([
            'PAYMENTREQUEST_0_AMT'          => '12.30',
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
        ]);

        $this->assertSame('Success', $result['ACK']);
        $this->assertSame('EC-123456', $result['TOKEN']);
        $this->assertSame(
            'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=EC-123456',
            $result['CHECKOUT_URL']
        );
        $this->assertSame('https://api-3t.sandbox.paypal.com/nvp', (string) $history[0]['request']->getUri());
        $this->assertStringContainsString('METHOD=SetExpressCheckout', (string) $history[0]['request']->getBody());
        $this->assertStringContainsString('USER=api-user', (string) $history[0]['request']->getBody());
    }

    public function test_paypal_error_response_throws_normalized_exception(): void
    {
        $history = [];
        $client  = $this->makeHttpClient(
            [new Response(200, [], 'ACK=Failure&L_ERRORCODE0=10002&L_LONGMESSAGE0=Authentication+failed')],
            $history
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('PayPal NVP error 10002: Authentication failed');

        (new PaypalNvpClient($this->config(), $client))->getExpressCheckoutDetails('EC-123456');
    }

    public function test_missing_credentials_fail_before_request(): void
    {
        $history = [];
        $client  = $this->makeHttpClient([new Response(200, [], 'ACK=Success')], $history);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('PayPal NVP API username, password, and signature are required.');

        try {
            (new PaypalNvpClient($this->config(['username' => '']), $client))->getExpressCheckoutDetails('EC-123456');
        } finally {
            $this->assertCount(0, $history);
        }
    }

    private function makeHttpClient(array $responses, array &$history): Client
    {
        $mock  = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        return new Client(['handler' => $stack]);
    }

    private function config(array $overrides = []): array
    {
        return array_merge([
            'mode'      => 'sandbox',
            'username'  => 'api-user',
            'password'  => 'api-password',
            'signature' => 'api-signature',
            'timeout'   => 5,
        ], $overrides);
    }
}
