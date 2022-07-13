<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Services\FileManagerService;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    /**
     * 获取文件夹和文件列表
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $baseFolder = $request->get('base_folder');
        $page = (int)$request->get('page');
        $data = (new FileManagerService)->getFiles($baseFolder, $page);

        if ($request->expectsJson()) {
            return $data;
        }

        return view('admin::pages.file_manager.index', $data);
    }


    /**
     * 创建文件夹
     * POST      /admin/file_manager
     * @throws \Exception
     */
    public function store(Request $request): array
    {
        $folderName = $request->get('name');
        (new FileManagerService)->createDirectory($folderName);
        return json_success('创建成功');
    }


    /**
     * 删除文件或文件夹
     * DELETE    /admin/file_manager/{file_manager}
     * @throws \Exception
     */
    public function destroy(Request $request): array
    {
        $folderName = $request->get('name');
        (new FileManagerService)->deleteDirectoryOrFile($folderName);
        return json_success('删除成功');
    }


    /**
     * 文件或文件夹改名
     * PUT       /admin/file_manager/{file_manager}
     */
    public function update(Request $request)
    {
        $folderName = $request->get('name');
        (new FileManagerService)->updateName($folderName);
        return json_success('删除成功');
    }


    /**
     * 上传文件
     * POST      /admin/file_manager/upload
     *
     * @param Request $request
     * @return array
     */
    public function uploadFiles(Request $request): array
    {
        $file = $request->file('file');
        $path = $file->store('xxx', 'upload');

        return [
            'name' => $file->getClientOriginalName(),
            'url' => asset('upload/' . $path),
        ];
    }
}
