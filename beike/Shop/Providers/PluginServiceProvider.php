<?php
/**
 * PluginServiceProvider.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-20 14:42:10
 * @modified   2022-07-20 14:42:10
 */

namespace Beike\Shop\Providers;

use Beike\Plugin\Manager;
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
        $manager = app('plugin');
        $bootstraps = $manager->getEnabledBootstraps();
        $this->pluginBasePath = base_path('plugins');

        foreach ($bootstraps as $bootstrap) {
            $pluginCode = $bootstrap['code'];
            $this->bootPlugin($bootstrap);
            $this->loadRoutes($pluginCode);
            $this->loadTranslations($pluginCode);
            $this->loadViews($pluginCode);
        }
    }


    /**
     * 调用插件 Bootstrap::boot()
     *
     * @param $bootstrap
     */
    private function bootPlugin($bootstrap)
    {
        $filePath = $bootstrap['file'];
        $pluginCode = $bootstrap['code'];
        if (file_exists($filePath)) {
            $className = "Plugin\\{$pluginCode}\\Bootstrap";
            if (method_exists($className, 'boot')) {
                (new $className)->boot();
            }
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
        $routePath = "{$pluginBasePath}/{$pluginCode}/routes.php";
        if (file_exists($routePath)) {
            Route::prefix('plugin')
                ->middleware('web')
                ->group(function () use ($routePath) {
                    $this->loadRoutesFrom($routePath);
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
