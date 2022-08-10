<?php

namespace Beike\Shop\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('file');
        $type = $request->get('type');

        $path = $file->store($type . '/', 'upload');

        return [
            'url' => asset('upload/' . $path),
            'value' => 'upload/' . $path,
        ];
    }
}
