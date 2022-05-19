<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        return view('admin::pages.file.index');
    }

    public function store(Request $request)
    {
        // $user = logged_admin_user();
        $file = $request->file('file');
        $path = $file->store('', 'upload');

        return [
            'name' => $file->getClientOriginalName(),
            'url' => asset('upload/' . $path),
        ];
    }
}
