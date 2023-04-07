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

use Beike\Admin\Http\Resources\CustomerGroupDetail;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\SettingRepo;
use Beike\Repositories\ThemeRepo;
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
        $themes = ThemeRepo::getAllThemes();

        $taxAddress = [
            ['value' => 'shipping', 'label' => trans('admin/setting.shipping_address')],
            ['value' => 'payment', 'label' => trans('admin/setting.payment_address')],
        ];
        $multiFilter = system_setting('base.multi_filter');
        if ($attributeIds = $multiFilter['attribute'] ?? []) {
            $multiFilter['attribute'] = AttributeRepo::getByIds($attributeIds);
        }

        $data = [
            'countries'       => CountryRepo::listEnabled(),
            'currencies'      => CurrencyRepo::listEnabled(),
            'multi_filter'   => $multiFilter,
            'tax_address'     => $taxAddress,
            'customer_groups' => CustomerGroupDetail::collection(CustomerGroupRepo::list())->jsonSerialize(),
            'themes'          => $themes,
        ];

        $data = hook_filter('admin.setting.index.data', $data);

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
        $settingUrl   = str_replace($oldAdminName, $newAdminName, admin_route('settings.index'));

        return redirect($settingUrl)->with('success', trans('common.updated_success'));
    }

    public function storeDeveloperToken(Request $request)
    {
        SettingRepo::storeValue('developer_token', $request->get('developer_token'));

        return json_success(trans('common.updated_success'));
    }
}
