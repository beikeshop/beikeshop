<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\SettingRepo;
use Beike\Services\DesignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            'editors'         => [
                'editor-slide_show', 'editor-image401', 'editor-image402', 'editor-tab_product', 'editor-product', 'editor-image100',
                'editor-brand', 'editor-icons', 'editor-rich_text', 'editor-image200', 'editor-image300', 'editor-image301', 'editor-page',
            ],
            'design_settings' => system_setting('base.design_setting'),
        ];

        $data = hook_filter('admin.design.index.data', $data);

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
        $module     = json_decode($request->getContent(), true);
        $moduleId   = $module['module_id'] ?? '';
        $moduleCode = $module['code']      ?? '';
        $content    = $module['content']   ?? '';
        $viewPath   = $module['view_path'] ?? '';

        if (empty($viewPath)) {
            $viewPath   = "design.{$moduleCode}";
        }

        $viewData = [
            'code'      => $moduleCode,
            'module_id' => $moduleId,
            'view_path' => $viewPath,
            'content'   => DesignService::handleModuleContent($moduleCode, $content),
            'design'    => (bool) $request->get('design'),
        ];

        $viewData = hook_filter('admin.design.preview.data', $viewData);

        return view($viewPath, $viewData);
    }

    /**
     * 更新所有数据
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request): JsonResponse
    {
        $content    = json_decode($request->getContent(), true);
        $moduleData = DesignService::handleRequestModules($content);
        SettingRepo::storeValue('design_setting', $moduleData);

        hook_action('admin.design.update.after', $moduleData);

        return json_success(trans('common.updated_success'));
    }
}
