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

use App\Repositories\SettingRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;

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
        $this->app = $app;
        $this->setting = $setting;
        $this->dispatcher = $dispatcher;
        $this->filesystem = $fileSystem;
    }

    /**
     * Get all plugins
     *
     * @return Collection
     */
    public function getPlugins(): Collection
    {
        if ($this->plugins) {
            return $this->plugins;
        }

        $plugins = new Collection();
        $installed = [];
        $resource = opendir($this->getPluginsDir());

        while ($filename = @readdir($resource)) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }

            $path = $this->getPluginsDir() . DIRECTORY_SEPARATOR . $filename;
            if (is_dir($path)) {
                $packageJsonPath = $path . DIRECTORY_SEPARATOR . 'package.json';
                if (file_exists($packageJsonPath)) {
                    $installed[$filename] = json_decode($this->filesystem->get($packageJsonPath), true);
                }
            }
        }
        closedir($resource);

        foreach ($installed as $dirname => $package) {
            $plugin = new Plugin($this->getPluginsDir() . DIRECTORY_SEPARATOR . $dirname, $package);
            $plugin->setDirname($dirname);
            $plugin->setInstalled(true);
            $plugin->setNameSpace(Arr::get($package, 'namespace'));
            $plugin->setVersion(Arr::get($package, 'version'));
            // $plugin->setEnabled($this->isEnabled($plugin->name));
            $plugin->setEnabled(true);

            if ($plugins->has($plugin->name)) {
                throw new \Exception("有重名插件：" . $plugin->name);
            }

            $plugins->put($plugin->name, $plugin);
        }

        $this->plugins = $plugins->sortBy(function ($plugin) {
            return $plugin->name;
        });

        return $this->plugins;
    }

    /**
     * The id's of the enabled plugins.
     *
     * @return array
     */
    public function getEnabled(): array
    {
        return (array)json_decode($this->setting->get('plugins_enabled'), true);
    }

    /**
     * Persist the currently enabled plugins.
     *
     * @param array $enabled
     */
    protected function setEnabled(array $enabled)
    {
        $enabled = array_values(array_unique($enabled));

        $this->setting->set('plugins_enabled', json_encode($enabled));
    }

    /**
     * Whether the plugin is enabled.
     *
     * @param string $pluginName
     * @return bool
     */
    public function isEnabled(string $pluginName): bool
    {
        return in_array($pluginName, $this->getEnabled());
    }


    /**
     * Get only enabled plugins.
     *
     * @return Collection
     * @throws \Exception
     */
    public function getEnabledPlugins(): Collection
    {
        return $this->getPlugins();//->only($this->getEnabled());
    }

    /**
     * Loads all bootstrap.php files of the enabled plugins.
     *
     * @return Collection
     * @throws \Exception
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

    /**
     * The plugins path.
     *
     * @return string
     */
    public function getPluginsDir(): string
    {
        return config('plugins.directory') ?: base_path('plugins');
    }
}
