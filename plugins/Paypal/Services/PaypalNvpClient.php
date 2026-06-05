<?php
/**
 * PaypalNvpClient.php
 *
 * @copyright  2026 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 */

namespace Plugin\Paypal\Services;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class PaypalNvpClient
{
    private const VERSION = '204.0';
    private const SANDBOX_ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
    private const LIVE_ENDPOINT = 'https://api-3t.paypal.com/nvp';
    private const SANDBOX_CHECKOUT_URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    private const LIVE_CHECKOUT_URL = 'https://www.paypal.com/cgi-bin/webscr';

    private string $mode;
    private string $username;
    private string $password;
    private string $signature;
    private int $timeout;
    private ClientInterface $httpClient;

    public function __construct(array $config, ?ClientInterface $httpClient = null)
    {
        $this->mode       = ($config['mode'] ?? 'sandbox') === 'live' ? 'live' : 'sandbox';
        $this->username   = trim((string) ($config['username'] ?? ''));
        $this->password   = trim((string) ($config['password'] ?? ''));
        $this->signature  = trim((string) ($config['signature'] ?? ''));
        $this->timeout    = max(1, (int) ($config['timeout'] ?? 30));
        $this->httpClient = $httpClient ?: new Client(['timeout' => $this->timeout]);
    }

    public function setExpressCheckout(array $parameters): array
    {
        $response = $this->request('SetExpressCheckout', $parameters);

        if (empty($response['TOKEN'])) {
            throw new \RuntimeException('PayPal NVP response did not include checkout token.');
        }

        $response['CHECKOUT_URL'] = $this->buildCheckoutUrl($response['TOKEN']);

        return $response;
    }

    public function getExpressCheckoutDetails(string $token): array
    {
        return $this->request('GetExpressCheckoutDetails', [
            'TOKEN' => $token,
        ]);
    }

    public function doExpressCheckoutPayment(array $parameters): array
    {
        return $this->request('DoExpressCheckoutPayment', $parameters);
    }

    private function request(string $method, array $parameters): array
    {
        $this->assertConfigured();

        $payload = array_merge([
            'METHOD'    => $method,
            'VERSION'   => self::VERSION,
            'USER'      => $this->username,
            'PWD'       => $this->password,
            'SIGNATURE' => $this->signature,
        ], $parameters);

        try {
            $response = $this->httpClient->request('POST', $this->endpoint(), [
                'form_params' => $payload,
                'timeout'     => $this->timeout,
            ]);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('PayPal NVP request failed: ' . $e->getMessage(), 0, $e);
        }

        $data = $this->parseResponse($response);
        $this->assertSuccessfulResponse($data);

        return $data;
    }

    private function parseResponse(ResponseInterface $response): array
    {
        parse_str((string) $response->getBody(), $data);

        return array_change_key_case($data, CASE_UPPER);
    }

    private function assertConfigured(): void
    {
        if ($this->username === '' || $this->password === '' || $this->signature === '') {
            throw new \RuntimeException('PayPal NVP API username, password, and signature are required.');
        }
    }

    private function assertSuccessfulResponse(array $data): void
    {
        $ack = strtoupper((string) ($data['ACK'] ?? ''));

        if (in_array($ack, ['SUCCESS', 'SUCCESSWITHWARNING'], true)) {
            return;
        }

        $code    = (string) ($data['L_ERRORCODE0'] ?? '');
        $short   = (string) ($data['L_SHORTMESSAGE0'] ?? '');
        $message = (string) ($data['L_LONGMESSAGE0'] ?? $short ?: 'PayPal NVP request was not successful.');
        $prefix  = $code !== '' ? "PayPal NVP error {$code}: " : 'PayPal NVP error: ';

        throw new \RuntimeException($prefix . $message);
    }

    private function endpoint(): string
    {
        return $this->mode === 'live' ? self::LIVE_ENDPOINT : self::SANDBOX_ENDPOINT;
    }

    private function buildCheckoutUrl(string $token): string
    {
        $baseUrl = $this->mode === 'live' ? self::LIVE_CHECKOUT_URL : self::SANDBOX_CHECKOUT_URL;

        return $baseUrl . '?' . http_build_query([
            'cmd'   => '_express-checkout',
            'token' => $token,
        ]);
    }
}
