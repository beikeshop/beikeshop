<?php

/**
 * FileManagerService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-07-12 15:12:48
 * @modified   2022-07-12 15:12:48
 */

namespace Beike\Admin\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService
{
    protected $fileBasePath = '';

    protected $basePath = '';

    protected string $publicCatalogPath = 'image/catalog';

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->fileBasePath = rtrim(catalog_path() . $this->basePath, '/\\');
        File::ensureDirectoryExists($this->fileBasePath);
    }

    /**
     * 获取某个目录下所有文件夹
     */
    public function getDirectories($baseFolder = '/'): array
    {
        $currentBasePath = $this->catalogPath($baseFolder);

        $directories = glob("{$currentBasePath}/*", GLOB_ONLYDIR);

        $result = [];
        foreach ($directories as $directory) {
            $baseName = basename($directory);
            $dirName  = $this->filesystemPathToCatalogPath($directory);
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
        $currentBasePath = $this->catalogPath($baseFolder);
        $files           = array_filter(glob($currentBasePath . '/*'), function ($file) {
            return is_file($file) && preg_match('/\.(jpg|jpeg|png|JPG|JPEG|mp4|MP4|gif|webp)$/', $file);
        });

        // 过滤文件
        if ($keyword) {
            $files = array_filter($files, function ($file) use ($keyword) {
                return str_contains(basename($file), $keyword);
            });
        }

        // 获取文件信息
        $fileData = array_map(function ($file) {
            return [
                'path'     => $file,
                'basename' => basename($file),
                'mtime'    => filemtime($file),
            ];
        }, $files);

        // 排序文件
        if ($sort == 'created') {
            usort($fileData, function ($a, $b) use ($order) {
                return $order == 'desc' ? $b['mtime'] <=> $a['mtime'] : $a['mtime'] <=> $b['mtime'];
            });
        } else {
            usort($fileData, function ($a, $b) use ($order) {
                return $order == 'desc' ? strcmp($b['basename'], $a['basename']) : strcmp($a['basename'], $b['basename']);
            });
        }

        // 分页
        $totalFiles = count($fileData);
        $start      = ($page - 1) * $perPage;
        $fileData   = array_slice($fileData, $start, $perPage);

        // 处理文件
        $images = array_map(function ($file) {
            return $this->handleImage($this->filesystemPathToCatalogPath($file['path']), $file['basename']);
        }, $fileData);

        return [
            'images'      => $images,
            'image_total' => $totalFiles,
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
        $folderPath = $this->catalogPath($folderName, false, false);
        if (is_dir($folderPath)) {
            throw new \Exception(trans('admin/file_manager.directory_already_exist'));
        }

        File::ensureDirectoryExists($folderPath);
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

        $sourceDirPath = $this->catalogPath($sourcePath, true);
        $destDirPath   = $this->catalogPath($destPath, true);
        if (! is_dir($sourceDirPath) || ! is_dir($destDirPath)) {
            throw new \Exception(trans('admin/file_manager.target_not_exist'));
        }

        if ($sourceDirPath === $destDirPath || str_starts_with($destDirPath . '/', $sourceDirPath . '/')) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }

        $folderName   = basename($sourceDirPath);
        $destFullPath = $destDirPath . '/' . $folderName;
        if (! File::exists($destFullPath)) {
            move_dir($sourceDirPath . '/', $destDirPath . '/');
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
        if (! is_array($images)) {
            throw new \Exception(trans('admin/file_manager.invalid_request_data'));
        }

        $destDirPath = $this->catalogPath($destPath, true);
        if (! is_dir($destDirPath)) {
            throw new \Exception(trans('admin/file_manager.target_not_exist'));
        }

        foreach ($images as $image) {
            if (! is_string($image)) {
                throw new \Exception(trans('admin/file_manager.invalid_path'));
            }

            $sourcePath = $this->catalogPath($image, true);
            if (! is_file($sourcePath)) {
                throw new \Exception(trans('admin/file_manager.target_not_exist'));
            }

            File::move($sourcePath, $destDirPath . '/' . basename($sourcePath));
        }
    }

    /**
     * @param $imagePath
     * @return string
     */
    /**
     * 验证路径，防止路径遍历攻击
     *
     * @param string $path 需要验证的路径
     * @return string 安全的路径
     * @throws \Exception 如果路径不安全则抛出异常
     */
    private function validatePath($path): string
    {
        return $this->normalizeCatalogPath($path);
    }

    public function zipFolder($imagePath): string
    {
        $realPath = $this->catalogPath($imagePath, true);

        // 检查目录是否存在
        if (! is_dir($realPath)) {
            throw new \Exception(trans('admin/file_manager.target_not_exist'));
        }

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
        $fullPath = $this->catalogPath($filePath);

        if (is_dir($fullPath)) {
            $files = glob($fullPath . '/*');
            if ($files) {
                throw new \Exception(trans('admin/file_manager.directory_not_empty'));
            }
            @rmdir($fullPath);
        } elseif (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    /**
     * 批量删除文件
     *
     * @param             $basePath
     * @param             $files
     * @throws \Exception
     */
    public function deleteFiles($basePath, $files)
    {
        if (empty($files)) {
            return;
        }

        $basePath = $this->normalizeCatalogPath($basePath);

        foreach ($files as $file) {
            // 验证文件名
            if (! $this->isValidFileName($file)) {
                throw new \Exception(trans('admin/file_manager.invalid_filename'));
            }

            $filePath = $this->catalogPath($basePath . '/' . $file);

            // 确保文件路径在允许的目录内
            if (file_exists($filePath)) {
                // 只删除文件，不删除目录
                if (is_file($filePath)) {
                    @unlink($filePath);
                }
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
        // 验证新文件名的安全性
        if (! $this->isValidFileName($newPath)) {
            throw new \Exception(trans('admin/file_manager.invalid_filename'));
        }

        // 验证新文件名（不允许路径分隔符）
        if (str_contains($newPath, '/') || str_contains($newPath, '\\') || str_contains($newPath, '..')) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }

        $fullOriginPath = $this->catalogPath($originPath, true);

        if (! is_dir($fullOriginPath) && ! file_exists($fullOriginPath)) {
            throw new \Exception(trans('admin/file_manager.target_not_exist'));
        }

        $originBase  = dirname($fullOriginPath);
        $newFullPath = $originBase . '/' . $newPath;

        $this->ensureCatalogPath($newFullPath);

        if ($fullOriginPath == $newFullPath) {
            return;
        }

        if (file_exists($newFullPath)) {
            throw new \Exception(trans('admin/file_manager.rename_failed'));
        }

        $result = @rename($fullOriginPath, $newFullPath);
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
        // 路径与文件名过滤
        $savePath = $this->normalizeCatalogPath($savePath);

        // 校验类型
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4'];
        $allowedMimeTypes  = [
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'video/mp4',
        ];
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType  = $file->getMimeType();

        if (! in_array($extension, $allowedExtensions) || ! in_array($mimeType, $allowedMimeTypes)) {
            throw new \Exception(trans('admin/file_manager.upload_type_fail'));
        }

        $originName = $this->getUniqueFileName($savePath, $originName);
        $filePath   = $file->storeAs(trim($this->basePath . $savePath, '/'), $originName, 'catalog');

        return [
            'url'  => asset('image/catalog/' . $filePath),
            'path' => 'image/catalog/' . $filePath,
        ];
    }

    public function sanitizePath($path): string
    {
        return $this->normalizeCatalogPath((string) $path);
    }

    private function normalizeCatalogPath($path, bool $allowRoot = true): string
    {
        if ($path === null) {
            $path = '';
        }
        if (! is_string($path)) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }
        if (str_contains($path, "\0")) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }

        $path    = trim(str_replace('\\', '/', $path));
        $trimmed = ltrim($path, '/');
        if ($trimmed === $this->publicCatalogPath) {
            $trimmed = '';
        } elseif (str_starts_with($trimmed, $this->publicCatalogPath . '/')) {
            $trimmed = substr($trimmed, strlen($this->publicCatalogPath) + 1);
        }

        if ($trimmed !== '' && preg_match('#[<>:"|?*\x00-\x1f]#', $trimmed)) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }

        $parts = [];
        foreach (explode('/', $trimmed) as $part) {
            if ($part === '') {
                continue;
            }
            if ($part === '.' || $part === '..') {
                throw new \Exception(trans('admin/file_manager.invalid_path'));
            }
            $parts[] = $part;
        }

        if (empty($parts)) {
            if (! $allowRoot) {
                throw new \Exception(trans('admin/file_manager.invalid_path'));
            }

            return '/';
        }

        return '/' . implode('/', $parts);
    }

    private function catalogPath($path, bool $mustExist = false, bool $allowRoot = true): string
    {
        $path     = $this->normalizeCatalogPath($path, $allowRoot);
        $fullPath = rtrim($this->fileBasePath, '/') . ($path === '/' ? '' : $path);

        return $this->ensureCatalogPath($fullPath, $mustExist);
    }

    private function ensureCatalogPath(string $path, bool $mustExist = false): string
    {
        $basePath = realpath($this->fileBasePath);
        if (! $basePath) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }

        if (file_exists($path)) {
            $realPath = realpath($path);
            if (! $realPath || ! $this->isPathInside($realPath, $basePath)) {
                throw new \Exception(trans('admin/file_manager.invalid_path'));
            }

            return rtrim($realPath, '/');
        }

        if ($mustExist) {
            throw new \Exception(trans('admin/file_manager.target_not_exist'));
        }

        $parent = dirname($path);
        while (! file_exists($parent) && $parent !== dirname($parent)) {
            $parent = dirname($parent);
        }

        $realParent = realpath($parent);
        if (! $realParent || ! $this->isPathInside($realParent, $basePath)) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }

        return rtrim($path, '/');
    }

    private function isPathInside(string $path, string $basePath): bool
    {
        $path     = $this->normalizeFilesystemPath($path);
        $basePath = $this->normalizeFilesystemPath($basePath);

        return $path === $basePath || str_starts_with($path, $basePath . '/');
    }

    private function filesystemPathToCatalogPath(string $path): string
    {
        $path     = $this->normalizeFilesystemPath($path);
        $basePath = $this->normalizeFilesystemPath($this->fileBasePath);

        if ($path === $basePath) {
            return '/';
        }

        if (! str_starts_with($path, $basePath . '/')) {
            throw new \Exception(trans('admin/file_manager.invalid_path'));
        }

        return substr($path, strlen($basePath));
    }

    private function normalizeFilesystemPath(string $path): string
    {
        return rtrim(str_replace('\\', '/', $path), '/');
    }

    /**
     * 验证文件名是否安全
     *
     * @param string $fileName
     * @return bool
     */
    private function isValidFileName(string $fileName): bool
    {
        // 检查文件名长度
        if (strlen($fileName) > 255 || empty(trim($fileName))) {
            return false;
        }

        // 检查危险字符
        if (preg_match('#[<>:"|?*\x00-\x1f]#', $fileName)) {
            return false;
        }

        // 检查路径遍历模式
        if (str_contains($fileName, '..') || str_contains($fileName, '/') || str_contains($fileName, '\\')) {
            return false;
        }

        // 检查保留文件名 (Windows)
        $reservedNames  = ['CON', 'PRN', 'AUX', 'NUL', 'COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9', 'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9'];
        $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        if (in_array(strtoupper($nameWithoutExt), $reservedNames)) {
            return false;
        }

        // 如果是文件（有扩展名），验证扩展名
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (! empty($extension)) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
            if (! in_array(strtolower($extension), $allowedExtensions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @throws BindingResolutionException
     */
    public function getUniqueFileName($savePath, $originName): string
    {
        if (is_file($this->catalogPath($savePath . '/' . $originName))) {
            $originName     = $this->getNewFileName($originName);
            $originName     = $this->getUniqueFileName($savePath, $originName);
        }

        return $originName;
    }

    public function getNewFileName($originName): string
    {
        $originNameInfo = pathinfo($originName);
        $uuid = Str::uuid();

        return $originNameInfo['filename'] . '_' . $uuid . '.' . $originNameInfo['extension'];
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
     * @throws BindingResolutionException
     */
    private function hasSubFolders($folderPath): bool
    {
        $path     = $this->catalogPath($folderPath);
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
        $path     = "image/catalog{$filePath}";
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
