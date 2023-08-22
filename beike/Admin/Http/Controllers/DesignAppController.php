<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\SettingRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignAppController extends Controller
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
            'design_settings' => system_setting('base.app_home_setting', ['modules' => []]),
        ];

        $data = hook_filter('admin.design_app_home.index.data', $data);

        return view('admin::pages.design.builder.app_home', $data);
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

        SettingRepo::storeValue('app_home_setting', $content);

        return json_success(trans('common.updated_success'));
    }
}
