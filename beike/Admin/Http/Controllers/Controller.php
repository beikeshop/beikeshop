<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected string $defaultRoute;

    /**
     * 表单页面获跳转页面链接
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Request|string|null
     */
    public function getRedirect()
    {
        return request('_redirect') ?? request()->header('referer', admin_route($this->defaultRoute));
    }
}
