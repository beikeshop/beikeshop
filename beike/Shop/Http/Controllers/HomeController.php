<?php

namespace Beike\Shop\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * 通过page builder 显示首页
     *
     * @return View
     */
    public function index(): View
    {
        $designSettings = system_setting('base.design_setting');
        $modules = $designSettings['modules'] ?? [];

        $moduleItems = [];
        foreach ($modules as $module) {
            $code = $module['code'];
            $content = $module['content'];
            $viewPath = "design.{$code}";
            if (view()->exists($viewPath)) {
                $moduleItems[] = [
                    'view_path' => $viewPath,
                    'content' => $content
                ];
            }
        }

        return view('home', ['modules' => $moduleItems]);
    }
}
