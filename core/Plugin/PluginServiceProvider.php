<?php
/**
 * PluginServiceProvider.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-04-19 11:19:26
 * @modified   2022-04-19 11:19:26
 */

namespace Beike\Plugin;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('plugin', PluginManager::class);
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     * @throws BindingResolutionException
     * @throws FileNotFoundException
     */
    public function boot(PluginManager $pluginManager)
    {
        $srcPaths = [];
        $loader = $this->app->make('translation.loader');
        $viewFinder = $this->app->make('view');

        foreach ($pluginManager->getPlugins() as $plugin) {
            if ($plugin->isEnabled()) {
                $srcPaths[$plugin->getNameSpace()] = $plugin->getPath() . '/src';
                $viewFinder->addNamespace($plugin->getNameSapace(), $plugin->getPath() . '/views');
            }
            $loader->addNamespace($plugin->getNameSpace(), $plugin->getPath() . "/lang");
        }

        $this->registerClassAutoloader($srcPaths);

        $bootstrappers = $pluginManager->getEnabledBootstrappers();

        foreach ($bootstrappers as $file) {
            $bootstrapper = require $file;
            $this->app->call($bootstrapper);
        }
    }

    /**
     * Register class autoloader for plugins.
     *
     * @return void
     */
    protected function registerClassAutoloader($paths)
    {
        spl_autoload_register(function ($class) use ($paths) {
            foreach ((array)array_keys($paths) as $namespace) {
                if ($namespace != '' && mb_strpos($class, $namespace) === 0) {
                    $path = $paths[$namespace] . Str::replaceFirst($namespace, '', $class) . ".php";
                    $path = str_replace('\\', '/', $path);

                    if (file_exists($path)) {
                        include $path;
                    }
                }
            }
        });
    }
}
