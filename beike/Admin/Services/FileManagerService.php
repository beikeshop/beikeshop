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
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService
{
    protected $fileBasePath = '';

    protected $basePath = '';

    public function __construct()
    {
        $this->fileBasePath = public_path('catalog') . $this->basePath;
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
            $dirName  = str_replace($this->fileBasePath, '', $directory);
            if (is_dir($directory)) {
                $item           = $this->handleFolder($dirName, $baseName);
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
     * @param     $baseFolder
     * @param     $keyword
     * @param     $sort
     * @param     $order
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws \Exception
     */
    public function getFiles($baseFolder, $keyword, $sort, $order, int $page = 1, int $perPage = 20): array
    {
        $currentBasePath = rtrim($this->fileBasePath . $baseFolder, '/');
        $files           = glob($currentBasePath . '/*');

        if ($sort == 'created') {
            if ($order == 'desc') {
                usort($files, function ($a, $b) {
                    return filemtime($a) - filemtime($b) < 0;
                });
            } else {
                usort($files, function ($a, $b) {
                    return filemtime($a) - filemtime($b) >= 0;
                });
            }
        } else {
            natcasesort($files);
            if ($order == 'desc') {
                $files = array_reverse($files);
            }
        }

        $images = [];
        foreach ($files as $file) {
            $baseName = basename($file);
            if ($baseName == 'index.html') {
                continue;
            }
            if ($keyword && ! str_contains($baseName, $keyword)) {
                continue;
            }
            $fileName = str_replace(public_path('catalog'), '', $file);
            if (is_file($file)) {
                $images[] = $this->handleImage($fileName, $baseName);
            }
        }

        $page            = $page > 0 ? $page : 1;
        $imageCollection = collect($images);

        $currentImages = $imageCollection->forPage($page, $perPage);

        return [
            'images'      => $currentImages->values(),
            'image_total' => $imageCollection->count(),
            'image_page'  => $page,
        ];
    }

    /**
     * 创建目录
     * @param             $folderName
     * @throws \Exception
     */
    public function createDirectory($folderName)
    {
        $catalogFolderPath = "catalog{$this->basePath}/{$folderName}";
        $folderPath        = public_path($catalogFolderPath);
        if (is_dir($folderPath)) {
            throw new \Exception(trans('admin/file_manager.directory_already_exist'));
        }
        create_directories($catalogFolderPath);
    }

    /**
     * 移动文件夹
     *
     * @param             $sourcePath
     * @param             $destPath
     * @throws \Exception
     */
    public function moveDirectory($sourcePath, $destPath)
    {
        if (empty($sourcePath)) {
            throw new \Exception(trans('admin/file_manager.empty_source_path'));
        }
        if (empty($destPath)) {
            throw new \Exception(trans('admin/file_manager.empty_dest_path'));
        }

        $folderName    = basename($sourcePath);
        $sourceDirPath = public_path("catalog{$this->basePath}{$sourcePath}/");

        if ($destPath != '/') {
            $destDirPath = public_path("catalog{$this->basePath}{$destPath}/");
        } else {
            $destDirPath = public_path("catalog{$this->basePath}{$destPath}");
        }

        $destFullPath = public_path("catalog{$this->basePath}{$destPath}/{$folderName}");
        if (! File::exists($destFullPath)) {
            move_dir($sourceDirPath, $destDirPath);
        } else {
            throw new \Exception(trans('admin/file_manager.target_dir_exist'));
        }
    }

    /**
     * 批量移动图片文件
     *
     * @param $images
     * @param $destPath
     */
    public function moveFiles($images, $destPath)
    {
        if ($destPath != '/') {
            $destDirPath = public_path("catalog{$this->basePath}{$destPath}/");
        } else {
            $destDirPath = public_path("catalog{$this->basePath}{$destPath}");
        }

        foreach ($images as $image) {
            $sourceDirPath = public_path($image);
            File::move($sourceDirPath, $destDirPath . basename($sourceDirPath));
        }
    }

    /**
     * @param $imagePath
     * @return string
     */
    public function zipFolder($imagePath): string
    {
        $realPath = $this->fileBasePath . $imagePath;
        $dirName  = basename($realPath);
        $zipName  = $dirName . '-' . date('Ymd') . '.zip';
        $zipPath  = public_path("{$zipName}");
        zip_folder($realPath, $zipPath);

        return $zipPath;
    }

    /**
     * 删除文件或文件夹
     *
     * @param             $filePath
     * @throws \Exception
     */
    public function deleteDirectoryOrFile($filePath)
    {
        $filePath = public_path("catalog{$this->basePath}/{$filePath}");
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
            $filePath = public_path("catalog{$this->basePath}/{$basePath}/$file");
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
    }

    /**
     * 修改文件夹或者文件名称
     *
     * @param             $originPath
     * @param             $newPath
     * @throws \Exception
     */
    public function updateName($originPath, $newPath)
    {
        $originPath = public_path("catalog{$this->basePath}/{$originPath}");
        if (! is_dir($originPath) && ! file_exists($originPath)) {
            throw new \Exception(trans('admin/file_manager.target_not_exist'));
        }
        $originBase = dirname($originPath);
        $newPath    = $originBase . '/' . $newPath;
        if ($originPath == $newPath) {
            return;
        }
        if (file_exists($newPath)) {
            throw new \Exception(trans('admin/file_manager.rename_failed'));
        }
        $result = @rename($originPath, $newPath);
        if (! $result) {
            throw new \Exception(trans('admin/file_manager.rename_failed'));
        }
    }

    /**
     * 上传文件
     *
     * @param $file
     * @param $savePath
     * @param $originName
     * @return mixed
     */
    public function uploadFile(UploadedFile $file, $savePath, $originName): mixed
    {
        $originName = $this->getUniqueFileName($savePath, $originName);
        $filePath   = $file->storeAs($this->basePath . $savePath, $originName, 'catalog');

        return asset('catalog/' . $filePath);
    }

    public function getUniqueFileName($savePath, $originName): string
    {
        if (is_file(public_path('catalog' . $this->basePath . $savePath . '/' . $originName))) {
            $originName     = $this->getNewFileName($originName);
            $originName     = $this->getUniqueFileName($savePath, $originName);
        }

        return $originName;
    }

    public function getNewFileName($originName): string
    {
        $originNameInfo = pathinfo($originName);

        $fileName = $originNameInfo['filename'];
        $index    = 1;

        $hyphenPos    = mb_strrpos($fileName, '-');
        $indexPending = mb_substr($fileName, $hyphenPos + 1);
        if (is_numeric($indexPending)) {
            $fileName = mb_substr($fileName, 0, $hyphenPos);
            $index    = $indexPending + 1;
        }

        $originName     = $fileName . '-' . $index . '.' . $originNameInfo['extension'];

        return $originName;
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
        $path     = public_path("catalog{$this->basePath}/{$folderPath}");
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
        $path     = "catalog{$filePath}";
        $realPath = str_replace($this->fileBasePath . $this->basePath, $this->fileBasePath, $this->fileBasePath . $filePath);

        $mime = '';
        if (file_exists($realPath)) {
            $mime = mime_content_type($realPath);
        }

        return [
            'path'       => '/' . $path,
            'name'       => $baseName,
            'origin_url' => image_origin($path),
            'url'        => image_resize($path),
            'mime'       => $mime,
            'selected'   => false,
        ];
    }
}
