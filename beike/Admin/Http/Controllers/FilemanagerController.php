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
dd($data);
        if ($request->expectsJson()) {
            return $data;
        }
        return view('admin::pages.filemanager.index', $data);
    }
}
