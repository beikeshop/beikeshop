<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Repositories\ProductRepo;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'category_products' => [],
        ];

        $html = '';
        $designSettings = system_setting('base.design_setting');
        $modules = $designSettings['form']['modules'] ?? [];

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
