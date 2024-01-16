<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\SettingRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignMenuController extends Controller
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
            'design_settings' => system_setting('base.menu_setting', []),
        ];
        $data = hook_filter('admin.design_menu.index.data', $data);

        return view('admin::pages.design.builder.menu', $data);
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
        $content = json_decode($request->getContent(), true);

        SettingRepo::storeValue('menu_setting', $content);

        return json_success(trans('common.updated_success'));
    }
}
