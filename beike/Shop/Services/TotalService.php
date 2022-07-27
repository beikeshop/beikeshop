<?php
/**
 * TotalService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-22 17:11:31
 * @modified   2022-07-22 17:11:31
 */

namespace Beike\Shop\Services;

use \Beike\Shop\Services\TotalServices;
use Illuminate\Support\Str;

class TotalService
{
    const TOTAL_CODES = [
        'subtotal',
        'tax',
        'shipping',
        'total'
    ];

    /**
     * @return array
     */
    public static function getTotals(): array
    {
        $totals = [];
        foreach (self::TOTAL_CODES as $code) {
            $serviceName = Str::studly($code);
            $service = "TotalServices\{$serviceName}";
            $totals[] = $service::getTotal();
        }

        return $totals;
    }
}
