<?php
/**
 * OrderShipment.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-12-16 18:37:24
 * @modified   2022-12-16 18:37:24
 */

namespace Beike\Models;

class OrderShipment extends Base
{
    protected $table = 'order_shipments';

    protected $fillable = [
        'order_id', 'express_code', 'express_company', 'express_number',
    ];
}
