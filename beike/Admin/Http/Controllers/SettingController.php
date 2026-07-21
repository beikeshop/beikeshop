<?php

/**
 * SettingController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-06-29 16:02:15
 * @modified   2022-06-29 16:02:15
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\CustomerGroupDetail;
use Beike\Admin\Services\MarketingService;
use Beike\Admin\Services\SettingService;
use Beike\Libraries\Weight;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\SettingRepo;
use Beike\Services\ApiTokenService;
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
        $data = [
            'links' => $this->getSettingLinks(),
        ];

        $data = hook_filter('admin.setting.index.data', $data);

        return view('admin::pages.setting', $data);
    }

    public function basicSettings()
    {
        // 直接定义世界上所有主要时区列表
        $timezones = [
            ['value' => 'UTC', 'label' => 'UTC'],
            ['value' => 'Africa/Cairo', 'label' => 'Africa/Cairo'],
            ['value' => 'Africa/Johannesburg', 'label' => 'Africa/Johannesburg'],
            ['value' => 'Africa/Lagos', 'label' => 'Africa/Lagos'],
            ['value' => 'Africa/Nairobi', 'label' => 'Africa/Nairobi'],
            ['value' => 'America/Chicago', 'label' => 'America/Chicago'],
            ['value' => 'America/Denver', 'label' => 'America/Denver'],
            ['value' => 'America/Los_Angeles', 'label' => 'America/Los_Angeles'],
            ['value' => 'America/New_York', 'label' => 'America/New_York'],
            ['value' => 'America/Sao_Paulo', 'label' => 'America/Sao_Paulo'],
            ['value' => 'Asia/Bangkok', 'label' => 'Asia/Bangkok'],
            ['value' => 'Asia/Dubai', 'label' => 'Asia/Dubai'],
            ['value' => 'Asia/Hong_Kong', 'label' => 'Asia/Hong_Kong'],
            ['value' => 'Asia/Jakarta', 'label' => 'Asia/Jakarta'],
            ['value' => 'Asia/Kolkata', 'label' => 'Asia/Kolkata'],
            ['value' => 'Asia/Seoul', 'label' => 'Asia/Seoul'],
            ['value' => 'Asia/Shanghai', 'label' => 'Asia/Shanghai'],
            ['value' => 'Asia/Tokyo', 'label' => 'Asia/Tokyo'],
            ['value' => 'Australia/Melbourne', 'label' => 'Australia/Melbourne'],
            ['value' => 'Australia/Perth', 'label' => 'Australia/Perth'],
            ['value' => 'Australia/Sydney', 'label' => 'Australia/Sydney'],
            ['value' => 'Europe/Amsterdam', 'label' => 'Europe/Amsterdam'],
            ['value' => 'Europe/Athens', 'label' => 'Europe/Athens'],
            ['value' => 'Europe/Berlin', 'label' => 'Europe/Berlin'],
            ['value' => 'Europe/Istanbul', 'label' => 'Europe/Istanbul'],
            ['value' => 'Europe/London', 'label' => 'Europe/London'],
            ['value' => 'Europe/Madrid', 'label' => 'Europe/Madrid'],
            ['value' => 'Europe/Moscow', 'label' => 'Europe/Moscow'],
            ['value' => 'Europe/Paris', 'label' => 'Europe/Paris'],
            ['value' => 'Europe/Rome', 'label' => 'Europe/Rome'],
            ['value' => 'Europe/Stockholm', 'label' => 'Europe/Stockholm'],
            ['value' => 'Europe/Vienna', 'label' => 'Europe/Vienna'],
            ['value' => 'Europe/Warsaw', 'label' => 'Europe/Warsaw'],
            ['value' => 'Pacific/Auckland', 'label' => 'Pacific/Auckland'],
            ['value' => 'Pacific/Honolulu', 'label' => 'Pacific/Honolulu'],
        ];

        $data = [
            'timezones' => $timezones,
        ];
        $data = hook_filter('admin.setting.basic.data', $data);

        return view('admin::pages.setting.basic', $data);
    }

    public function storeSettings()
    {
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
            'weight_classes'  => Weight::getWeightUnits(),
        ];

        $data = hook_filter('admin.setting.store.data', $data);

        return view('admin::pages.setting.store', $data);
    }

    public function checkoutSettings()
    {
        $taxAddress = [
            ['value' => 'shipping', 'label' => trans('admin/setting.shipping_address')],
            ['value' => 'payment', 'label' => trans('admin/setting.payment_address')],
        ];

        $data = [
            'tax_address'     => $taxAddress,
        ];

        $data = hook_filter('admin.setting.checkout.data', $data);

        return view('admin::pages.setting.checkout', $data);
    }

    public function pictureSettings()
    {
        $data = [];
        $data = hook_filter('admin.setting.picture.data', $data);

        return view('admin::pages.setting.picture', $data);
    }

    public function expressSettings()
    {
        $data = [];
        $data = hook_filter('admin.setting.express.data', $data);

        return view('admin::pages.setting.express', $data);
    }

    public function mailSettings()
    {
        $data = [];
        $data = hook_filter('admin.setting.mail.data', $data);

        return view('admin::pages.setting.mail', $data);
    }

    public function systemAuthorizationSettings()
    {
        $data = [];
        $data = hook_filter('admin.setting.system_authorization.data', $data);

        return view('admin::pages.setting.system_authorization', $data);
    }

    /**
     * 更新系统设置
     *
     * @throws \Throwable
     */
    public function store(Request $request): mixed
    {
        $settings   = $request->all();
        $return_url = $request->get('return_url');

        if (isset($settings['show_price_after_login'])) {
            if ($settings['show_price_after_login']) {
                $settings['guest_checkout'] = false;
            }
        }

        try {
            SettingService::storeSettings($settings);
        } catch (Exception $e) {
            return redirect($return_url ?? url()->current())->withInput()->with('error', $e->getMessage());
        }

        $oldAdminName = admin_name();
        $newAdminName = $settings['admin_name'] ?? 'admin';
        $currentUrl   = $return_url             ?? url()->current();

        if (! empty($request->admin_name)) {
            $currentUrl = str_replace($oldAdminName, $newAdminName, $currentUrl);
        }

        return redirect($currentUrl)->with('success', trans('common.updated_success'));
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
        $developerToken           = (string) $request->get('developer_token');
        $normalizedDeveloperToken = $this->normalizeDeveloperToken($developerToken);

        if ($normalizedDeveloperToken === '') {
            return json_fail(trans('admin/setting.developer_token_required'));
        }

        $domain      = request()->getHost();
        $checkResult = MarketingService::getInstance()->checkToken($domain, $normalizedDeveloperToken);

        if (empty($checkResult['exist'])) {
            return json_fail(trans('admin/setting.developer_token_check_failed'));
        }

        $result = $this->storeDeveloperTokenAndSyncSignatureSecret($developerToken);

        return json_success($result['message'], $result['data']);
    }

    /**
     * 保存开发者令牌，并尝试自动获取签名密钥
     */
    protected function storeDeveloperTokenAndSyncSignatureSecret(string $developerToken): array
    {
        $normalizedDeveloperToken = $this->normalizeDeveloperToken($developerToken);

        if ($normalizedDeveloperToken === '') {
            return [
                'message' => trans('admin/setting.developer_token_required'),
                'data'    => [
                    'developer_token_saved'         => false,
                    'signature_secret_auto_fetched' => false,
                ],
            ];
        }

        $previousDeveloperToken = $this->normalizeDeveloperToken((string) (system_setting('base.developer_token') ?? ''));
        $this->storeSystemSettingValue('developer_token', $normalizedDeveloperToken);

        if ($previousDeveloperToken !== '' && $previousDeveloperToken !== $normalizedDeveloperToken) {
            $this->makeApiTokenService()->clearTokens();
        }

        try {
            $secret = $this->makeApiTokenService()->fetchSignatureSecret($normalizedDeveloperToken);

            return [
                'message' => trans('admin/setting.developer_token_save_success'),
                'data'    => [
                    'developer_token_saved'         => true,
                    'signature_secret_auto_fetched' => true,
                    'secret_length'                 => strlen($secret),
                    'secret_preview'                => $this->buildSecretPreview($secret),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'message' => trans('admin/setting.developer_token_save_partial'),
                'data'    => [
                    'developer_token_saved'         => true,
                    'signature_secret_auto_fetched' => false,
                    'signature_secret_error'        => $e->getMessage(),
                ],
            ];
        }
    }

    /**
     * 方便单测替身覆盖持久化行为
     */
    protected function storeSystemSettingValue(string $name, mixed $value): void
    {
        SettingRepo::storeValue($name, $value);
    }

    /**
     * 方便单测替身覆盖 API 服务
     */
    protected function makeApiTokenService(): ApiTokenService
    {
        return new ApiTokenService;
    }

    protected function buildSecretPreview(string $secret): string
    {
        if (strlen($secret) <= 16) {
            return $secret;
        }

        return substr($secret, 0, 8) . '...' . substr($secret, -6);
    }

    /**
     * 统一清理开发者令牌，避免空白字符导致鉴权失败。
     */
    protected function normalizeDeveloperToken(string $developerToken): string
    {
        $normalized = preg_replace('/\s+/u', '', trim($developerToken));

        return is_string($normalized) ? $normalized : '';
    }

    /**
     * Get the setting links for the settings page
     *
     * @return array
     */
    protected function getSettingLinks(): array
    {
        $items = [
            [
                'title' => trans('admin/common.settings.basic'),
                'child' => [
                    [
                        'title'     => trans('admin/common.settings.basic'),
                        'sub_title' => trans('admin/setting.basic_help'),
                        'url'       => admin_route('settings.basic'),
                        'icon'      => 'bi bi-archive',
                    ],
                    [
                        'title'     => trans('admin/common.settings.store_settings'),
                        'sub_title' => trans('admin/setting.store_help'),
                        'url'       => admin_route('settings.store_settings'),
                        'icon'      => 'bi bi-shop',
                    ],
                    [
                        'title'     => trans('admin/common.settings.checkout'),
                        'sub_title' => trans('admin/setting.checkout_help'),
                        'url'       => admin_route('settings.checkout'),
                        'icon'      => 'bi bi-cart-check',
                    ],
                    [
                        'title'     => trans('admin/common.settings.picture'),
                        'sub_title' => trans('admin/setting.picture_help'),
                        'url'       => admin_route('settings.picture'),
                        'icon'      => 'bi bi-images',
                    ],
                    [
                        'title'     => trans('admin/common.admin_users_index'),
                        'sub_title' => trans('admin/setting.admin_users_help'),
                        'url'       => admin_route('admin_users.index'),
                        'icon'      => 'bi bi-person-gear',
                    ],
                    [
                        'title'     => trans('admin/common.settings.mail'),
                        'sub_title' => trans('admin/setting.mail_help'),
                        'url'       => admin_route('settings.mail'),
                        'icon'      => 'bi bi-envelope',
                    ],
                    [
                        'title'     => trans('admin/common.account_index'),
                        'sub_title' => trans('admin/setting.account_help'),
                        'url'       => admin_route('account.index'),
                        'icon'      => 'bi bi-person-circle',
                    ],
                    [
                        'title'     => trans('admin/common.settings.system_authorization'),
                        'sub_title' => trans('admin/setting.system_authorization_help'),
                        'url'       => admin_route('settings.system_authorization'),
                        'icon'      => 'bi bi-person-circle',
                    ],
                ],
            ],
            [
                'title' => trans('admin/setting.practical_functions'),
                'child' => [
                    [
                        'title'     => trans('admin/common.languages_index'),
                        'sub_title' => trans('admin/setting.languages_help'),
                        'url'       => admin_route('languages.index'),
                        'icon'      => 'bi bi-translate',
                    ],
                    [
                        'title'     => trans('admin/common.currencies_index'),
                        'sub_title' => trans('admin/setting.currencies_help'),
                        'url'       => admin_route('currencies.index'),
                        'icon'      => 'bi bi-coin',
                    ],
                    [
                        'title'     => trans('admin/common.tax_rates_index'),
                        'sub_title' => trans('admin/setting.tax_rates_help'),
                        'url'       => admin_route('tax_rates.index'),
                        'icon'      => 'bi bi-cash-coin',
                    ],
                    [
                        'title'     => trans('admin/common.tax_classes_index'),
                        'sub_title' => trans('admin/setting.tax_classes_help'),
                        'url'       => admin_route('tax_classes.index'),
                        'icon'      => 'bi bi-cash',
                    ],
                    [
                        'title'     => trans('admin/common.countries_index'),
                        'sub_title' => trans('admin/setting.countries_help'),
                        'url'       => admin_route('countries.index'),
                        'icon'      => 'bi bi-buildings',
                    ],
                    [
                        'title'     => trans('admin/common.zones_index'),
                        'sub_title' => trans('admin/setting.zones_help'),
                        'url'       => admin_route('zones.index'),
                        'icon'      => 'bi bi-building',
                    ],
                    [
                        'title'     => trans('admin/common.regions_index'),
                        'sub_title' => trans('admin/setting.regions_help'),
                        'url'       => admin_route('regions.index'),
                        'icon'      => 'bi bi-collection',
                    ],
                    [
                        'title'     => trans('order.express_company'),
                        'sub_title' => trans('admin/setting.express_help'),
                        'url'       => admin_route('settings.express'),
                        'icon'      => 'bi bi-truck',
                    ],
                    [
                        'title'     => trans('admin/common.mail_logs_index'),
                        'sub_title' => trans('admin/setting.mail_logs_help'),
                        'url'       => admin_route('mail_logs.index'),
                        'icon'      => 'bi bi-envelope-check',
                    ],
                ],
            ],
        ];

        return hook_filter('admin.setting.links', $items);
    }

    /**
     * 获取签名密钥（用于本地签名）
     *
     * @return JsonResponse
     */
    public function fetchSignatureSecret(): JsonResponse
    {
        try {
            $tokenService = new \Beike\Services\ApiTokenService;
            $secret       = $tokenService->fetchSignatureSecret();

            return json_success(trans('admin/setting.signature_secret_fetch_success'), [
                'secret_length'  => strlen($secret),
                'secret_preview' => substr($secret, 0, 8) . '...' . substr($secret, -8),
            ]);
        } catch (\Exception $e) {
            return json_fail(trans('admin/setting.signature_secret_fetch_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * 获取签名密钥状态
     *
     * @return JsonResponse
     */
    public function getSignatureSecretStatus(): JsonResponse
    {
        try {
            $secret = system_setting('base.signature_secret');

            return json_success(trans('admin/setting.signature_secret_status_fetch_success'), [
                'has_secret'     => ! empty($secret),
                'secret_length'  => $secret ? strlen($secret) : 0,
                'secret_preview' => $secret ? substr($secret, 0, 8) . '...' . substr($secret, -8) : '',
            ]);
        } catch (\Exception $e) {
            return json_fail(trans('admin/setting.signature_secret_status_fetch_failed') . ': ' . $e->getMessage());
        }
    }
}
