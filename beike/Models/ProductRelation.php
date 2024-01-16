<?php
/**
 * ProductRelation.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-12-30 11:04:40
 * @modified   2022-12-30 11:04:40
 */

namespace Beike\Models;

class ProductRelation extends Base
{
    protected $table = 'product_relations';

    protected $fillable = ['product_id', 'relation_id'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }
}
