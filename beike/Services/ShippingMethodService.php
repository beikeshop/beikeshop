<?php
/**
 * ShippingMethodService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-12-01 10:54:46
 * @modified   2022-12-01 10:54:46
 */

namespace Beike\Services;

use Illuminate\Support\Str;
use Beike\Repositories\PluginRepo;
use Beike\Shop\Services\CheckoutService;

class ShippingMethodService
{
    /**
     * 获取配送方式, 二维数组, 一个配送插件对应多个配送方式
     *
     * @param CheckoutService $checkout
     * @return array
     * @throws \Exception
     */
    public static function getShippingMethods(CheckoutService $checkout): array
    {
        $shippingPlugins = PluginRepo::getShippingMethods();

        $shippingMethods = [];
        foreach ($shippingPlugins as $shippingPlugin) {
            $plugin = $shippingPlugin->plugin;
            $pluginCode = $shippingPlugin->code;
            $pluginName = Str::studly($pluginCode);
            $className = "Plugin\\{$pluginName}\\Bootstrap";

            if (!method_exists($className, 'getQuotes')) {
                throw new \Exception("请在插件 {$className} 实现方法: public function getQuotes(\$currentCart)");
            }
            $quotes = (new $className)->getQuotes($checkout, $plugin);
            if ($quotes) {
                $shippingMethods[] = [
                    'code' => $pluginCode,
                    'name' => $plugin->name,
                    'quotes' => $quotes
                ];
            }
        }
        return $shippingMethods;
    }
}
