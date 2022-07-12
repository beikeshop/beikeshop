<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Services\FileManagerService;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $baseFolder = $request->get('base_folder');
        $page = (int)$request->get('page');
        $data = (new FileManagerService)->getFiles($baseFolder, $page);

        if ($request->expectsJson()) {
            return $data;
        }
        return view('admin::pages.filemanager.index', $data);
    }
}
