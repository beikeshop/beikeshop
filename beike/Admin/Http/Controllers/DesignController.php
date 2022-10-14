<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Beike\Services\DesignService;
use Beike\Repositories\SettingRepo;

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
            'editors' => ['editor-slide_show', 'editor-image401', 'editor-tab_product', 'editor-image100', 'editor-brand'],
            'design_settings' => system_setting('base.design_setting'),
        ];
        return view('admin::pages.design.builder.index', $data);
    }


    /**
     * 预览模块显示结果
     *
     * @param Request $request
     * @return View
     * @throws \Exception
     */
    public function preview(Request $request): View
    {
        $module = json_decode($request->getContent(), true);
        $moduleId = $module['module_id'] ?? '';
        $moduleCode = $module['code'] ?? '';
        $content = $module['content'] ?? '';
        $viewPath = "design.{$moduleCode}";

        $viewData = [
            'code' => $moduleCode,
            'module_id' => $moduleId,
            'view_path' => $viewPath,
            'content' => DesignService::handleModuleContent($moduleCode, $content),
            'design' => (bool)$request->get('design')
        ];

        return view($viewPath, $viewData);
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
        $content = json_decode($request->getContent(), true);
        $moduleData = DesignService::handleRequestModules($content);
        SettingRepo::storeValue('design_setting', $moduleData);
        return json_success(trans('common.updated_success'));
    }
}
