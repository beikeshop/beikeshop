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
    protected const TOTAL_CODES = [
        'subtotal',
        'tax',
        'shipping',
        'customer_discount',
    ];

    protected Cart $currentCart;

    protected array $cartProducts;

    public array $taxes = [];

    public array $totals;

    public float $amount = 0;

    protected string|array $shippingMethod = '';

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

    public function getShippingMethod(): string
    {
        return $this->shippingMethod;
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
            $serviceName = Str::studly($code) . 'Service';
            $maps[$code] = "\Beike\\Shop\\Services\\TotalServices\\{$serviceName}";
        }

        $maps                = hook_filter('service.total.maps', $maps);
        $maps['order_total'] = "\Beike\\Shop\\Services\\TotalServices\\OrderTotalService";

        return $maps;
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

    /**
     * Get Cart Products
     *
     * @return array
     */
    public function getCartProducts(): array
    {
        return $this->cartProducts;
    }

    /**
     * Get Current Cart
     *
     * @return Cart
     */
    public function getCurrentCart()
    {
        return $this->currentCart;
    }

    /**
     * Get Cart Product Amount
     *
     * @return mixed
     */
    public function countProducts(): mixed
    {
        $cartProducts = $this->getCartProducts();

        return collect($cartProducts)->sum('quantity');
    }
}
