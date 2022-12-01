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
     * 获取固定运费方式
     *
     * @param $currentCart
     * @param $shippingPlugin
     * @return array
     * @throws \Exception
     */
    public function getQuotes($currentCart, $shippingPlugin): array
    {
        $code = 'flat_shipping';
        $pluginSetting = $shippingPlugin->plugin;
        $quotes[] = [
            'type' => 'shipping',
            'code' => "{$code}.0",
            'name' => $pluginSetting->name,
            'description' => $pluginSetting->description,
            'icon' => plugin_resize($code, $pluginSetting->icon),
        ];
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
