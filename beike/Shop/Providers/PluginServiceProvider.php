<?php
/**
 * PluginServiceProvider.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-20 14:42:10
 * @modified   2022-07-20 14:42:10
 */

namespace Beike\Shop\Providers;

use Beike\Plugin\Manager;
use Beike\Models\AdminUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    private string $pluginBasePath = '';

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('plugin', function () {
            return new Manager();
        });
    }


    /**
     * Bootstrap Plugin Service Provider
     */
    public function boot()
    {
        if (!installed()) {
            return;
        }
        $manager = app('plugin');
        $plugins = $manager->getEnabledPlugins();
        $this->pluginBasePath = base_path('plugins');

        foreach ($plugins as $plugin) {
            $this->bootPlugin($plugin);
        }

        $allPlugins = $manager->getPlugins();
        foreach ($allPlugins as $plugin) {
            $pluginCode = $plugin->getDirname();
            $this->loadMigrations($pluginCode);
            $this->loadRoutes($pluginCode);
            $this->loadViews($pluginCode);
            $this->loadTranslations($pluginCode);
        }
    }


    /**
     * 调用插件 Bootstrap::boot()
     *
     * @param $plugin
     */
    private function bootPlugin($plugin)
    {
        $filePath = $plugin->getBootFile();
        $pluginCode = $plugin->getDirname();
        if (file_exists($filePath)) {
            $className = "Plugin\\{$pluginCode}\\Bootstrap";
            if (method_exists($className, 'boot')) {
                (new $className)->boot();
            }
        }
    }


    /**
     * 加载插件数据库迁移脚本
     *
     * @param $pluginCode
     */
    private function loadMigrations($pluginCode)
    {
        $migrationPath = "{$this->pluginBasePath}/{$pluginCode}/Migrations";
        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom("{$migrationPath}");
        }
    }


    /**
     * 加载插件路由
     *
     * @param $pluginCode
     */
    private function loadRoutes($pluginCode)
    {
        $pluginBasePath = $this->pluginBasePath;
        $shopRoutePath = "{$pluginBasePath}/{$pluginCode}/Routes/shop.php";
        if (file_exists($shopRoutePath)) {
            Route::prefix('plugin')
                ->middleware('shop')
                ->group(function () use ($shopRoutePath) {
                    $this->loadRoutesFrom($shopRoutePath);
                });
        }

        $adminRoutePath = "{$pluginBasePath}/{$pluginCode}/Routes/admin.php";
        if (file_exists($adminRoutePath)) {
            $adminName = admin_name();
            Route::prefix($adminName)
                ->name('admin.')
                ->middleware(['admin', 'admin_auth:' . AdminUser::AUTH_GUARD])
                ->group(function () use ($adminRoutePath) {
                    $this->loadRoutesFrom($adminRoutePath);
                });
        }
    }


    /**
     * 加载多语言
     */
    private function loadTranslations($pluginCode)
    {
        $pluginBasePath = $this->pluginBasePath;
        $this->loadTranslationsFrom("{$pluginBasePath}/{$pluginCode}/Lang", $pluginCode);
    }


    /**
     * 加载模板目录
     *
     * @param $pluginCode
     */
    private function loadViews($pluginCode)
    {
        $pluginBasePath = $this->pluginBasePath;
        $this->loadViewsFrom("{$pluginBasePath}/{$pluginCode}/Views", $pluginCode);
    }
}
