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

        return view('admin::pages.file_manager.index', ['directories' => $data]);
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
            $originPath = $request->get('origin_name');
            $newPath    = $request->get('new_name');
            $this->fileManagerService->updateName($originPath, $newPath);

            return json_success(trans('common.updated_success'));
        } catch (Exception $e) {
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
            $basePath    = $requestData['path']  ?? '';
            $files       = $requestData['files'] ?? [];
            $this->fileManagerService->deleteFiles($basePath, $files);

            return json_success(trans('common.deleted_success'));
        } catch (Exception $e) {
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
            $imagePath = $request->get('path');
            $zipFile   = $this->fileManagerService->zipFolder($imagePath);

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . basename($zipFile) . '"');
            header('Content-Length: ' . filesize($zipFile));
            readfile($zipFile);
            unlink($zipFile);

        } catch (Exception $e) {
            echo $e->getMessage();
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
}
