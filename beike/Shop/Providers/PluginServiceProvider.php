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
                require_once $filePath;
                $className = "Plugin\\{$pluginCode}\\Bootstrap";
                (new $className)->boot();
            }

            $this->loadViewsFrom("{$pluginBasePath}/{$pluginCode}/Views", $pluginCode);

            Route::prefix('plugin')
                ->middleware('web')
                ->group(function () use ($pluginBasePath, $pluginCode) {
                    $this->loadRoutesFrom("{$pluginBasePath}/{$pluginCode}/routes.php");
                });
        }
    }
}
