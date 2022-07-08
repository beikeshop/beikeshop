<?php
/**
 * PluginRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-01 14:23:41
 * @modified   2022-07-01 14:23:41
 */

namespace Beike\Repositories;

use Beike\Models\Plugin;
use Beike\Plugin\Manager;
use Beike\Plugin\Plugin as BPlugin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\File;

class PluginRepo
{
    public static $installedPlugins;

    /**
     * 安装插件到系统: 插入数据
     * @param BPlugin $bPlugin
     */
    public static function installPlugin(BPlugin $bPlugin)
    {
        self::publishStaticFiles($bPlugin);
        $type = $bPlugin->type;
        $code = $bPlugin->code;
        $plugin = Plugin::query()
            ->where('type', $type)
            ->where('code', $code)
            ->first();
        if (empty($plugin)) {
            Plugin::query()->create([
                'type' => $type,
                'code' => $code,
            ]);
        }
    }


    /**
     * 发布静态资源到 public
     * @param BPlugin $bPlugin
     */
    public static function publishStaticFiles(BPlugin $bPlugin)
    {
        $code = $bPlugin->code;
        $path = $bPlugin->getPath();
        $staticPath = $path . '/static';
        if (is_dir($staticPath)) {
            \Illuminate\Support\Facades\File::copyDirectory($staticPath, public_path('plugin/' . $code));
        }
    }


    /**
     * 从系统卸载插件: 删除数据
     * @param $plugin
     */
    public static function uninstallPlugin($plugin)
    {
        $type = $plugin->type;
        $code = $plugin->code;
        Plugin::query()
            ->where('type', $type)
            ->where('code', $code)
            ->delete();
    }


    /**
     * 判断插件是否安装
     *
     * @param $code
     * @return bool
     */
    public static function installed($code): bool
    {
        $plugins = self::getPluginsByCode();
        return $plugins->has($code);
    }


    public static function allPlugins()
    {
        if (self::$installedPlugins !== null) {
            return self::$installedPlugins;
        }
        return self::$installedPlugins = Plugin::all();
    }


    /**
     * 获取所有已安装插件
     * @return Plugin[]|Collection
     */
    public static function getPluginsByCode()
    {
        $allPlugins = self::allPlugins();
        return $allPlugins->keyBy('code');
    }


    /**
     * 获取所有配送方式
     */
    public static function getShippingMethods()
    {
        $allPlugins = self::allPlugins();
        return $allPlugins->where('type', 'shipping')->filter(function ($item) {
            $plugin = (new Manager)->getPlugin($item->code);
            if ($plugin) {
                $item->plugin = $plugin;
            }
            return $plugin && $plugin->getEnabled();
        });
    }


    /**
     * 获取所有支付方式
     */
    public static function getPaymentMethods()
    {
        $allPlugins = self::allPlugins();
        return $allPlugins->where('type', 'payment')->filter(function ($item) {
            $plugin = (new Manager)->getPlugin($item->code);
            if ($plugin) {
                $item->plugin = $plugin;
            }
            return $plugin && $plugin->getEnabled();
        });
    }
}
