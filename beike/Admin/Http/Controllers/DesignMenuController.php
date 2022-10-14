<?php

namespace Beike\Admin\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Beike\Repositories\FooterRepo;
use Beike\Repositories\SettingRepo;

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
        return view('admin::pages.design.builder.menu', $data);
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

        SettingRepo::storeValue("menu_setting", $content);
        return json_success(trans('common.updated_success'));
    }
}
