<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Services\FileManagerService;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $baseFolder = $request->get('base_folder');
        $data = (new FileManagerService)->getFiles($baseFolder);

        if ($request->ajax()) {
            return $data;
        }
        return view('admin::pages.filemanager.index', $data);
    }
}
