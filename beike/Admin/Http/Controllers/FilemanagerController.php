<?php

namespace Beike\Admin\Http\Controllers;

class FileManagerController extends Controller
{
    public function index()
    {
        $fileBasePath = public_path('catalog');
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
        return view('admin::pages.filemanager.index', $data);
    }
}
