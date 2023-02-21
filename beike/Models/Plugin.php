<?php
/**
 * Plugin.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-01 14:22:15
 * @modified   2022-07-01 14:22:15
 */

namespace Beike\Models;

class Plugin extends Base
{
    public const TYPES = [
        'shipping',  // 配送方式
        'payment',   // 支付方式
        'total',     // 订单金额
        'social',    // 社交网络
        'feature',   // 功能模块
    ];

    protected $fillable = ['type', 'code'];
}
