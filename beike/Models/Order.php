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

use Illuminate\Notifications\Notifiable;
use Beike\Notifications\NewOrderNotification;
use Beike\Notifications\UpdateOrderNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Base
{
    use Notifiable;

    protected $fillable = [
        'number', 'customer_id', 'customer_group_id', 'shipping_address_id', 'payment_address_id', 'customer_name',
        'email', 'calling_code', 'telephone', 'total', 'locale', 'currency_code', 'currency_value', 'ip', 'user_agent',
        'status', 'shipping_method_code', 'shipping_method_name', 'shipping_customer_name', 'shipping_calling_code',
        'shipping_telephone', 'shipping_country', 'shipping_country_id', 'shipping_zone', 'shipping_zone_id', 'shipping_city', 'shipping_address_1',
        'shipping_zipcode', 'shipping_address_2', 'payment_method_code', 'payment_method_name', 'payment_customer_name',
        'payment_calling_code', 'payment_telephone', 'payment_country', 'payment_country_id', 'payment_zone', 'payment_zone_id', 'payment_city',
        'payment_address_1', 'payment_address_2', 'payment_zipcode',
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

    public function getStatusFormatAttribute()
    {
        return trans('order.' . $this->status);
    }

    public function getTotalFormatAttribute()
    {
        return currency_format($this->total, $this->currency_code, $this->currency_value);
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
