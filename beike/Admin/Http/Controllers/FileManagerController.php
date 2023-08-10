<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\UploadRequest;
use Beike\Admin\Services\FileManagerService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileManagerController extends Controller
{
    /**
     * 获取文件夹和文件列表
     * @return mixed
     * @throws Exception
     */
    public function index(): mixed
    {
        $data = (new FileManagerService)->getDirectories();
        $data = hook_filter('admin.file_manager.index.data', $data);

        return view('admin::pages.file_manager.index', ['directories' => $data]);
    }

    /**
     * 获取某个文件夹下面的文件列表
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getFiles(Request $request): JsonResponse
    {
        $baseFolder = $request->get('base_folder');
        $sort       = $request->get('sort', 'created');
        $order      = $request->get('order', 'desc');
        $page       = (int) $request->get('page');
        $perPage    = (int) $request->get('per_page');

        $data = (new FileManagerService)->getFiles($baseFolder, $sort, $order, $page, $perPage);

        return hook_filter('admin.file_manager.files.data', $data);
    }

    /**
     * 获取文件夹列表
     * @param Request $request
     * @return JsonResponse
     */
    public function getDirectories(Request $request): JsonResponse
    {
        $baseFolder = $request->get('base_folder');

        $data = (new FileManagerService)->getDirectories($baseFolder);

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
            (new FileManagerService)->createDirectory($folderName);

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
            (new FileManagerService)->updateName($originPath, $newPath);

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
            (new FileManagerService)->deleteFiles($basePath, $files);

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
            (new FileManagerService)->deleteDirectoryOrFile($folderName);

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
            (new FileManagerService)->moveDirectory($sourcePath, $destPath);

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
            $images     = $request->get('images');
            $destPath   = $request->get('dest_path');
            (new FileManagerService)->moveFiles($images, $destPath);

            return json_success(trans('common.updated_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * 上传文件
     * POST      /admin/file_manager/upload
     *
     * @param UploadRequest $request
     * @return JsonResponse
     */
    public function uploadFiles(UploadRequest $request): JsonResponse
    {
        $file     = $request->file('file');
        $savePath = $request->get('path');

        $originName = $file->getClientOriginalName();
        $filePath   = (new FileManagerService)->uploadFile($file, $savePath, $originName);

        return [
            'name' => $originName,
            'url'  => asset('catalog/' . $filePath),
        ];
    }
}
