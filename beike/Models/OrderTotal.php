<?php
/**
 * OrderTotal.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-28 11:26:47
 * @modified   2022-07-28 11:26:47
 */

namespace Beike\Models;

class OrderTotal extends Base
{
    protected $fillable = [
        'order_id', 'code', 'value', 'title', 'reference'
    ];
}
