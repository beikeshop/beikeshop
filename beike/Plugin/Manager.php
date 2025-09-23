<?php
/**
 * Manager.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
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
use Beike\Facades\BeikeHttp\Facade\Http;
use Beike\Admin\Services\MarketingService;

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
            if (!app()->runningInConsole())
            {
                return $this->plugins;
            }
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
            $plugin->setCanUpdate(false);
            if ($this->canUpdate($plugin->code)) {
                $plugin->setCanUpdate(true);
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

        $apiEndPoint = "/v1/plugins/{$code}";

        $content = Http::sendGet($apiEndPoint);

        if (!in_array($code, config('app.free_plugin_codes')) && ($content['data']['price'] ?? 0) > 0) {
            $plugin->checkLicenseValid();
        }

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
        // 验证文件类型
        $this->validatePluginFile($file);

        $originalName = $file->getClientOriginalName();
        $destPath     = storage_path('upload');
        $safeFileName = $this->generateSafeFileName($originalName);
        $newFilePath  = $destPath . '/' . $safeFileName;

        // 确保目录存在
        if (!is_dir($destPath)) {
            mkdir($destPath, 0755, true);
        }

        $file->move($destPath, $safeFileName);

        try {
            // 安全解压ZIP文件
            $this->safeExtractZip($newFilePath, base_path('plugins'));
        } finally {
            // 清理临时文件
            if (file_exists($newFilePath)) {
                @unlink($newFilePath);
            }
        }
    }

    /**
     * 验证插件文件
     */
    private function validatePluginFile(UploadedFile $file): void
    {
        // 检查文件大小 (最大50MB)
        if ($file->getSize() > 50 * 1024 * 1024) {
            throw new \Exception('Plugin file too large. Maximum size is 50MB.');
        }

        // 检查文件扩展名
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension !== 'zip') {
            throw new \Exception('Only ZIP files are allowed for plugin upload.');
        }

        // 检查MIME类型
        $mimeType = $file->getMimeType();
        $allowedMimeTypes = ['application/zip', 'application/x-zip-compressed'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new \Exception('Invalid file type. Only ZIP files are allowed.');
        }
    }

    /**
     * 生成安全的文件名
     */
    private function generateSafeFileName(string $originalName): string
    {
        $pathInfo = pathinfo($originalName);
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', $pathInfo['filename']);
        $extension = strtolower($pathInfo['extension']);

        return $baseName . '_' . time() . '.' . $extension;
    }

    /**
     * 安全解压ZIP文件
     */
    private function safeExtractZip(string $zipPath, string $extractPath): void
    {
        $zip = new \ZipArchive();
        $result = $zip->open($zipPath);

        if ($result !== TRUE) {
            throw new \Exception('Cannot open ZIP file: ' . $result);
        }

        // 检查ZIP文件内容
        $this->validateZipContents($zip, $extractPath);

        // 安全解压
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // 验证文件路径
            if (!$this->isValidZipPath($filename, $extractPath)) {
                $zip->close();
                throw new \Exception('Invalid file path in ZIP: ' . $filename);
            }

            // 解压单个文件
            $zip->extractTo($extractPath, $filename);
        }

        $zip->close();
    }

    /**
     * 验证ZIP文件内容
     */
    private function validateZipContents(\ZipArchive $zip, string $extractPath): void
    {
        $totalSize = 0;
        $fileCount = 0;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $filename = $stat['name'];

            // 检查文件数量限制
            $fileCount++;
            if ($fileCount > 1000) {
                throw new \Exception('ZIP file contains too many files (max 1000).');
            }

            // 检查解压后总大小
            $totalSize += $stat['size'];
            if ($totalSize > 100 * 1024 * 1024) { // 100MB
                throw new \Exception('ZIP file content too large when extracted.');
            }

            // 检查压缩比，防止ZIP炸弹
            if ($stat['size'] > 0 && $stat['comp_size'] > 0) {
                $ratio = $stat['size'] / $stat['comp_size'];
                if ($ratio > 100) { // 压缩比超过100:1
                    throw new \Exception('Suspicious compression ratio detected.');
                }
            }
        }
    }

    /**
     * 验证ZIP中的文件路径是否安全
     */
    private function isValidZipPath(string $filename, string $extractPath): bool
    {
        // 检查路径遍历
        if (str_contains($filename, '..') || str_contains($filename, '\\')) {
            return false;
        }

        // 检查绝对路径
        if (str_starts_with($filename, '/')) {
            return false;
        }

        // 检查解析后的路径是否在允许的目录内
        $fullPath = realpath($extractPath) . '/' . $filename;
        $realExtractPath = realpath($extractPath);

        return str_starts_with($fullPath, $realExtractPath);
    }

    private function canUpdate(mixed $code): bool
    {
        if (in_array($code, config('app.free_plugin_codes'))) {
            return false;
        }

        $plugin = \Beike\Models\Plugin::query()->where('code', $code)->count();
        if (!$plugin) {
            return false;
        }

        return true;
    }

}
