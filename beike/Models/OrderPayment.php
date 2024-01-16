<?php
/**
 * OrderPayment.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-05-25 10:02:52
 * @modified   2023-05-25 10:02:52
 */

namespace Beike\Models;

class OrderPayment extends Base
{
    protected $table = 'order_payments';

    protected $fillable = [
        'order_id', 'transaction_id', 'request', 'response', 'callback', 'receipt',
    ];
}
