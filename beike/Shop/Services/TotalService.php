<?php
/**
 * TotalService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-22 17:11:31
 * @modified   2022-07-22 17:11:31
 */

namespace Beike\Shop\Services;

use Beike\Libraries\Tax;
use Beike\Models\Cart;
use Illuminate\Support\Str;

class TotalService
{
    private const TOTAL_CODES = [
        'subtotal',
        'tax',
        'shipping',
        'customer_discount',
        'order_total',
    ];

    public Cart $currentCart;

    public array $cartProducts;

    public array $taxes = [];

    public array $totals;

    public float $amount = 0;

    public string $shippingMethod = '';

    public function __construct($currentCart, $cartProducts)
    {
        $this->currentCart  = $currentCart;
        $this->cartProducts = $cartProducts;
        $this->setShippingMethod($currentCart->shipping_method_code);
        $this->getTaxes();
    }

    /**
     * 设置配送方式
     */
    public function setShippingMethod($methodCode): self
    {
        $this->shippingMethod = $methodCode;

        return $this;
    }

    /**
     * 获取税费数据
     *
     * @return array
     */
    public function getTaxes(): array
    {
        $addressInfo = [
            'shipping_address' => $this->currentCart->shippingAddress ?? $this->currentCart->guest_shipping_address,
            'payment_address'  => $this->currentCart->paymentAddress  ?? $this->currentCart->guest_payment_address,
        ];
        $taxLib = Tax::getInstance($addressInfo);

        foreach ($this->cartProducts as $product) {
            if (empty($product['tax_class_id'])) {
                continue;
            }

            $taxRates = $taxLib->getRates($product['price'], $product['tax_class_id']);
            foreach ($taxRates as $taxRate) {
                if (! isset($this->taxes[$taxRate['tax_rate_id']])) {
                    $this->taxes[$taxRate['tax_rate_id']] = ($taxRate['amount'] * $product['quantity']);
                } else {
                    $this->taxes[$taxRate['tax_rate_id']] += ($taxRate['amount'] * $product['quantity']);
                }
            }
        }

        return $this->taxes;
    }

    /**
     * @param CheckoutService $checkout
     * @return array
     */
    public function getTotals(CheckoutService $checkout): array
    {
        $maps = $this->getTotalClassMaps();
        foreach ($maps as $service) {
            if (! class_exists($service) || ! method_exists($service, 'getTotal')) {
                continue;
            }
            $service::getTotal($checkout);
        }

        return hook_filter('service.total.totals', $this->totals);
    }

    /**
     * total 与类名 映射
     *
     * @return mixed
     */
    private function getTotalClassMaps()
    {
        $maps = [];
        foreach (self::TOTAL_CODES as $code) {
            $serviceName  = Str::studly($code) . 'Service';
            $maps[$code] = "\Beike\\Shop\\Services\\TotalServices\\{$serviceName}";
        }

        return hook_filter('service.total.maps', $maps);
    }

    /**
     * 获取当前购物车商品总额
     *
     * @return mixed
     */
    public function getSubTotal(): mixed
    {
        $carts = $this->cartProducts;

        return collect($carts)->sum('subtotal');
    }
}
