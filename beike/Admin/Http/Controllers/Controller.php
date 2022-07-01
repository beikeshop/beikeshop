<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected string $defaultRoute;

    /**
     * 表单页面获跳转页面链接
     * @return mixed
     */
    public function getRedirect()
    {
        if (empty($this->defaultRoute)) {
            $this->defaultRoute = $this->getDefaultRoute();
        }
        return request('_redirect') ?? request()->header('referer', admin_route($this->defaultRoute));
    }

    /**
     * 获取当前管理界面列表页路由
     * @return string
     */
    private function getDefaultRoute(): string
    {
        $currentRouteName = Route::getCurrentRoute()->getName();
        $names = explode('.', $currentRouteName);
        $name = $names[1] ?? '';
        return "{$name}.index";
    }
}
