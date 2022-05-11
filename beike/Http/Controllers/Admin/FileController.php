<?php

namespace Beike\Http\Controllers\Admin;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $path = $request->file('file')->store('avatars');
    }
}
