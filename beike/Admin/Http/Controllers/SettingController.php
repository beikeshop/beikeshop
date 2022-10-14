<?php
/**
 * SettingController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-29 16:02:15
 * @modified   2022-06-29 16:02:15
 */

namespace Beike\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Beike\Repositories\SettingRepo;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Admin\Http\Resources\CustomerGroupDetail;

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
            'countries' => CountryRepo::listEnabled(),
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
            SettingRepo::storeValue($key, $value);
        }

        $oldAdminName = admin_name();
        $newAdminName = $settings['admin_name'] ?: 'admin';
        $settingUrl = str_replace($oldAdminName, $newAdminName, admin_route('settings.index'));
        return redirect($settingUrl)->with('success', trans('common.updated_success'));
    }

    public function storeDeveloperToken(Request $request)
    {
        SettingRepo::storeValue('developer_token', $request->get('developer_token'));
        return json_success(trans('common.updated_success'));
    }
}
