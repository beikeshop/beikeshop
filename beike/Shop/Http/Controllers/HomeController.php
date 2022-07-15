<?php

namespace Beike\Shop\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $html = '';
        $designSettings = system_setting('base.design_setting');
        $modules = $designSettings['modules'] ?? [];

        foreach ($modules as $module) {
            $code = $module['code'];
            $content = $module['content'];
            $viewPath = "design.{$code}";
            if (view()->exists($viewPath)) {
                $html .= view($viewPath, $content)->render();
            }
        }
        $data['html'] = $html;

        return view('home', $data);
    }
}
