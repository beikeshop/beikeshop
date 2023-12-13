<?php
/**
 * Order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-04 17:24:42
 * @modified   2022-07-04 17:24:42
 */

namespace Beike\Models;

use Beike\Notifications\NewOrderNotification;
use Beike\Notifications\UpdateOrderNotification;
use Beike\Services\StateMachineService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Order extends Base
{
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'number', 'customer_id', 'customer_group_id', 'shipping_address_id', 'payment_address_id', 'customer_name',
        'email', 'calling_code', 'telephone', 'total', 'locale', 'currency_code', 'currency_value', 'ip', 'user_agent',
        'comment', 'status', 'shipping_method_code', 'shipping_method_name', 'shipping_customer_name', 'shipping_calling_code',
        'shipping_telephone', 'shipping_country', 'shipping_country_id', 'shipping_zone', 'shipping_zone_id', 'shipping_city',
        'shipping_address_1', 'shipping_zipcode', 'shipping_address_2', 'payment_method_code', 'payment_method_name',
        'payment_customer_name', 'payment_calling_code', 'payment_telephone', 'payment_country', 'payment_country_id',
        'payment_zone', 'payment_zone_id', 'payment_city', 'payment_address_1', 'payment_address_2', 'payment_zipcode',
    ];

    protected $appends = ['status_format', 'total_format'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderTotals(): HasMany
    {
        return $this->hasMany(OrderTotal::class);
    }

    public function orderHistories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function orderShipments(): HasMany
    {
        return $this->hasMany(OrderShipment::class);
    }

    public function orderPayments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function subTotal()
    {
        $totals = $this->orderTotals;

        return $totals->where('code', 'sub_total')->first();
    }

    public function getStatusFormatAttribute()
    {
        if ($this->status === null) {
            return null;
        }
        $statusMap = array_column(StateMachineService::getAllStatuses(), 'name', 'status');

        return $statusMap[$this->status] ?? '';
    }

    public function getTotalFormatAttribute()
    {
        if ($this->total === null) {
            return null;
        }

        return currency_format($this->total, $this->currency_code, $this->currency_value);
    }

    /**
     * 获取该订单可以变更的状态
     *
     * @return array
     * @throws \Exception
     */
    public function getNextStatuses()
    {
        return StateMachineService::getInstance($this)->nextBackendStatuses();
    }

    /**
     * 获取订单所有商品名称并用;分割, 如果网站名称超出长度则只保留产品名称
     *
     * @param int $length
     * @return string
     */
    public function getOrderDesc(int $length = 256): string
    {
        $productsName = trim(system_setting('base.meta_title'));
        if (strlen($productsName) > $length) {
            $productsName = '';
        }

        foreach ($this->orderProducts as $product) {
            $newProductsName = $productsName;
            if ($newProductsName) {
                $newProductsName .= '; ';
            }
            $newProductsName .= $product->name;
            if (strlen($newProductsName) > $length) {
                return $productsName;
            }
            $productsName = $newProductsName;
        }

        return $productsName;
    }

    /**
     * 新订单通知
     */
    public function notifyNewOrder()
    {
        $useQueue = system_setting('base.use_queue', true);
        if ($useQueue) {
            $this->notify(new NewOrderNotification($this));
        } else {
            $this->notifyNow(new NewOrderNotification($this));
        }
    }

    /**
     * 订单状态更新通知
     */
    public function notifyUpdateOrder($fromCode)
    {
        $useQueue = system_setting('base.use_queue', true);
        if ($useQueue) {
            $this->notify(new UpdateOrderNotification($this, $fromCode));
        } else {
            $this->notifyNow(new UpdateOrderNotification($this, $fromCode));
        }
    }
}
