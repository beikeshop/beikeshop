<?php
/**
 * OrderProduct.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-04 21:18:54
 * @modified   2022-07-04 21:18:54
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Base
{
    protected $fillable = [
        'product_id', 'order_number', 'product_sku', 'name', 'image', 'quantity', 'price',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
