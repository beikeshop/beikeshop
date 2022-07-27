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
        $plugins = $manager->getPlugins();
        $bootstraps = $manager->getEnabledBootstraps();
        $pluginBasePath = base_path('plugins');

        foreach ($bootstraps as $bootstrap) {
            $filePath = $bootstrap['file'];
            $pluginCode = $bootstrap['code'];
            if (file_exists($filePath)) {
                $className = "Plugin\\{$pluginCode}\\Bootstrap";
                if (method_exists($className, 'boot')) {
                    (new $className)->boot();
                }
            }

            $routePath = "{$pluginBasePath}/{$pluginCode}/routes.php";
            if (file_exists($routePath)) {
                Route::prefix('plugin')
                    ->middleware('web')
                    ->group(function () use ($routePath) {
                        $this->loadRoutesFrom($routePath);
                    });
            }

            $this->loadViewsFrom("{$pluginBasePath}/{$pluginCode}/Views", $pluginCode);
        }
    }
}
