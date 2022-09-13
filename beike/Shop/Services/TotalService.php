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
    const TOTAL_CODES = [
        'subtotal',
        'tax',
        'shipping',
        'order_total'
    ];

    public Cart $currentCart;
    public array $cartProducts;
    public array $taxes = [];
    public array $totals;
    public float $amount = 0;
    public string $shippingMethod = '';

    public function __construct($currentCart, $cartProducts)
    {
        $this->currentCart = $currentCart;
        $this->cartProducts = $cartProducts;
        $this->setShippingMethod($currentCart->shipping_method_code);
        $this->getTaxes();
    }


    /**
     * 设置配送方式
     */
    public function setShippingMethod($methodCode): TotalService
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
            'shipping_address' => $this->currentCart->shippingAddress,
            'payment_address' => $this->currentCart->paymentAddress,
        ];
        $taxLib = Tax::getInstance($addressInfo);

        foreach ($this->cartProducts as $product) {
            if (empty($product['tax_class_id'])) {
                continue;
            }

            $taxRates = $taxLib->getRates($product['price'], $product['tax_class_id']);
            foreach ($taxRates as $taxRate) {
                if (!isset($this->taxes[$taxRate['tax_rate_id']])) {
                    $this->taxes[$taxRate['tax_rate_id']] = ($taxRate['amount'] * $product['quantity']);
                } else {
                    $this->taxes[$taxRate['tax_rate_id']] += ($taxRate['amount'] * $product['quantity']);
                }
            }
        }

        return $this->taxes;
    }


    /**
     * @return array
     */
    public function getTotals(): array
    {
        foreach (self::TOTAL_CODES as $code) {
            $serviceName = Str::studly($code) . 'Service';
            $service = "\Beike\\Shop\\Services\\TotalServices\\{$serviceName}";
            if (!class_exists($service) || !method_exists($service, 'getTotal')) {
                continue;
            }
            $service::getTotal($this);
        }

        return $this->totals;
    }
}
