<?php

namespace Beike\Installer\Controllers;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    protected function checkInstalled()
    {
        if (installed()) {
            exit('Already installed');
        }
    }
}
