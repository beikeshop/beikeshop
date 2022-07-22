<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\UploadRequest;
use Beike\Admin\Services\FileManagerService;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    /**
     * 获取文件夹和文件列表
     * @return mixed
     * @throws \Exception
     */
    public function index()
    {
        $data = (new FileManagerService)->getDirectories();
        return view('admin::pages.file_manager.index', ['directories' => $data]);
    }


    /**
     * 获取某个文件夹下面的文件列表
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getFiles(Request $request): array
    {
        $baseFolder = $request->get('base_folder');
        $page = (int)$request->get('page');
        return (new FileManagerService)->getFiles($baseFolder, $page);
    }


    /**
     * 获取文件夹列表
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getDirectories(Request $request)
    {
        $baseFolder = $request->get('base_folder');
        return (new FileManagerService)->getDirectories($baseFolder);
    }


    /**
     * 创建文件夹
     * POST      /admin/file_manager
     * @throws \Exception
     */
    public function createDirectory(Request $request): array
    {
        $folderName = $request->get('name');
        (new FileManagerService)->createDirectory($folderName);
        return json_success('创建成功');
    }


    /**
     * 文件或文件夹改名
     * PUT       /admin/file_manager/rename
     * @throws \Exception
     */
    public function rename(Request $request): array
    {
        $originPath = $request->get('origin_name');
        $newPath = $request->get('new_name');
        (new FileManagerService)->updateName($originPath, $newPath);
        return json_success('修改成功');
    }


    /**
     * 删除文件或文件夹
     * DELETE    /admin/file_manager/delete
     * @throws \Exception
     */
    public function destroyFiles(Request $request): array
    {
        $folderName = $request->get('name');
        (new FileManagerService)->deleteDirectoryOrFile($folderName);
        return json_success('删除成功');
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
        $file = $request->file('file');
        $savePath = $request->get('path');

        $originName = $file->getClientOriginalName();
        $filePath = $file->storeAs($savePath, $originName, 'catalog');

        return [
            'name' => $originName,
            'url' => asset('catalog/' . $filePath),
        ];
    }
}
