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
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CurrencyRepo;
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
        $themes =  [
            ['value' => 'default', 'label' => '默认主题'],
            ['value' => 'black', 'label' => '黑色主题']
        ];

        $tax_address = [
            ['value' => 'shipping', 'label' => '配送地址'],
            ['value' => 'payment', 'label' => '账单地址']
        ];

        $data = [
            'settings' => SystemSettingRepo::getList(),
            'countries' => CountryRepo::all(),
            'currencies' => CurrencyRepo::all(),
            'tax_address' => $tax_address,
            'themes' => $themes
        ];

        return view('admin::pages.setting', $data);
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
