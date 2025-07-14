<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\UploadRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    protected $fileManagerService;

    public function __construct()
    {
        $class = hook_filter('controller.file_manager.construct', \Beike\Admin\Services\FileManagerService::class);

        $this->fileManagerService = new $class();
    }

    /**
     * 获取文件夹和文件列表
     * @return mixed
     * @throws Exception
     */
    public function index(): mixed
    {
        $data = $this->fileManagerService->getDirectories();
        $data = hook_filter('admin.file_manager.index.data', $data);
        $uploadMaxFilesize = ini_get('upload_max_filesize');
        $maxSizeBytes = $this->convertToBytes($uploadMaxFilesize);

        return view('admin::pages.file_manager.index', ['directories' => $data, 'maxSizeBytes' => $maxSizeBytes]);
    }

    /**
     * 获取某个文件夹下面的文件列表
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getFiles(Request $request): mixed
    {
        $baseFolder = $request->get('base_folder');
        $keyword    = $request->get('keyword');
        $sort       = $request->get('sort', 'created');
        $order      = $request->get('order', 'desc');
        $page       = (int) $request->get('page');
        $perPage    = (int) $request->get('per_page');

        $data = $this->fileManagerService->getFiles($baseFolder, $keyword, $sort, $order, $page, $perPage);

        return hook_filter('admin.file_manager.files.data', $data);
    }

    /**
     * 获取文件夹列表
     * @param Request $request
     * @return mixed
     */
    public function getDirectories(Request $request): mixed
    {
        $baseFolder = $request->get('base_folder');

        $data = $this->fileManagerService->getDirectories($baseFolder);

        return hook_filter('admin.file_manager.directories.data', $data);
    }

    /**
     * 创建文件夹
     * POST      /admin/file_manager
     * @throws Exception
     */
    public function createDirectory(Request $request): JsonResponse
    {
        try {
            $folderName = $request->get('name');
            $this->fileManagerService->createDirectory($folderName);

            return json_success(trans('common.created_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 文件或文件夹改名
     * PUT       /admin/file_manager/rename
     * @throws Exception
     */
    public function rename(Request $request): JsonResponse
    {
        try {
            // 验证请求参数
            $request->validate([
                'origin_name' => 'required|string|max:255',
                'new_name' => 'required|string|max:255'
            ]);

            $originPath = $request->get('origin_name');
            $newName = $request->get('new_name');

            // 基本的路径验证
            if (str_contains($originPath, '..') || str_contains($newName, '..')) {
                throw new \Exception(trans('admin/file_manager.invalid_path'));
            }

            // 验证新文件名的安全性
            if (!$this->isValidFileName($newName)) {
                throw new \Exception(trans('admin/file_manager.invalid_filename'));
            }

            $this->fileManagerService->updateName($originPath, $newName);

            // 记录重命名操作
            \Log::info('File renamed', [
                'origin_name' => $originPath,
                'new_name' => $newName,
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            return json_success(trans('common.updated_success'));
        } catch (Exception $e) {
            // 记录错误日志
            \Log::error('File rename error: ' . $e->getMessage(), [
                'origin_name' => $request->get('origin_name'),
                'new_name' => $request->get('new_name'),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            return json_fail($e->getMessage());
        }
    }

    /**
     * 删除文件或文件夹
     * DELETE    /admin/file_manager/files  {"path":"/xx/yy", "files":["1.jpg", "2.png"]}
     * @throws Exception
     */
    public function destroyFiles(Request $request): JsonResponse
    {
        try {
            $requestData = json_decode($request->getContent(), true);

            // 验证请求数据
            if (!is_array($requestData)) {
                throw new \Exception(trans('admin/file_manager.invalid_request_data'));
            }

            $basePath = $requestData['path'] ?? '';
            $files = $requestData['files'] ?? [];

            // 验证基础路径
            if (!is_string($basePath) || str_contains($basePath, '..')) {
                throw new \Exception(trans('admin/file_manager.invalid_path'));
            }

            // 验证文件列表
            if (!is_array($files)) {
                throw new \Exception(trans('admin/file_manager.invalid_files_parameter'));
            }

            // 验证每个文件名
            foreach ($files as $file) {
                if (!is_string($file) || !$this->isValidFileName($file)) {
                    throw new \Exception(trans('admin/file_manager.invalid_filename'));
                }

                // 检查路径遍历
                if (str_contains($file, '..') || str_contains($file, '/') || str_contains($file, '\\')) {
                    throw new \Exception(trans('admin/file_manager.invalid_filename'));
                }
            }

            $this->fileManagerService->deleteFiles($basePath, $files);

            // 记录删除操作
            \Log::info('Files deleted', [
                'base_path' => $basePath,
                'files' => $files,
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            return json_success(trans('common.deleted_success'));
        } catch (Exception $e) {
            // 记录错误日志
            \Log::error('File deletion error: ' . $e->getMessage(), [
                'request_data' => $request->getContent(),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            return json_fail($e->getMessage());
        }
    }

    /**
     * 删除文件夹
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function destroyDirectories(Request $request): JsonResponse
    {
        try {
            $folderName = $request->get('name');
            $this->fileManagerService->deleteDirectoryOrFile($folderName);

            return json_success(trans('common.deleted_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 移动目录
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function moveDirectories(Request $request): JsonResponse
    {
        try {
            $sourcePath = $request->get('source_path');
            $destPath   = $request->get('dest_path');
            $this->fileManagerService->moveDirectory($sourcePath, $destPath);

            return json_success(trans('common.updated_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 移动多个图片文件
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function moveFiles(Request $request): JsonResponse
    {
        try {
            $images   = $request->get('images');
            $destPath = $request->get('dest_path');
            $this->fileManagerService->moveFiles($images, $destPath);

            return json_success(trans('common.updated_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 压缩文件夹下载ZIP
     *
     * @param Request $request
     */
    public function exportZip(Request $request)
    {
        try {
            // 验证请求参数
            $request->validate([
                'path' => 'required|string|max:255'
            ]);

            $imagePath = $request->get('path');

            // 基本的路径验证
            if (empty($imagePath) || str_contains($imagePath, '..')) {
                throw new \Exception(trans('admin/file_manager.invalid_path'));
            }

            $zipFile = $this->fileManagerService->zipFolder($imagePath);

            // 安全的文件名处理
            $safeFileName = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', basename($zipFile));

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $safeFileName . '"');
            header('Content-Length: ' . filesize($zipFile));
            readfile($zipFile);
            unlink($zipFile);

        } catch (Exception $e) {
            // 记录错误日志
            \Log::error('File export error: ' . $e->getMessage(), [
                'path' => $request->get('path'),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);

            abort(400, $e->getMessage());
        }
    }

    /**
     * 上传文件
     * POST      /admin/file_manager/upload
     *
     * @param UploadRequest $request
     * @return array
     */
    public function uploadFiles(UploadRequest $request): array
    {
        $file     = $request->file('file');
        $savePath = $request->get('path');

        $originName = $file->getClientOriginalName();
        $fileUrl    = $this->fileManagerService->uploadFile($file, $savePath, $originName);

        return [
            'name' => $originName,
            'url'  => $fileUrl,
        ];
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
        $reservedNames = ['CON', 'PRN', 'AUX', 'NUL', 'COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9', 'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9'];
        $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        if (in_array(strtoupper($nameWithoutExt), $reservedNames)) {
            return false;
        }

        // 如果是文件（有扩展名），验证扩展名
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!empty($extension)) {
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return false;
            }
        }

        return true;
    }

    private function convertToBytes($sizeStr) {
        $sizeStr = trim($sizeStr);
        $last  = strtolower($sizeStr[strlen($sizeStr) - 1]);
        $val   = substr($sizeStr, 0, -1);
        switch ($last) {
            case 'g':
                $val *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $val *= 1024 * 1024;
                break;
            case 'k':
                $val *= 1024;
                break;
        }
        return $val;
    }
}
