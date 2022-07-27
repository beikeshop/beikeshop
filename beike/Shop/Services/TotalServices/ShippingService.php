<?php

/**
 * ShippingService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-22 17:58:14
 * @modified   2022-07-22 17:58:14
 */

namespace Beike\Shop\Services\TotalServices;

use Beike\Shop\Services\TotalService;

class ShippingService
{
    public static function getTotal(TotalService $totalService)
    {
        $shippingMethod = $totalService->shippingMethod;
        if (empty($shippingMethod)) {
            return null;
        }
        $amount = 5;
        return [
            'code' => 'shipping',
            'title' => '运费',
            'amount' => $amount,
            'amount_format' => currency_format($amount)
        ];
    }
}
