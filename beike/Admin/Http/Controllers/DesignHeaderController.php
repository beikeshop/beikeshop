<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\SettingRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesignHeaderController extends Controller
{
    /**
     * 展示所有模块编辑器
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $headerSetting = system_setting('base.header_setting', []);
        if (!isset($headerSetting['header_ads'])) {
            $headerSetting['header_ads'] = [
                'active' => 1,
                'bg_color' => '#333333',
                'color' => '#ffffff',
                'items' => [],
            ];
        }

        $data = [
            'design_settings' => $headerSetting,
        ];
        $data = hook_filter('admin.design_header.index.data', $data);

        return view('admin::pages.design.builder.header', $data);
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

        SettingRepo::storeValue('header_setting', $content);

        return json_success(trans('common.updated_success'));
    }
}
