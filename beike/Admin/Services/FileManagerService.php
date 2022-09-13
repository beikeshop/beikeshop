<?php
/**
 * FileManagerService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-12 15:12:48
 * @modified   2022-07-12 15:12:48
 */

namespace Beike\Admin\Services;

use Illuminate\Support\Facades\File;

class FileManagerService
{
    private $fileBasePath = '';


    public function __construct()
    {
        $this->fileBasePath = public_path('catalog');
    }


    /**
     * 获取某个目录下所有文件夹
     */
    public function getDirectories($baseFolder = '/'): array
    {
        $currentBasePath = rtrim($this->fileBasePath . $baseFolder, '/');
        $directories = glob("{$currentBasePath}/*", GLOB_ONLYDIR);

        $result = [];
        foreach ($directories as $directory) {
            $baseName = basename($directory);
            $dirName = str_replace($this->fileBasePath, '', $directory);
            if (is_dir($directory)) {
                $item = $this->handleFolder($dirName, $baseName);
                $subDirectories = $this->getDirectories($dirName);
                if ($subDirectories) {
                    $item['children'] = $subDirectories;
                }
                $result[] = $item;
            }
        }
        return $result;
    }


    /**
     * 获取某个目录下的文件和文件夹
     *
     * @param $baseFolder
     * @param int $page
     * @return array
     * @throws \Exception
     */
    public function getFiles($baseFolder, int $page = 1, int $perPage = 20): array
    {
        $currentBasePath = rtrim($this->fileBasePath . $baseFolder, '/');
        $files = glob($currentBasePath . '/*');

        $images = [];
        foreach ($files as $file) {
            $baseName = basename($file);
            if ($baseName == 'index.html') {
                continue;
            }
            $fileName = str_replace($this->fileBasePath, '', $file);
            if (is_file($file)) {
                $images[] = $this->handleImage($fileName, $baseName);
            }
        }

        $page = $page > 0 ? $page : 1;
        // $perPage = $perPage;
        $imageCollection = collect($images);
        $data = [
            'images' => $imageCollection->forPage($page, $perPage)->values()->toArray(),
            'image_total' => $imageCollection->count(),
            'image_page' => $page,
        ];
        return $data;
    }


    /**
     * 创建目录
     * @param $folderName
     * @throws \Exception
     */
    public function createDirectory($folderName)
    {
        $catalogFolderPath = "catalog/{$folderName}";
        $folderPath = public_path($catalogFolderPath);
        if (is_dir($folderPath)) {
            throw new \Exception(trans('admin/file_manager.directory_already_exist'));
        }
        create_directories($catalogFolderPath);
    }


    /**
     * 删除文件或文件夹
     *
     * @param $filePath
     * @throws \Exception
     */
    public function deleteDirectoryOrFile($filePath)
    {
        $filePath = public_path("catalog/{$filePath}");
        if (is_dir($filePath)) {
            $files = glob($filePath . '/*');
            if ($files) {
                throw new \Exception(trans('admin/file_manager.directory_not_empty'));
            }
            @rmdir($filePath);
        } elseif (file_exists($filePath)) {
            @unlink($filePath);
        }
    }


    /**
     * 批量删除文件
     *
     * @param $basePath
     * @param $files
     */
    public function deleteFiles($basePath, $files)
    {
        if (empty($basePath) && empty($files)) {
            return;
        }
        foreach ($files as $file) {
            $filePath = public_path("catalog/{$basePath}/$file");
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }


    /**
     * 修改文件夹或者文件名称
     *
     * @param $originPath
     * @param $newPath
     * @throws \Exception
     */
    public function updateName($originPath, $newPath)
    {
        $originPath = public_path("catalog/{$originPath}");
        if (!is_dir($originPath) && !file_exists($originPath)) {
            throw new \Exception(trans('admin/file_manager.target_not_exist'));
        }
        $originBase = dirname($originPath);
        $newPath = $originBase . '/' . $newPath;
        if ($originPath == $newPath) {
            return;
        }
        @rename($originPath, $newPath);
    }


    /**
     * 处理文件夹
     *
     * @param $folderPath
     * @param $baseName
     * @return array
     */
    private function handleFolder($folderPath, $baseName): array
    {
        return [
            'path' => $folderPath,
            'name' => $baseName
        ];
    }


    /**
     * 检测是否含有子文件夹
     *
     * @param $folderPath
     * @return bool
     */
    private function hasSubFolders($folderPath): bool
    {
        $path = public_path("catalog/{$folderPath}");
        $subFiles = glob($path . '/*');
        foreach ($subFiles as $subFile) {
            if (is_dir($subFile)) {
                return true;
            }
        }
        return false;
    }


    /**
     * 处理文件
     *
     * @param $filePath
     * @param $baseName
     * @return array
     * @throws \Exception
     */
    private function handleImage($filePath, $baseName): array
    {
        return [
            'path' => 'catalog' . $filePath,
            'name' => $baseName,
            'url' => image_resize("catalog{$filePath}"),
            'origin_url' => image_origin("catalog{$filePath}"),
            'selected' => false,
        ];
    }
}
