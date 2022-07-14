<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Beike\Repositories\SettingRepo;
use Beike\Repositories\LanguageRepo;

class DesignController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'editors' => ['editor-slide_show'],
            'languages' => LanguageRepo::all(),
            'design_settings' => system_setting('base.design_setting'),
        ];
        return view('admin::pages.design.builder.index', $data);
    }


    /**
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function update(Request $request): array
    {
        $data = [
            'type' => 'system',
            'space' => 'base',
            'name' => 'design_setting',
            'value' => $request->getContent(),
            'json' => 1
        ];
        SettingRepo::createOrUpdate($data);
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
