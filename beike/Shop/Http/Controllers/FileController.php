<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Shop\Http\Requests\UploadRequest;

class FileController extends Controller
{
    public function store(UploadRequest $request)
    {
        $file = $request->file('file');
        $type = $request->get('type', 'default');

        $type = preg_replace('/[^a-zA-Z0-9_-]/', '', $type);
        if (empty($type)) {
            $type = 'default';
        }

        $path = $file->store($type, 'upload');

        $data = [
            'url'   => asset('upload/' . $path),
            'value' => 'upload/' . $path,
        ];

        $data = hook_filter('file.store.data', $data);

        return json_success(trans('shop/file.uploaded_success'), $data);
    }
}
