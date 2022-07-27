<?php

/**
 * TaxService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-27 14:24:05
 * @modified   2022-07-27 14:24:05
 */

namespace Beike\Shop\Services\TotalServices;

use Beike\Shop\Services\TotalService;

class TaxService
{
    public static function getTotal(TotalService $totalService)
    {
        $amount = $totalService->amount * 0.02;
        return [
            'code' => 'tax',
            'title' => '税费',
            'amount' => $amount,
            'amount_format' => currency_format($amount)
        ];
    }
}
