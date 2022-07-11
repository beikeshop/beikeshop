<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Repositories\ProductRepo;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'category_products' => ProductRepo::getProductsByCategories([100002, 100003, 100004, 100005]),
        ];

        $html = '';
        $designSettings = setting('system.design_setting');
        $modules = $designSettings['form']['modules'] ?? [];

        foreach ($modules as $module) {
            $code = $module['code'];
            $content = $module['content'];
            $viewPath = "design.module.{$code}.render.index";
            if (view()->exists($viewPath)) {
                $html .= view($viewPath, $content)->render();
            }
        }
        $data['html'] = $html;

        return view('home', $data);
    }
}
