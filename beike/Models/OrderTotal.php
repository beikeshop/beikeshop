<?php
/**
 * OrderTotal.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-28 11:26:47
 * @modified   2022-07-28 11:26:47
 */

namespace Beike\Models;

class OrderTotal extends Base
{
    protected $fillable = [
        'order_id', 'code', 'value', 'title', 'reference',
    ];

    protected $appends = ['value_format'];

    public function getValueFormatAttribute()
    {
        return currency_format($this->value);
    }
}
