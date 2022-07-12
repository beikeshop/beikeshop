<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)
    {
        $baseFolder = $request->get('base_folder');

        $fileBasePath = public_path('catalog');
        if ($baseFolder) {
            $fileBasePath .= '/' . $baseFolder;
        }
        $files = glob($fileBasePath . '/*');

        $folders = $images = [];
        foreach ($files as $file) {
            $baseName = basename($file);
            if ($baseName == 'index.html') {
                continue;
            }

            $fileName = str_replace($fileBasePath, '', $file);
            if (is_dir($file)) {
                $folders[] = $fileName;
            } elseif (is_file($file)) {
                $images[] = $fileName;
            }
        }

        $data = [
            'folders' => $folders,
            'images' => $images
        ];
        if ($request->ajax()) {
            return $data;
        }
        return view('admin::pages.filemanager.index', $data);
    }
}
