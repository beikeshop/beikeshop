<?php
/**
 * PageProduct.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-10 14:49:45
 * @modified   2023-02-10 14:49:45
 */

namespace Beike\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageProduct extends Base
{
    protected $table = 'page_products';

    protected $fillable = ['page_id', 'product_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
