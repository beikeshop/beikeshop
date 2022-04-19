<?php
/**
 * PluginManager.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-04-18 16:21:06
 * @modified   2022-04-18 16:21:06
 */

namespace Beike\Plugin;

use App\Exceptions\PrettyPageException;
use App\Repositories\SettingRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class PluginManager
{
    protected $app;
    protected $setting;
    protected $dispatcher;
    protected $filesystem;
    protected $plugins;

    public function __construct(
        Application       $app,
        SettingRepository $setting,
        Dispatcher        $dispatcher,
        Filesystem        $fileSystem
    )
    {
        throw new \Exception(222);
        $this->app = $app;
        $this->setting = $setting;
        $this->dispatcher = $dispatcher;
        $this->filesystem = $fileSystem;
    }

    /**
     * Get all plugins
     *
     * @return Collection
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getPlugins(): Collection
    {
        if (is_null($this->plugins)) {
            $plugins = new Collection();

            $installed = [];

            try {
                $resource = opendir($this->getPluginsDir());
            } catch (\Exception $e) {
                throw new PrettyPageException(trans('errors.plugins.directory', ['msg' => $e->getMessage()]), 500);
            }

            // traverse plugins dir
            while ($filename = @readdir($resource)) {
                if ($filename == '.' || $filename == '..')
                    continue;

                $path = $this->getPluginsDir() . DIRECTORY_SEPARATOR . $filename;

                if (is_dir($path)) {
                    $packageJsonPath = $path . DIRECTORY_SEPARATOR . 'package.json';

                    if (file_exists($packageJsonPath)) {
                        // load packages installed
                        $installed[$filename] = json_decode($this->filesystem->get($packageJsonPath), true);
                    }
                }

            }
            closedir($resource);

            foreach ($installed as $dirname => $package) {

                // Instantiates an Plugin object using the package path and package.json file.
                $plugin = new Plugin($this->getPluginsDir() . DIRECTORY_SEPARATOR . $dirname, $package);

                // Per default all plugins are installed if they are registered in composer.
                $plugin->setDirname($dirname);
                $plugin->setInstalled(true);
                $plugin->setNameSpace(Arr::get($package, 'namespace'));
                $plugin->setVersion(Arr::get($package, 'version'));
                $plugin->setEnabled($this->isEnabled($plugin->name));

                if ($plugins->has($plugin->name)) {
                    throw new PrettyPageException(trans('errors.plugins.duplicate', [
                        'dir1' => $plugin->getDirname(),
                        'dir2' => $plugins->get($plugin->name)->getDirname()
                    ]), 5);
                }

                $plugins->put($plugin->name, $plugin);
            }

            $this->plugins = $plugins->sortBy(function ($plugin, $name) {
                return $plugin->name;
            });
        }

        return $this->plugins;
    }

    /**
     * The id's of the enabled plugins.
     *
     * @return array
     */
    public function getEnabled()
    {
        return (array) json_decode($this->setting->get('plugins_enabled'), true);
    }


    /**
     * Get only enabled plugins.
     *
     * @return Collection
     */
    public function getEnabledPlugins()
    {
        return $this->getPlugins()->only($this->getEnabled());
    }

    /**
     * Loads all bootstrap.php files of the enabled plugins.
     *
     * @return Collection
     */
    public function getEnabledBootstrappers(): Collection
    {
        $bootstrappers = new Collection;

        foreach ($this->getEnabledPlugins() as $plugin) {
            if ($this->filesystem->exists($file = $plugin->getPath() . '/bootstrap.php')) {
                $bootstrappers->push($file);
            }
        }

        return $bootstrappers;
    }
}
