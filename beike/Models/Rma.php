<?php
/**
 * Rma.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-08-01 20:22:18
 * @modified   2022-08-01 20:22:18
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rma extends Base
{
    use HasFactory;

    protected $fillable = ['order_id','order_product_id','customer_id','name','email','telephone','product_name','sku','quantity','opened','rma_reason_id','type','status','comment'];

    public function order() :BelongsTo
    {
        return $this->belongsTo(Order::Class);
    }

    public function orderProduct() :BelongsTo
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function customer() :BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reason() :BelongsTo
    {
        return $this->belongsTo(RmaReason::class);
    }

    public function histories() :HasMany
    {
        return $this->hasMany(RmaHistory::class);
    }
}
