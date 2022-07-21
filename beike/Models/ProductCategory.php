<?php
/**
 * ProductCategory.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <sam.chen@opencart.cn>
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
