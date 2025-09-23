<?php
/**
 * Rma.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-01 20:22:18
 * @modified   2022-08-01 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Beike\Notifications\NewRmaNotification;
use Beike\Notifications\NewRmaAlertNotification;
use Illuminate\Notifications\Notifiable;

class Rma extends Base
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['order_id', 'order_product_id', 'customer_id', 'name', 'email', 'telephone', 'product_name', 'sku', 'quantity', 'images', 'opened', 'rma_reason_id', 'type', 'status', 'comment'];

    protected $casts = ['images' => 'json'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderProduct(): BelongsTo
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(RmaReason::class, 'rma_reason_id', 'id');
    }

    /**
     * 退货通知
     */
    public function notifyCreateRma()
    {
        $useQueue = system_setting('base.use_queue', true);
        if ($useQueue) {
            $this->notify(new NewRmaNotification($this));
        } else {
            $this->notifyNow(new NewRmaNotification($this));
        }
    }

    public function notifyAlertCreateRma()
    {
        $useQueue = system_setting('base.use_queue', true);
        if ($useQueue) {
            $this->notify(new NewRmaAlertNotification($this));
        } else {
            $this->notifyNow(new NewRmaAlertNotification($this));
        }
    }

    public function histories(): HasMany
    {
        return $this->hasMany(RmaHistory::class);
    }

    public function getTypeFormatAttribute(): mixed
    {
        return trans("rma.type_{$this->type}");
    }

    public function getStatusFormatAttribute(): mixed
    {
        return trans("rma.status_{$this->status}");
    }
}
