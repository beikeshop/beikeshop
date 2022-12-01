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

use Beike\Shop\Http\Resources\Checkout\ShippingMethodItem;

class Bootstrap
{
    /**
     * 获取固定运费方式
     *
     * @param $currentCart
     * @param $shippingPlugin
     * @return array
     */
    public function getQuotes($currentCart, $shippingPlugin): array
    {
        $quotes['flat_shipping.0'] = (new ShippingMethodItem($shippingPlugin))->jsonSerialize();
        return $quotes;
    }


    /**
     * 计算固定运费
     *
     * @param $totalService
     * @return float|int
     */
    public function getShippingFee($totalService): float|int
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
