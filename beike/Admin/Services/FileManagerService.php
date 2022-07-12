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
     * @return array
     */
    public function getFiles($baseFolder): array
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

        $data = [
            'folders' => $folders,
            'images' => $images
        ];
        return $data;
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
            'selected' => false,
        ];
    }


    /**
     * 处理文件
     *
     * @param $filePath
     * @param $baseName
     * @return array
     */
    private function handleImage($filePath, $baseName): array
    {
        return [
            'path' => $filePath,
            'name' => $baseName,
            'url' => image_resize("catalog{$filePath}", 100, 100),
            'selected' => false,
        ];
    }
}
