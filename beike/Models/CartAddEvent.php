<?php

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartAddEvent extends Base
{
    protected $fillable = [
        'customer_id',
        'visitor_key',
        'product_id',
        'product_sku',
        'product_name',
        'sku_model',
        'quantity',
    ];

    /**
     * 加购事件属于主库，不能随商品目录切换到 catalog_public。
     */
    public function getConnectionName(): ?string
    {
        if ($this->connection !== null) {
            return parent::getConnectionName();
        }

        return (string) config('database.default', 'mysql');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }
}
