<?php
/**
 * Manager.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-29 19:38:30
 * @modified   2022-06-29 19:38:30
 */

namespace Beike\Plugin;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class Manager
{
    protected $plugins;
    protected Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * 获取所有插件
     *
     * @return Collection
     * @throws \Exception
     */
    public function getPlugins(): Collection
    {
        if ($this->plugins) {
            return $this->plugins;
        }

        $existed = $this->getPluginsConfig();
        $plugins = new Collection();
        foreach ($existed as $dirname => $package) {
            $pluginPath = $this->getPluginsDir() . DIRECTORY_SEPARATOR . $dirname;
            $plugin = new Plugin($pluginPath, $package);
            $plugin->setDirname($dirname);
            $plugin->setName(Arr::get($package, 'name'));
            $plugin->setInstalled(true);
            $plugin->setEnabled(false);
            $plugin->setVersion(Arr::get($package, 'version'));
            $plugin->setColumns();

            if ($plugins->has($plugin->code)) {
                throw new \Exception("有重名插件：" . $plugin->code);
            }

            $plugins->put($plugin->code, $plugin);
        }

        $this->plugins = $plugins->sortBy(function ($plugin) {
            return $plugin->code;
        });

        return $this->plugins;
    }

    /**
     * 获取单个插件
     *
     * @throws \Exception
     */
    public function getPlugin($code)
    {
        $plugins = $this->getPlugins();
        return $plugins[$code] ?? null;
    }

    /**
     * 获取插件目录以及配置
     *
     * @return array
     * @throws FileNotFoundException
     */
    protected function getPluginsConfig(): array
    {
        $installed = [];
        $resource = opendir($this->getPluginsDir());
        while ($filename = @readdir($resource)) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }
            $path = $this->getPluginsDir() . DIRECTORY_SEPARATOR . $filename;
            if (is_dir($path)) {
                $packageJsonPath = $path . DIRECTORY_SEPARATOR . 'config.json';
                if (file_exists($packageJsonPath)) {
                    $installed[$filename] = json_decode($this->filesystem->get($packageJsonPath), true);
                }
            }
        }
        closedir($resource);
        return $installed;
    }

    /**
     * 插件根目录
     *
     * @return string
     */
    protected function getPluginsDir(): string
    {
        return config('plugins.directory') ?: base_path('plugins');
    }
}
