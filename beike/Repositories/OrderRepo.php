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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepo
{
    /**
     * 获取所有客户订单列表
     *
     * @return LengthAwarePaginator
     */
    public static function getListAll(): LengthAwarePaginator
    {
        $builder = self::getListBuilder()->orderByDesc('created_at');
        return $builder->paginate();
    }


    /**
     * 获取特定客户订单列表
     *
     * @param null $customer
     * @return LengthAwarePaginator
     */
    public static function getListByCustomer($customer): LengthAwarePaginator
    {
        $builder = self::getListBuilder(['customer' => $customer])->orderByDesc('created_at');
        return $builder->paginate();
    }


    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public static function filterOrders(array $filters = []): LengthAwarePaginator
    {
        $builder = self::getListBuilder($filters)->orderByDesc('created_at');
        return $builder->paginate();
    }


    /**
     * @param array $filters
     * @return Builder
     */
    public static function getListBuilder(array $filters = []): Builder
    {
        $builder = Order::query();

        $customer = $filters['customer'] ?? null;
        if ($customer) {
            $builder->where('customer_id', $customer->id);
        }

        $start = $filters['start'] ?? null;
        if ($start) {
            $builder->where('created_at', '>', $start);
        }

        $end = $filters['end'] ?? null;
        if ($end) {
            $builder->where('created_at', '<', $end);
        }

        return $builder;
    }

    /**
     * 通过订单号获取订单
     *
     * @param $number
     * @param $customer
     * @return Builder|Model|object|null
     */
    public static function getOrderByNumber($number, $customer)
    {
        $order = Order::query()
            ->where('number', $number)
            ->where('customer_id', $customer->id)
            ->first();
        return $order;
    }


    /**
     * 通过订单ID或者订单号获取订单
     *
     * @param $number
     * @param $customer
     * @return Builder|Model|object|null
     */
    public static function getOrderByIdOrNumber($number, $customer)
    {
        $order = Order::query()
            ->where(function ($query) use ($number) {
                $query->where('number', $number)
                    ->orWhere('id', $number);
            })
            ->where('customer_id', $customer->id)
            ->first();
        return $order;
    }


    /**
     * @param array $data
     * @return Order
     * @throws \Throwable
     */
    public static function create(array $data): Order
    {
        $customer = $data['customer'] ?? null;
        $current = $data['current'] ?? [];
        $carts = $data['carts'] ?? [];
        $totals = $data['totals'] ?? [];
        $orderTotal = collect($totals)->where('code', 'order_total')->first();

        $shippingAddressId = $current['shipping_address_id'] ?? 0;
        $paymentAddressId = $current['payment_address_id'] ?? 0;

        $shippingAddress = AddressRepo::find($shippingAddressId);
        $paymentAddress = AddressRepo::find($paymentAddressId);

        $shippingMethodCode = $current['shipping_method_code'] ?? '';
        $paymentMethodCode = $current['payment_method_code'] ?? '';

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
            'total' => $orderTotal['amount'],
            'locale' => locale(),
            'currency_code' => current_currency_code(),
            'currency_value' => 1,
            'ip' => request()->getClientIp(),
            'user_agent' => request()->userAgent(),
            'status' => 'unpaid',
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
        OrderTotalRepo::createTotals($order, $totals);
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
