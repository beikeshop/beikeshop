<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $path = $request->file('file')->store('avatars');
    }
}
