<?php
/**
 * TotalService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-27 17:49:15
 * @modified   2022-07-27 17:49:15
 */

namespace Beike\Shop\Services\TotalServices;

use Beike\Shop\Services\CheckoutService;

class OrderTotalService
{
    /**
     * @param CheckoutService $checkout
     * @return array
     */
    public static function getTotal(CheckoutService $checkout)
    {
        $totalService = $checkout->totalService;
        $amount       = $totalService->amount;
        $totalData    = [
            'code'          => 'order_total',
            'title'         => trans('shop/carts.order_total'),
            'amount'        => $amount,
            'amount_format' => currency_format($amount),
        ];

        $totalService->amount += $totalData['amount'];
        $totalService->totals[] = $totalData;

        return $totalData;
    }
}
