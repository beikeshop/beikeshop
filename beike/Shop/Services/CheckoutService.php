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

use Beike\Libraries\Weight;
use Beike\Models\Address;
use Beike\Models\Country;
use Beike\Models\Customer;
use Beike\Models\Order;
use Beike\Models\Zone;
use Beike\Repositories\AddressRepo;
use Beike\Repositories\CartRepo;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\OrderRepo;
use Beike\Repositories\PluginRepo;
use Beike\Services\ShippingMethodService;
use Beike\Services\StateMachineService;
use Beike\Shop\Http\Resources\Account\AddressResource;
use Beike\Shop\Http\Resources\Checkout\PaymentMethodItem;
use Illuminate\Support\Facades\DB;

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
        // if (empty($this->customer) || !($this->customer instanceof Customer)) {
        //     // throw new \Exception(trans('shop/carts.invalid_customer'));
        // }
        $this->cart             = CartRepo::createCart($this->customer);
        $this->selectedProducts = CartRepo::selectedCartProducts($this->customer->id ?? 0);
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
        $shippingAddressId  = $requestData['shipping_address_id']  ?? 0;
        $shippingMethodCode = $requestData['shipping_method_code'] ?? '';

        $paymentAddressId  = $requestData['payment_address_id']  ?? 0;
        $paymentMethodCode = $requestData['payment_method_code'] ?? '';

        $guestShippingAddress = $requestData['guest_shipping_address'] ?? [];
        $guestPaymentAddress  = $requestData['guest_payment_address']  ?? [];

        hook_action('service.checkout.update.before', ['request_data' => $requestData, 'cart' => $this->cart]);

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
        if ($guestShippingAddress) {
            $this->updateGuestShippingAddress($guestShippingAddress);
        }
        if ($guestPaymentAddress) {
            $this->updateGuestPaymentAddress($guestPaymentAddress);
        }

        hook_action('service.checkout.update.after', ['request_data' => $requestData, 'cart' => $this->cart]);

        return $this->checkoutData();
    }

    /**
     * 确认提交订单
     * @throws \Throwable
     */
    public function confirm(): Order
    {
        $customer                 = current_customer();
        $checkoutData             = self::checkoutData();
        $checkoutData['customer'] = $customer;
        $this->validateConfirm($checkoutData);

        try {
            DB::beginTransaction();

            $order = OrderRepo::create($checkoutData);
            StateMachineService::getInstance($order)->changeStatus(StateMachineService::UNPAID, '', true);
            CartRepo::clearSelectedCartProducts($customer);

            hook_action('service.checkout.confirm.after', ['order' => $order, 'cart' => $this->cart]);

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
        $weight           = 0;
        $selectedProducts = $this->selectedProducts;
        foreach ($selectedProducts as $product) {
            $weight += Weight::convert($product->product['weight'], $product->product['weight_class']);
        }

        return $weight;
    }

    /**
     * @throws \Exception
     */
    private function validateConfirm($checkoutData)
    {
        $current = $checkoutData['current'];

        if ($this->customer) {
            $shippingAddressId = $current['shipping_address_id'];
            if (empty(Address::query()->find($shippingAddressId))) {
                throw new \Exception(trans('shop/carts.invalid_shipping_address'));
            }

            $paymentAddressId = $current['payment_address_id'];
            if (empty(Address::query()->find($paymentAddressId))) {
                throw new \Exception(trans('shop/carts.invalid_payment_address'));
            }
        } else {
            if (! $current['guest_shipping_address']) {
                throw new \Exception(trans('shop/carts.invalid_shipping_address'));
            }

            if (! $current['guest_payment_address']) {
                throw new \Exception(trans('shop/carts.invalid_payment_address'));
            }
        }

        $shippingMethodCode = $current['shipping_method_code'];
        if (! PluginRepo::shippingEnabled($shippingMethodCode)) {
            throw new \Exception(trans('shop/carts.invalid_shipping_method'));
        }

        $paymentMethodCode = $current['payment_method_code'];
        if (! PluginRepo::paymentEnabled($paymentMethodCode)) {
            throw new \Exception(trans('shop/carts.invalid_payment_method'));
        }

        hook_action('service.checkout.validate_confirm.after', $checkoutData);
    }

    private function updateShippingAddressId($shippingAddressId)
    {
        $this->cart->update(['shipping_address_id' => $shippingAddressId]);
        $this->cart->load('shippingAddress');
    }

    private function updatePaymentAddressId($paymentAddressId)
    {
        $this->cart->update(['payment_address_id' => $paymentAddressId]);
        $this->cart->load('paymentAddress');
    }

    private function updateGuestShippingAddress($guestShippingAddress)
    {
        $this->cart->update(['guest_shipping_address' => self::formatAddress($guestShippingAddress)]);
    }

    private function updateGuestPaymentAddress($guestPaymentAddress)
    {
        $this->cart->update(['guest_payment_address' => self::formatAddress($guestPaymentAddress)]);
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
        $customer    = $this->customer;
        $currentCart = $this->cart;

        $cartList           = CartService::list($customer, true);
        $carts              = CartService::reloadData($cartList);
        $totalService       = (new TotalService($currentCart, $cartList));
        $this->totalService = $totalService;

        $addresses = AddressRepo::listByCustomer($customer);
        $shipments = ShippingMethodService::getShippingMethods($this);
        $payments  = PaymentMethodItem::collection(PluginRepo::getPaymentMethods())->jsonSerialize();

        $shipmentCodes = [];
        foreach ($shipments as $shipment) {
            $shipmentCodes = array_merge($shipmentCodes, array_column($shipment['quotes'], 'code'));
        }
        if (!in_array($currentCart->shipping_method_code, $shipmentCodes)) {
            $this->updateShippingMethod($shipmentCodes[0]);
        }

        $data = [
            'current'          => [
                'shipping_address_id'    => $currentCart->shipping_address_id,
                'guest_shipping_address' => $currentCart->guest_shipping_address,
                'shipping_method_code'   => $currentCart->shipping_method_code,
                'payment_address_id'     => $currentCart->payment_address_id,
                'guest_payment_address'  => $currentCart->guest_payment_address,
                'payment_method_code'    => $currentCart->payment_method_code,
                'extra'                  => $currentCart->extra,
            ],
            'country_id'       => (int) system_setting('base.country_id'),
            'customer_id'      => $customer->id ?? null,
            'countries'        => CountryRepo::listEnabled(),
            'addresses'        => AddressResource::collection($addresses),
            'shipping_methods' => $shipments,
            'payment_methods'  => $payments,
            'carts'            => $carts,
            'totals'           => $totalService->getTotals($this),
        ];

        return hook_filter('checkout.data', $data);
    }

    public static function formatAddress($address)
    {
        if (! $address) {
            return [];
        }
        $address['country'] = Country::find($address['country_id'])->name;
        $address['zone']    = Zone::find($address['zone_id'])->name;

        return $address;
    }
}
