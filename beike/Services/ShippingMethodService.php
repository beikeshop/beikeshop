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

use Beike\Admin\Http\Resources\PluginResource;
use Beike\Repositories\CartRepo;
use Beike\Repositories\PluginRepo;
use Beike\Shop\Services\CheckoutService;
use Illuminate\Support\Str;

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
            $plugin     = $shippingPlugin->plugin;
            $pluginCode = $shippingPlugin->code;
            $pluginName = Str::studly($pluginCode);
            $className  = "Plugin\\{$pluginName}\\Bootstrap";

            if (! method_exists($className, 'getQuotes')) {
                throw new \Exception("请在插件 {$className} 实现方法: public function getQuotes(\$currentCart)");
            }
            $quotes = (new $className)->getQuotes($checkout, $plugin);
            if ($quotes) {
                $pluginResource    = (new PluginResource($plugin))->jsonSerialize();
                $shippingMethods[] = [
                    'code'   => $pluginCode,
                    'name'   => $pluginResource['name'],
                    'quotes' => $quotes,
                ];
            }
        }

        $data = hook_filter('service.shipping_method.data', ['shipping_methods' => $shippingMethods, 'checkout' => $checkout]);

        return $data['shipping_methods'];
    }

    /**
     * 获取配送方式, 二维数组, 一个配送插件对应多个配送方式
     *
     * @param CheckoutService $checkout
     * @return array
     * @throws \Exception
     */
    public static function getShippingMethodsForCurrentCart(CheckoutService $checkout): array
    {
        $customerId = current_customer()->id ?? 0;
        if (! CartRepo::shippingRequired($customerId)) {
            return [];
        }

        return self::getShippingMethods($checkout);
    }

    /**
     * 获取当前使用的配送方式
     *
     * @param $shipments
     * @param $methodCode
     * @return void
     */
    public static function getCurrentQuote($shipments, $methodCode)
    {
        if (empty($shipments) || empty($methodCode)) {
            return null;
        }
        foreach ($shipments as $method) {
            $quotes = $method['quotes'] ?? [];
            foreach ($quotes as $quote) {
                if ($quote['code'] == $methodCode) {
                    return $quote;
                }
            }
        }

        return null;
    }
}
