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

class TotalService
{
    const TOTAL_CODES = [
        'subtotal',
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
            $totals[] = 11;
        }

        // hook= [subtotal, shipping]

        return [];
    }
}
