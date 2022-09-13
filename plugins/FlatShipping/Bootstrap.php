<?php
/**
 * bootstrap.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-20 15:35:59
 * @modified   2022-07-20 15:35:59
 */

namespace Plugin\FlatShipping;

class Bootstrap
{
    /**
     * @return float
     */
    public function getShippingFee($totalService)
    {
        $amount = $totalService->amount;
        $shippingType = plugin_setting('flat_shipping.type', 'fixed');
        $shippingValue = plugin_setting('flat_shipping.value', 0);
        if ($shippingType == 'fixed') {
            return $shippingValue;
        } elseif ($shippingType == 'percent') {
            return $amount * $shippingValue / 100;
        } else {
            return 0;
        }
    }
}
