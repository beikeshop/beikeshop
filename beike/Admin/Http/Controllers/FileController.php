<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('file');
        $type = $request->get('type');

        $path = $file->store($type, 'upload');

        $data = [
            'url'   => asset('upload/' . $path),
            'value' => 'upload/' . $path,
        ];

        $data = hook_filter('file.store.data', $data);

        return json_success(trans('shop/file.uploaded_success'), $data);
    }
}
