<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Services\DesignService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * 通过page builder 显示首页
     *
     * @return View
     * @throws \Exception
     */
    public function index(): View
    {
        $designSettings = system_setting('base.design_setting');
        $modules        = $designSettings['modules'] ?? [];

        $moduleItems = [];
        foreach ($modules as $module) {
            $code       = $module['code'];
            $moduleId   = $module['module_id'] ?? '';
            $content    = $module['content'];
            $viewPath   = $module['view_path'] ?? '';

            if (empty($viewPath)) {
                $viewPath = "design.{$code}";
            }

            $paths = explode('::', $viewPath);
            if (count($paths) == 2) {
                $pluginCode = $paths[0];
                if (! app('plugin')->checkActive($pluginCode)) {
                    continue;
                }
            }

            if (view()->exists($viewPath) && $moduleId) {
                $moduleItems[] = [
                    'code'      => $code,
                    'module_id' => $moduleId,
                    'view_path' => $viewPath,
                    'content'   => DesignService::handleModuleContent($code, $content),
                ];
            }
        }

        $data = ['modules' => $moduleItems];

        $data = hook_filter('home.index.data', $data);

        return view('home', $data);
    }
}
