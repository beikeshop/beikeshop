<?php
/**
 * ProductCategory.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-05-23 11:04:40
 * @modified   2022-05-23 11:04:40
 */

namespace Beike\Models;

class ProductCategory extends Base
{
    protected $table = 'product_categories';

    protected $fillable = ['product_id', 'category_id'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
