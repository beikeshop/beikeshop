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
     *
     * @param Manager $manager
     * @throws \Exception
     */
    public function boot(Manager $manager)
    {
        $plugins = $manager->getPlugins();
        $bootstraps = $manager->getEnabledBootstraps();
        foreach ($bootstraps as $bootstrap) {
            $filePath = $bootstrap['file'];
            $pluginCode = $bootstrap['code'];
            if (file_exists($filePath)) {
                require_once $filePath;
                $className = "Plugin\\{$pluginCode}\\Bootstrap";
                (new $className)->boot();
            }
        }
    }
}
