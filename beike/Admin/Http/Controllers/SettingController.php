<?php
/**
 * SettingController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-29 16:02:15
 * @modified   2022-06-29 16:02:15
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\SettingRepo;
use Beike\Repositories\SystemSettingRepo;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * 显示系统设置页面
     *
     * @return mixed
     */
    public function index()
    {
        $settings = SystemSettingRepo::getList();

        return view('admin::pages.setting', ['settings' => $settings]);
    }


    /**
     * 更新系统设置
     *
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $settings = json_decode($request->getContent(), true);
        foreach ($settings as $key => $value) {
            $data = [
                'type' => 'system',
                'space' => 'base',
                'name' => $key,
                'value' => $value,
                'json' => is_array($value)
            ];
            SettingRepo::createOrUpdate($data);
        }

        return json_success("修改成功");
    }
}
