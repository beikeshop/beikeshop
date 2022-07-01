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
use Illuminate\Database\Eloquent\Collection;

class PluginRepo
{
    public static $installedPlugins;

    /**
     * 判断插件是否安装
     *
     * @param $code
     * @return bool
     */
    public static function installed($code): bool
    {
        $plugins = self::listByCode();
        return $plugins->has($code);
    }


    /**
     * 获取所有已安装插件
     * @return Plugin[]|Collection
     */
    public static function listByCode()
    {
        if (self::$installedPlugins !== null) {
            return self::$installedPlugins;
        }
        return self::$installedPlugins = Plugin::all()->keyBy('code');
    }
}
