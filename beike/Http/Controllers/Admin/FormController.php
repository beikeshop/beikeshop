<?php

namespace Beike\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

abstract class FormController extends Controller
{
    protected string $_redirect;
    protected string $defaultRoute;

    public function __construct()
    {
        $this->_redirect = request()->header('referer', admin_route($this->defaultRoute));
    }
}
