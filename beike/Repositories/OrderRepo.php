<?php
/**
 * OrderRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-04 17:22:02
 * @modified   2022-07-04 17:22:02
 */

namespace Beike\Repositories;

use Carbon\Carbon;
use Beike\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class OrderRepo
{
    /**
     * 获取订单列表
     *
     * @param null $customer
     * @return LengthAwarePaginator
     */
    public static function getListByCustomer($customer): LengthAwarePaginator
    {
        $builder = self::getListBuilder($customer);
        return $builder->paginate();
    }


    /**
     * @param null $customer
     * @return Builder
     */
    private static function getListBuilder($customer = null): Builder
    {
        $builder = Order::query();
        if ($customer) {
            $builder->where('customer_id', $customer->id);
        }
        return $builder;
    }


    /**
     * @param array $data
     * @return Order
     * @throws \Throwable
     */
    public static function create(array $data): Order
    {
        $customer = $data['customer'] ?? null;
        $current = $data['checkout']['current'] ?? [];
        $carts = $data['checkout']['carts'] ?? [];
        $shippingAddressId = $current['shipping_address_id'] ?? 0;
        $paymentAddressId = $current['payment_address_id'] ?? 0;

        $shippingAddress = AddressRepo::find($shippingAddressId);
        $paymentAddress = AddressRepo::find($paymentAddressId);

        $shippingMethodCode = $data['shipping_method_code'] ?? '';
        $paymentMethodCode = $data['payment_method_code'] ?? '';

        $order = new Order([
            'number' => self::generateOrderNumber(),
            'customer_id' => $customer->id,
            'customer_group_id' => $customer->customer_group_id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_address_id' => $paymentAddress->id,
            'customer_name' => $customer->name,
            'email' => $customer->email,
            'calling_code' => $customer->calling_code ?? 0,
            'telephone' => $customer->telephone ?? '',
            'total' => $carts['amount'],
            'locale' => locale(),
            'currency_code' => current_currency_code(),
            'currency_value' => 1,
            'ip' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
            'status' => 'UNPAID',
            'shipping_method_code' => $shippingMethodCode,
            'shipping_method_name' => trans($shippingMethodCode),
            'shipping_customer_name' => $shippingAddress->name,
            'shipping_calling_code' => $shippingAddress->calling_code ?? 0,
            'shipping_telephone' => $shippingAddress->telephone ?? '',
            'shipping_country' => $shippingAddress->country->name ?? '',
            'shipping_zone' => $shippingAddress->zone,
            'shipping_city' => $shippingAddress->city,
            'shipping_address_1' => $shippingAddress->address_1,
            'shipping_address_2' => $shippingAddress->address_2,
            'payment_method_code' => $paymentMethodCode,
            'payment_method_name' => trans($paymentMethodCode),
            'payment_customer_name' => $paymentAddress->name,
            'payment_calling_code' => $paymentAddress->calling_code ?? 0,
            'payment_telephone' => $paymentAddress->telephone ?? '',
            'payment_country' => $paymentAddress->country->name ?? '',
            'payment_zone' => $paymentAddress->zone,
            'payment_city' => $paymentAddress->city,
            'payment_address_1' => $paymentAddress->address_1,
            'payment_address_2' => $paymentAddress->address_2,
        ]);
        $order->saveOrFail();

        OrderProductRepo::create($order, $carts['carts']);
        // OrderHistoryRepo::create($order);

        return $order;
    }


    /**
     * 生成订单号
     *
     * @return string
     */
    public static function generateOrderNumber(): string
    {
        $orderNumber = Carbon::now()->format('Ymd') . rand(10000, 99999);
        $exist = Order::query()->where('number', $orderNumber)->exists();
        if ($exist) {
            return self::generateOrderNumber();
        }
        return $orderNumber;
    }
}
