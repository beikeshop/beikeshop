<?php
/**
 * Manager.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-29 19:38:30
 * @modified   2022-06-29 19:38:30
 */

namespace Beike\Plugin;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ZanySoft\Zip\Zip;

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
            $plugin     = new Plugin($pluginPath, $package);
            $status     = $plugin->getStatus();
            $plugin->setType(Arr::get($package, 'type'));
            $plugin->setDirname($dirname);
            $plugin->setName(Arr::get($package, 'name'));
            $plugin->setDescription(Arr::get($package, 'description'));
            $plugin->setInstalled(true);
            $plugin->setEnabled($status);
            $plugin->setVersion(Arr::get($package, 'version'));
            $plugin->setColumns();

            if ($plugins->has($plugin->code)) {
                continue;
            }

            $plugins->put($plugin->code, $plugin);
        }

        $this->plugins = $plugins->sortBy(function ($plugin) {
            return $plugin->code;
        });

        return $this->plugins;
    }

    /**
     * 获取已开启的插件
     *
     * @return Collection
     * @throws \Exception
     */
    public function getEnabledPlugins(): Collection
    {
        $allPlugins = $this->getPlugins();

        return $allPlugins->filter(function (Plugin $plugin) {
            return $plugin->getInstalled() && $plugin->getEnabled();
        });
    }

    /**
     * 获取已开启插件对应根目录下的启动文件 bootstrap.php
     *
     * @return Collection
     * @throws \Exception
     */
    public function getEnabledBootstraps(): Collection
    {
        $bootstraps = new Collection;

        foreach ($this->getEnabledPlugins() as $plugin) {
            if ($this->filesystem->exists($file = $plugin->getBootFile())) {
                $bootstraps->push([
                    'code' => $plugin->getDirName(),
                    'file' => $file,
                ]);
            }
        }

        return $bootstraps;
    }

    /**
     * 获取单个插件
     *
     * @throws \Exception
     */
    public function getPlugin($code): ?Plugin
    {
        $code    = Str::snake($code);
        $plugins = $this->getPlugins();

        return $plugins[$code] ?? null;
    }

    /**
     * 获取单个插件
     *
     * @throws \Exception
     */
    public function getPluginOrFail($code): ?Plugin
    {
        $plugin = $this->getPlugin($code);
        if (empty($plugin)) {
            throw new \Exception('无效的插件');
        }
        $plugin->checkLicenseValid();
        $plugin->handleLabel();

        return $plugin;
    }

    /**
     * Check plugin is active, include existed, installed and enabled
     *
     * @param $code
     * @return bool
     * @throws \Exception
     */
    public function checkActive($code): bool
    {
        $plugin    = $this->getPlugin($code);
        if (empty($plugin) || ! $plugin->getInstalled() || ! $plugin->getEnabled()) {
            return false;
        }

        return true;
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
        $resource  = opendir($this->getPluginsDir());
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

    /**
     * 上传插件并解压
     * @throws \Exception
     */
    public function import(UploadedFile $file)
    {
        $originalName = $file->getClientOriginalName();
        $destPath     = storage_path('upload');
        $newFilePath  = $destPath . '/' . $originalName;
        $file->move($destPath, $originalName);

        $zipFile = Zip::open($newFilePath);
        $zipFile->extract(base_path('plugins'));
    }
}
