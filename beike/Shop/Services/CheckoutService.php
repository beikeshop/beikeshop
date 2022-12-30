<?php
/**
 * CheckoutService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-30 19:37:05
 * @modified   2022-06-30 19:37:05
 */

namespace Beike\Shop\Services;

use Beike\Models\Order;
use Beike\Models\Address;
use Beike\Models\Customer;
use Beike\Repositories\CartRepo;
use Beike\Repositories\OrderRepo;
use Illuminate\Support\Facades\DB;
use Beike\Repositories\PluginRepo;
use Beike\Repositories\AddressRepo;
use Beike\Repositories\CountryRepo;
use Beike\Services\StateMachineService;
use Beike\Services\ShippingMethodService;
use Beike\Shop\Http\Resources\Account\AddressResource;
use Beike\Shop\Http\Resources\Checkout\PaymentMethodItem;

class CheckoutService
{
    public $customer;
    public $cart;
    public $selectedProducts;
    public $totalService;


    /**
     * @throws \Exception
     */
    public function __construct($customer = null)
    {
        if (is_int($customer) || empty($customer)) {
            $this->customer = current_customer();
        }
        if (empty($this->customer) || !($this->customer instanceof Customer)) {
            throw new \Exception(trans('shop/carts.invalid_customer'));
        }
        $this->cart = CartRepo::createCart($this->customer);
        $this->selectedProducts = CartRepo::selectedCartProducts($this->customer->id);
        if ($this->selectedProducts->count() == 0) {
            throw new \Exception(trans('shop/carts.empty_selected_products'));
        }
    }

    /**
     * 更新结账页数据
     *
     * @param $requestData ['shipping_address_id'=>1, 'payment_address_id'=>2, 'shipping_method'=>'code', 'payment_method'=>'code']
     * @return array
     * @throws \Exception
     */
    public function update($requestData): array
    {
        $shippingAddressId = $requestData['shipping_address_id'] ?? 0;
        $shippingMethodCode = $requestData['shipping_method_code'] ?? '';

        $paymentAddressId = $requestData['payment_address_id'] ?? 0;
        $paymentMethodCode = $requestData['payment_method_code'] ?? '';

        if ($shippingAddressId) {
            $this->updateShippingAddressId($shippingAddressId);
        }
        if ($shippingMethodCode) {
            $this->updateShippingMethod($shippingMethodCode);
        }

        if ($paymentAddressId) {
            $this->updatePaymentAddressId($paymentAddressId);
        }
        if ($paymentMethodCode) {
            $this->updatePaymentMethod($paymentMethodCode);
        }

        hook_action('after_checkout_update', ['request_data' => $requestData, 'cart' => $this->cart]);

        return $this->checkoutData();
    }


    /**
     * 确认提交订单
     * @throws \Throwable
     */
    public function confirm(): Order
    {
        $customer = current_customer();
        $checkoutData = self::checkoutData();
        $checkoutData['customer'] = $customer;
        $this->validateConfirm($checkoutData);

        try {
            DB::beginTransaction();
            $order = OrderRepo::create($checkoutData);
            StateMachineService::getInstance($order)->changeStatus(StateMachineService::UNPAID, '', true);
            CartRepo::clearSelectedCartProducts($customer);

            hook_action('after_checkout_confirm', ['order' => $order, 'cart' => $this->cart]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $order;
    }


    /**
     * 计算当前选中商品总重量, 当前产品无重量, 待处理
     * @return int
     * @todo
     */
    public function getCartWeight(): int
    {
        $weight = 0;
        $selectedProducts = $this->selectedProducts;
        foreach ($selectedProducts as $product) {
            $weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
        }
        return $weight;
    }


    /**
     * @throws \Exception
     */
    private function validateConfirm($checkoutData)
    {
        $current = $checkoutData['current'];

        $shippingAddressId = $current['shipping_address_id'];
        if (empty(Address::query()->find($shippingAddressId))) {
            throw new \Exception(trans('shop/carts.invalid_shipping_address'));
        }

        $paymentAddressId = $current['payment_address_id'];
        if (empty(Address::query()->find($paymentAddressId))) {
            throw new \Exception(trans('shop/carts.invalid_payment_address'));
        }

        $shippingMethodCode = $current['shipping_method_code'];
        if (!PluginRepo::shippingEnabled($shippingMethodCode)) {
            throw new \Exception(trans('shop/carts.invalid_shipping_method'));
        }

        $paymentMethodCode = $current['payment_method_code'];
        if (!PluginRepo::paymentEnabled($paymentMethodCode)) {
            throw new \Exception(trans('shop/carts.invalid_payment_method'));
        }

        hook_action('after_checkout_validate', $checkoutData);
    }


    private function updateShippingAddressId($shippingAddressId)
    {
        $this->cart->update(['shipping_address_id' => $shippingAddressId]);
    }

    private function updatePaymentAddressId($paymentAddressId)
    {
        $this->cart->update(['payment_address_id' => $paymentAddressId]);
    }

    private function updateShippingMethod($shippingMethodCode)
    {
        $this->cart->update(['shipping_method_code' => $shippingMethodCode]);
    }

    private function updatePaymentMethod($paymentMethodCode)
    {
        $this->cart->update(['payment_method_code' => $paymentMethodCode]);
    }

    /**
     * 获取结账页数据
     *
     * @return array
     * @throws \Exception
     */
    public function checkoutData(): array
    {
        $customer = $this->customer;
        $currentCart = $this->cart;

        $cartList = CartService::list($customer, true);
        $carts = CartService::reloadData($cartList);
        $totalService = (new TotalService($currentCart, $cartList));
        $this->totalService = $totalService;

        $addresses = AddressRepo::listByCustomer($customer);
        $shipments = ShippingMethodService::getShippingMethods($this);
        $payments = PaymentMethodItem::collection(PluginRepo::getPaymentMethods())->jsonSerialize();

        $data = [
            'current' => [
                'shipping_address_id' => $currentCart->shipping_address_id,
                'shipping_method_code' => $currentCart->shipping_method_code,
                'payment_address_id' => $currentCart->payment_address_id,
                'payment_method_code' => $currentCart->payment_method_code,
                'extra' => $currentCart->extra,
            ],
            'country_id' => (int)system_setting('base.country_id'),
            'customer_id' => $customer->id ?? null,
            'countries' => CountryRepo::all(),
            'addresses' => AddressResource::collection($addresses),
            'shipping_methods' => $shipments,
            'payment_methods' => $payments,
            'carts' => $carts,
            'totals' => $totalService->getTotals($this),
        ];

        return hook_filter('checkout.data', $data);
    }
}
