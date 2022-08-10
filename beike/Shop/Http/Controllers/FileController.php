<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Shop\Http\Requests\UploadRequest;

class FileController extends Controller
{
    public function store(UploadRequest $request)
    {
        dump($request->all);
        $file = $request->file('file');
        $type = $request->get('type');

        $path = $file->store($type . '/', 'upload');

        return [
            'url' => asset('upload/' . $path),
            'value' => 'upload/' . $path,
        ];
    }
}
