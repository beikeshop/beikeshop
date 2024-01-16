<?php
/**
 * PluginRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-01 14:23:41
 * @modified   2022-07-01 14:23:41
 */

namespace Beike\Repositories;

use Beike\Models\Plugin;
use Beike\Plugin\Plugin as BPlugin;
use Beike\Shop\Services\TotalServices\ShippingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PluginRepo
{
    public static $installedPlugins;

    public static function getTypes(): array
    {
        $types = [];
        foreach (BPlugin::TYPES as $item) {
            $types[] = [
                'value' => $item,
                'label' => trans("admin/plugin.{$item}"),
            ];
        }

        return $types;
    }

    /**
     * 安装插件到系统: 插入数据
     * @param  BPlugin    $bPlugin
     * @throws \Exception
     */
    public static function installPlugin(BPlugin $bPlugin)
    {
        self::publishThemeFiles($bPlugin);
        self::publishLangFiles($bPlugin);
        self::migrateDatabase($bPlugin);
        if ($bPlugin->type != 'theme') {
            self::runSeeder($bPlugin);
        }
        $type   = $bPlugin->type;
        $code   = $bPlugin->code;
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
        $code       = $bPlugin->code;
        $path       = $bPlugin->getPath();
        $staticPath = $path . '/Static';
        if (is_dir($staticPath)) {
            File::copyDirectory($staticPath, public_path('plugin/' . $code));
        }
    }

    /**
     * Publish theme files, include public and blade files.
     *
     * @param $bPlugin
     */
    public static function publishThemeFiles($bPlugin): void
    {
        if ($bPlugin->getType() != 'theme') {
            return;
        }

        $publicPath = $bPlugin->getPath() . '/Static/public';
        if (is_dir($publicPath)) {
            File::copyDirectory($publicPath, public_path('/'));
        }

        $themePath = $bPlugin->getPath() . '/Themes';
        if (is_dir($themePath)) {
            File::copyDirectory($themePath, base_path('themes'));
        }
    }

    /**
     * Publish lang files
     *
     * @param $bPlugin
     * @return void
     */
    public static function publishLangFiles($bPlugin): void
    {
        if ($bPlugin->getType() != 'language') {
            return;
        }

        $langPath = $bPlugin->getPath() . '/Lang';
        if (is_dir($langPath)) {
            File::copyDirectory($langPath, resource_path('lang'));
        }
    }

    /**
     * Run plugin seeder
     *
     * @param             $bPlugin
     * @throws \Exception
     */
    public static function runSeeder($bPlugin)
    {
        $seederPath = $bPlugin->getPath() . '/Seeders/';
        if (is_dir($seederPath)) {

            $seederFiles = glob($seederPath . '*');
            foreach ($seederFiles as $seederFile) {
                $seederName = basename($seederFile, '.php');
                $className  = "\\Plugin\\{$bPlugin->getDirname()}\\Seeders\\{$seederName}";
                if (class_exists($className)) {
                    $seeder = new $className;
                    $seeder->run();
                }
            }
        }
    }

    /**
     * 数据库迁移
     */
    public static function migrateDatabase(BPlugin $bPlugin)
    {
        $migrationPath = "{$bPlugin->getPath()}/Migrations";
        if (is_dir($migrationPath)) {
            $files = glob($migrationPath . '/*');
            asort($files);

            foreach ($files as $file) {
                $file = str_replace(base_path(), '', $file);
                Artisan::call('migrate', [
                    '--force' => true,
                    '--step'  => 1,
                    '--path'  => $file,
                ]);
            }
        }
    }

    /**
     * 从系统卸载插件: 删除数据
     * @param BPlugin $bPlugin
     */
    public static function uninstallPlugin(BPlugin $bPlugin)
    {
        self::rollbackDatabase($bPlugin);
        $type = $bPlugin->type;
        $code = $bPlugin->code;
        Plugin::query()
            ->where('type', $type)
            ->where('code', $code)
            ->delete();
    }

    /**
     * 从 public 删除静态资源
     * @param BPlugin $bPlugin
     */
    public static function removeStaticFiles(BPlugin $bPlugin)
    {
        $code       = $bPlugin->code;
        $path       = $bPlugin->getPath();
        $staticPath = $path . '/static';
        if (is_dir($staticPath)) {
            File::deleteDirectory(public_path('plugin/' . $code));
        }
    }

    /**
     * 数据库回滚
     */
    public static function rollbackDatabase(BPlugin $bPlugin)
    {
        $migrationPath = "{$bPlugin->getPath()}/Migrations";
        if (is_dir($migrationPath)) {
            $files = glob($migrationPath . '/*');
            arsort($files);

            foreach ($files as $file) {
                $file = str_replace(base_path(), '', $file);
                Artisan::call('migrate:rollback', [
                    '--force' => true,
                    '--step'  => 1,
                    '--path'  => $file,
                ]);
            }
        }
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

    /**
     * 判断插件是否安装
     *
     * @param $code
     * @return bool
     */
    public static function enabled($code): bool
    {
        $code = Str::camel($code);

        return SettingRepo::getPluginStatus($code);
    }

    /**
     * 获取所有已安装插件列表
     *
     * @return Plugin[]|Collection
     */
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
    public static function getPluginsByCode(): Collection|array
    {
        $allPlugins = self::allPlugins();

        return $allPlugins->keyBy('code');
    }

    /**
     * 获取所有配送方式
     */
    public static function getShippingMethods(): Collection
    {
        $allPlugins = self::allPlugins();

        return $allPlugins->where('type', 'shipping')->filter(function ($item) {
            $plugin = plugin($item->code);
            if ($plugin) {
                $item->plugin = $plugin;
            }

            return $plugin && $plugin->getEnabled();
        });
    }

    /**
     * 获取所有支付方式
     */
    public static function getPaymentMethods(): Collection
    {
        $allPlugins = self::allPlugins();

        $result = $allPlugins->where('type', 'payment')->filter(function ($item) {
            $plugin = plugin($item->code);
            if ($plugin) {
                $item->plugin = $plugin;
            }

            return $plugin && $plugin->getEnabled();
        });

        return hook_filter('repo.plugin.payment_methods', $result);
    }

    public static function getTranslators()
    {
        $allPlugins = self::allPlugins();

        return $allPlugins->where('type', 'translator')->filter(function ($item) {
            $plugin = plugin($item->code);
            if ($plugin) {
                $item->plugin = $plugin;
            }

            return $plugin && $plugin->getEnabled();
        });
    }

    /**
     * Get all enabled themes
     */
    public static function getEnabledThemes(): Collection
    {
        $allPlugins = self::allPlugins();

        return $allPlugins->where('type', 'theme')->filter(function ($item) {
            $plugin = plugin($item->code);
            if ($plugin) {
                $item->plugin = $plugin;
            }

            return $plugin && $plugin->getEnabled();
        });
    }

    /**
     * 检测对应配送方式是否可用
     *
     * @param $code
     * @return bool
     */
    public static function shippingEnabled($code): bool
    {
        $code            = ShippingService::parseShippingPluginCode($code);
        $shippingMethods = self::getShippingMethods();

        return $shippingMethods->where('code', $code)->count() > 0;
    }

    /**
     * 检测对应支付方式是否可用
     *
     * @param $code
     * @return bool
     */
    public static function paymentEnabled($code): bool
    {
        $paymentMethods = self::getPaymentMethods();

        return $paymentMethods->where('code', $code)->count() > 0;
    }
}
