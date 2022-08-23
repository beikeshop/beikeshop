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

use Beike\Admin\Http\Resources\CustomerGroupDetail;
use Beike\Models\CustomerGroup;
use Beike\Repositories\CustomerGroupRepo;
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
        $themes = [
            ['value' => 'default', 'label' => trans('admin/setting.theme_default')],
            ['value' => 'black', 'label' => trans('admin/setting.theme_black')]
        ];

        $tax_address = [
            ['value' => 'shipping', 'label' => trans('admin/setting.shipping_address')],
            ['value' => 'payment', 'label' => trans('admin/setting.payment_address')]
        ];

        $data = [
            'countries' => CountryRepo::all(),
            'currencies' => CurrencyRepo::listEnabled(),
            'tax_address' => $tax_address,
            'customer_groups' => CustomerGroupDetail::collection(CustomerGroupRepo::list())->jsonSerialize(),
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
        $settings = $request->all();
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

        $oldAdminName = admin_name();
        $newAdminName = $settings['admin_name'] ?: 'admin';
        $settingUrl = str_replace($oldAdminName, $newAdminName, admin_route('settings.index'));
        return redirect($settingUrl)->with('success', trans('common.updated_success'));
    }
}
