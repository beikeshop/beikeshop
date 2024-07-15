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
use Beike\Admin\Services\SettingService;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\SettingRepo;
use Beike\Repositories\ThemeRepo;
use Exception;
use Illuminate\Http\JsonResponse;
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

        $data = [
            'countries'       => CountryRepo::listEnabled(),
            'currencies'      => CurrencyRepo::listEnabled(),
            'languages'       => LanguageRepo::enabled()->toArray(),
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
    public function store(Request $request): mixed
    {
        $settings = $request->all();
        if (isset($settings['show_price_after_login'])) {
            if ($settings['show_price_after_login']) {
                $settings['guest_checkout'] = false;
            }
        }

        try {
            SettingService::storeSettings($settings);
        } catch (Exception $e) {
            return redirect(admin_route('settings.index'))->withInput()->with('error', $e->getMessage());
        }

        $oldAdminName = admin_name();
        $newAdminName = $settings['admin_name'] ?: 'admin';
        $settingUrl   = str_replace($oldAdminName, $newAdminName, admin_route('settings.index'));

        return redirect($settingUrl)->with('success', trans('common.updated_success'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function updateValues(Request $request): JsonResponse
    {
        $settings = $request->all();

        try {
            SettingService::storeSettings($settings);

            return json_success(trans('common.updated_success'));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function storeDeveloperToken(Request $request): JsonResponse
    {
        SettingRepo::storeValue('developer_token', $request->get('developer_token'));

        return json_success(trans('common.updated_success'));
    }
}
