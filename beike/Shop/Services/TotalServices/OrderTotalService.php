<?php
/**
 * TotalService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-27 17:49:15
 * @modified   2022-07-27 17:49:15
 */

namespace Beike\Shop\Services\TotalServices;

use Beike\Shop\Services\TotalService;

class OrderTotalService
{
    public static function getTotal(TotalService $totalService)
    {
        $amount = $totalService->amount;
        $totalData = [
            'code' => 'order_total',
            'title' => '应付总金额',
            'amount' => $amount,
            'amount_format' => currency_format($amount)
        ];

        $totalService->amount += $totalData['amount'];
        $totalService->totals[] = $totalData;

        return $totalData;
    }
}
