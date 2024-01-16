<?php

/**
 * SubtotalService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-22 17:58:25
 * @modified   2022-07-22 17:58:25
 */

namespace Beike\Shop\Services\TotalServices;

use Beike\Shop\Services\CheckoutService;

class SubtotalService
{
    /**
     * @param CheckoutService $checkout
     * @return array
     */
    public static function getTotal(CheckoutService $checkout)
    {
        $totalService = $checkout->totalService;
        $amount       = $totalService->getSubTotal();
        $totalData    = [
            'code'          => 'sub_total',
            'title'         => trans('shop/carts.product_total'),
            'amount'        => $amount,
            'amount_format' => currency_format($amount),
        ];

        $totalService->amount += $totalData['amount'];
        $totalService->totals[] = $totalData;

        return $totalData;
    }
}
