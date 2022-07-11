<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\Http\Request;

class FilemanagerController extends Controller
{
    public function index()
    {

        $data = [];
        return view('admin::pages.filemanager.index', $data);
    }
}
