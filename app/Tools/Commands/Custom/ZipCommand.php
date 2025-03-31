<?php

namespace App\Tools\Commands\Custom;

use App\Tools\Traits\ModuleCommandTrait;
use Beike\Services\ZipArchiverService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use ZipArchive;

class ZipCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:zip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new zip for the specified plugin.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $plugin = $this->argument('plugin');

        if (! $plugin) {
            $plugin = $this->getModuleName();
        }

        try {
            $directory = plugin_path($plugin);
            if (! is_dir($directory)) {
                $this->components->error("Directory {$directory} does not exist");

                return 0;
            }

            $filename = $plugin . '.zip';
            $zipPath  = storage_path('zip/');

            if (! is_dir($zipPath)) {
                mkdir($zipPath, 0755, true);
            }

            $zipFile = $zipPath . $filename;
            if (file_exists($zipFile)) {
                unlink($zipFile);
            }

            $zipper = new ZipArchiverService($directory, $zipFile);
            $zipper->setIgnorePatterns([
                '*.log',           // 忽略所有日志文件
                '.git',          // 忽略.git目录下的所有内容
                'tmp',            // 忽略tmp目录
                '.idea',
                '.DS_Store',
            ]);

            if ($zipper->create()) {
                $this->components->info('Zip file generated success.');
            } else {
                $this->components->info('Zip file generated fail.');
            }

            //$this->zip($plugin,$directory);
        } catch (\Throwable $e) {
            $this->components->error('Zip file generated fail: ' . $e->getMessage());
        }

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    private function zip($plugin, $directory): void
    {
        $name = $plugin;
        // 创建一个新的 ZIP 实例
        $zip      = new ZipArchive();
        $filename = $name . '.zip';
        $zipPath  = storage_path('zip/');

        if (! is_dir($zipPath)) {
            mkdir($zipPath, 0755, true);
        }

        $zipFile = $zipPath . $filename;
        if (file_exists($zipFile)) {
            unlink($zipFile);
        }

        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception("can\'t open <$zipFile>\n");
        }

        $this->read_file_list($directory, $files, app('plugins')->config('zip.ignore_dir'));

        foreach ($files as $file) {
            $zip->addFile($file, str_replace($directory . '/', '', $file));
        }
        $zip->close();
    }

    /**
     * 获取文件列表(所有子目录文件)
     *
     * @param string $path       目录
     * @param array  $file_list  存放所有子文件的数组
     * @param array  $ignore_dir 需要忽略的目录或文件
     * @return array 数据格式的返回结果
     */
    public function read_file_list($path, &$file_list, $ignore_dir = [])
    {
        $path = rtrim($path, '/');
        if (is_dir($path)) {
            $handle = @opendir($path);
            if ($handle) {
                while (false !== ($dir = readdir($handle))) {
                    if ($dir != '.' && $dir != '..') {
                        if (! in_array($dir, $ignore_dir)) {
                            if (is_file($path . '/' . $dir)) {
                                $file_list[] = $path . '/' . $dir;
                            } elseif (is_dir($path . '/' . $dir)) {
                                $this->read_file_list($path . '/' . $dir, $file_list, $ignore_dir);
                            }
                        }
                    }
                }
                @closedir($handle);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
