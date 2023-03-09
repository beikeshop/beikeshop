<?php
/**
 * OrderRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-04 17:22:02
 * @modified   2022-07-04 17:22:02
 */

namespace Beike\Repositories;

use Beike\Models\Address;
use Beike\Models\Customer;
use Beike\Models\Order;
use Beike\Services\StateMachineService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderRepo
{
    /**
     * 获取所有客户订单列表
     *
     * @param array $filters
     * @return Builder[]|Collection
     */
    public static function filterAll(array $filters = [])
    {
        $builder = self::getListBuilder($filters)->orderByDesc('created_at');

        return $builder->get();
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

        return $builder->paginate(perPage());
    }

    /**
     * @param $customer
     * @param $limit
     * @return mixed
     */
    public static function getLatestOrders($customer, $limit)
    {
        return self::getListBuilder(['customer' => $customer])
            ->orderByDesc('created_at')
            ->take($limit)
            ->get();
    }

    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public static function filterOrders(array $filters = []): LengthAwarePaginator
    {
        $builder = self::getListBuilder($filters)->orderByDesc('created_at');

        return $builder->paginate(perPage());
    }

    /**
     * @param array $filters
     * @return Builder
     */
    public static function getListBuilder(array $filters = []): Builder
    {
        $builder = Order::query()->with(['orderProducts']);

        $number = $filters['number'] ?? 0;
        if ($number) {
            $builder->where('number', 'like', "%{$number}%");
        }

        $customerName = $filters['customer_name'] ?? '';
        if ($customerName) {
            $builder->where('customer_name', 'like', "%{$customerName}%");
        }

        $email = $filters['email'] ?? '';
        if ($email) {
            $builder->where('email', 'like', "%{$email}%");
        }

        $phone = $filters['phone'] ?? '';
        if ($phone) {
            $builder->where('phone', 'like', "%{$phone}%");
        }

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

        $status = $filters['status'] ?? '';
        if ($status) {
            $builder->where('status', $status);
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
    public static function getOrderByNumber($number, $customer = null)
    {
        $builder = Order::query()
            ->with(['orderProducts', 'orderTotals', 'orderHistories'])
            ->where('number', $number);

        $customerId = 0;
        if (is_int($customer)) {
            $customerId = $customer;
        } elseif ($customer instanceof Customer) {
            $customerId = $customer->id;
        }

        if ($customerId) {
            $builder->where('customer_id', $customerId);
        }

        return $builder->first();
    }

    /**
     * 通过订单ID或者订单号获取订单
     *
     * @param $number
     * @param $customer
     * @return Builder|Model|object|null
     */
    public static function getOrderByIdOrNumber($number, $customer = null)
    {
        $builder = Order::query()
            ->where(function ($query) use ($number) {
                $query->where('number', $number)
                    ->orWhere('id', $number);
            });

        $customerId = 0;
        if (is_int($customer)) {
            $customerId = $customer;
        } elseif ($customer instanceof Customer) {
            $customerId = $customer->id;
        }

        if ($customerId) {
            $builder->where('customer_id', $customerId);
        }

        return $builder->first();
    }

    /**
     * @param array $data
     * @return Order
     * @throws \Throwable
     */
    public static function create(array $data): Order
    {
        $customer   = $data['customer'] ?? null;
        $current    = $data['current']  ?? [];
        $carts      = $data['carts']    ?? [];
        $totals     = $data['totals']   ?? [];
        $orderTotal = collect($totals)->where('code', 'order_total')->first();

        if (current_customer()) {
            $shippingAddressId = $current['shipping_address_id'] ?? 0;
            $paymentAddressId  = $current['payment_address_id']  ?? 0;

            $shippingAddress = Address::query()->findOrFail($shippingAddressId);
            $paymentAddress  = Address::query()->findOrFail($paymentAddressId);

            $shippingAddress->country    = $shippingAddress->country->name ?? '';
            $shippingAddress->country_id = $shippingAddress->country->id   ?? 0;
            $paymentAddress->country     = $paymentAddress->country->name  ?? '';
            $paymentAddress->country_id  = $paymentAddress->country->id    ?? 0;
        } else {
            $shippingAddress = (object) ($current['guest_shipping_address'] ?? []);
            $paymentAddress  = (object) ($current['guest_payment_address'] ?? []);
        }

        $shippingMethodCode = $current['shipping_method_code'] ?? '';
        $paymentMethodCode  = $current['payment_method_code']  ?? '';

        $currencyCode  = current_currency_code();
        $currency      = CurrencyRepo::findByCode($currencyCode);
        $currencyValue = $currency->value ?? 1;

        $order = new Order([
            'number'                 => self::generateOrderNumber(),
            'customer_id'            => $customer->id                ?? 0,
            'customer_group_id'      => $customer->customer_group_id ?? 0,
            'shipping_address_id'    => $shippingAddress->id         ?? 0,
            'payment_address_id'     => $paymentAddress->id          ?? 0,
            'customer_name'          => $customer->name              ?? '',
            'email'                  => $customer ? $customer->email : $shippingAddress->email,
            'calling_code'           => $customer->calling_code ?? 0,
            'telephone'              => $customer->telephone    ?? '',
            'total'                  => $orderTotal['amount'],
            'locale'                 => locale(),
            'currency_code'          => $currencyCode,
            'currency_value'         => $currencyValue,
            'ip'                     => request()->getClientIp(),
            'user_agent'             => request()->userAgent(),
            'status'                 => StateMachineService::CREATED,
            'shipping_method_code'   => $shippingMethodCode,
            'shipping_method_name'   => trans($shippingMethodCode),
            'shipping_customer_name' => $shippingAddress->name,
            'shipping_calling_code'  => $shippingAddress->calling_code ?? 0,
            'shipping_telephone'     => $shippingAddress->phone        ?? '',
            'shipping_country'       => $shippingAddress->country      ?? '',
            'shipping_country_id'    => $shippingAddress->country_id   ?? 0,
            'shipping_zone'          => $shippingAddress->zone,
            'shipping_zone_id'       => $shippingAddress->zone_id ?? 0,
            'shipping_city'          => $shippingAddress->city,
            'shipping_address_1'     => $shippingAddress->address_1,
            'shipping_address_2'     => $shippingAddress->address_2,
            'shipping_zipcode'       => $shippingAddress->zipcode,
            'payment_method_code'    => $paymentMethodCode,
            'payment_method_name'    => trans($paymentMethodCode),
            'payment_customer_name'  => $paymentAddress->name,
            'payment_calling_code'   => $paymentAddress->calling_code ?? 0,
            'payment_telephone'      => $paymentAddress->phone        ?? '',
            'payment_country'        => $paymentAddress->country      ?? '',
            'payment_country_id'     => $paymentAddress->country_id   ?? 0,
            'payment_zone'           => $paymentAddress->zone,
            'payment_zone_id'        => $paymentAddress->zone_id ?? 0,
            'payment_city'           => $paymentAddress->city,
            'payment_address_1'      => $paymentAddress->address_1,
            'payment_address_2'      => $paymentAddress->address_2,
            'payment_zipcode'        => $paymentAddress->zipcode,
        ]);
        $order->saveOrFail();

        OrderProductRepo::createOrderProducts($order, $carts['carts']);
        OrderTotalRepo::createTotals($order, $totals);

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
        $exist       = Order::query()->where('number', $orderNumber)->exists();
        if ($exist) {
            return self::generateOrderNumber();
        }

        return $orderNumber;
    }
}
