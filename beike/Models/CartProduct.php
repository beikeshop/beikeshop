<?php
/**
 * CartProduct.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-04 16:38:21
 * @modified   2022-07-04 16:38:21
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartProduct extends Base
{
    use HasFactory;

    protected $fillable = ['customer_id', 'session_id', 'selected', 'product_id', 'product_sku', 'quantity'];

    public function sku(): BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'product_sku', 'sku');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
