<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Beike\Repositories\SettingRepo;

class DesignController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'editors' => ['editor-slide_show'],
            'design_settings' => setting('system.design_setting'),
        ];
        return view('design.builder.index', $data);
    }


    /**
     * @param Request $request
     * @return array
     */
    public function update(Request $request): array
    {
        $fields = [
            'design_setting' => $request->get('design_setting')
        ];
        SettingRepo::update('system', 'base', $fields);
        return json_success("保存成功");
    }


    /**
     * @param Request $request
     * @return View
     */
    public function showModule(Request $request): View
    {
        $moduleName = $request->get('module');
        $content = $request->get('content');
        $viewPath = "design.module.{$moduleName}.render.index";
        return view($viewPath, $content);
    }
}
