<?php
/**
 * FileManagerService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
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
     * 获取某个目录下的文件和文件夹
     *
     * @param $baseFolder
     * @param int $page
     * @return array
     * @throws \Exception
     */
    public function getFiles($baseFolder, int $page = 1): array
    {
        $fileBasePath = $this->fileBasePath;
        if ($baseFolder) {
            $currentBasePath = $fileBasePath . $baseFolder;
        } else {
            $currentBasePath = $fileBasePath;
        }
        $files = glob($currentBasePath . '/*');

        $folders = $images = [];
        foreach ($files as $file) {
            $baseName = basename($file);
            if ($baseName == 'index.html') {
                continue;
            }

            $fileName = str_replace($fileBasePath, '', $file);
            if (is_dir($file)) {
                $folders[] = $this->handleFolder($fileName, $baseName);
            } elseif (is_file($file)) {
                $images[] = $this->handleImage($fileName, $baseName);
            }
        }

        $page = $page > 0 ? $page : 1;
        $perPage = 20;

        $imageCollection = collect($images);
        $data = [
            'folders' => $folders,
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
            throw new \Exception("目录已存在");
        }
        create_directories($catalogFolderPath);
    }


    /**
     * 批量删除文件或文件夹
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
                throw new \Exception("该目录不为空");
            }
            @rmdir($filePath);
        } elseif (file_exists($filePath)) {
            @unlink($filePath);
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
        if (!is_dir($originPath) || !file_exists($originPath)) {
            throw new \Exception('原始文件或者文件夹无效');
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
            'name' => $baseName,
            'leaf' => !$this->hasSubFolders($folderPath),
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
            'path' => $filePath,
            'name' => $baseName,
            'url' => image_resize("catalog{$filePath}"),
            'origin_url' => image_origin("catalog{$filePath}"),
            'selected' => false,
        ];
    }
}
