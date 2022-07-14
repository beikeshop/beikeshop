<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Services\DesignService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Beike\Repositories\SettingRepo;
use Beike\Repositories\LanguageRepo;

class DesignController extends Controller
{
    /**
     * 展示所有模块编辑器
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $data = [
            'editors' => ['editor-slide_show'],
            'languages' => LanguageRepo::all(),
            'design_settings' => system_setting('base.design_setting'),
        ];
        return view('admin::pages.design.builder.index', $data);
    }


    /**
     * 预览模块显示结果
     *
     * @param Request $request
     * @return View
     */
    public function preview(Request $request): View
    {
        $moduleName = $request->get('module');
        $content = $request->get('content');
        $viewPath = "design.{$moduleName}";
        return view($viewPath, $content);
    }


    /**
     * 更新所有数据
     *
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function update(Request $request): array
    {
        $moduleData = DesignService::handleModules($request->getContent());
        $data = [
            'type' => 'system',
            'space' => 'base',
            'name' => 'design_setting',
            'value' => $moduleData,
            'json' => 1
        ];
        SettingRepo::createOrUpdate($data);
        return json_success("保存成功");
    }
}
