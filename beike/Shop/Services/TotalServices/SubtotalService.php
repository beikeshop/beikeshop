<?php

/**
 * SubtotalService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-22 17:58:25
 * @modified   2022-07-22 17:58:25
 */

namespace Beike\Shop\Services\TotalServices;

use Beike\Shop\Services\TotalService;

class SubtotalService
{
    public static function getTotal(TotalService $totalService)
    {
        $carts = $totalService->carts;
        $amount = collect($carts)->sum('subtotal');
        $totalData = [
            'code' => 'sub_total',
            'title' => '商品总额',
            'amount' => $amount,
            'amount_format' => currency_format($amount)
        ];

        $totalService->amount += $totalData['amount'];
        $totalService->totals[] = $totalData;

        return $totalData;
    }
}
