<?php

namespace Tests\Unit;

use Plugin\Paypal\Services\PaypalService;
use Tests\TestCase;

class PaypalServiceTest extends TestCase
{
    public function test_rest_order_creation_keeps_order_currency(): void
    {
        config(['bk.system.base.currency' => 'USD']);

        if (! class_exists('Srmklive\\PayPal\\Services\\PayPal', false)) {
            class_alias(PaypalClientSpy::class, 'Srmklive\\PayPal\\Services\\PayPal');
        }

        $paypalClient = new PaypalClientSpy();
        $service      = new class((object) ['currency_code' => 'EUR', 'number' => 'ORDER-1001', 'status' => 'unpaid', 'total' => 42.35], $paypalClient) extends PaypalService
        {
            public function __construct(object $order, PaypalClientSpy $paypalClient)
            {
                $this->order        = $order;
                $this->paypalClient = $paypalClient;
            }

            public function isNvpApi(): bool
            {
                return false;
            }
        };

        $service->createOrder();

        $this->assertSame(
            'EUR',
            $paypalClient->lastOrder['purchase_units'][0]['amount']['currency_code']
        );
    }
}

class PaypalClientSpy
{
    public array $lastOrder = [];

    public function createOrder(array $order): array
    {
        $this->lastOrder = $order;

        return ['id' => 'REST-ORDER-1'];
    }
}
