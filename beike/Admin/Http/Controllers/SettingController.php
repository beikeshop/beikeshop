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
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\SettingRepo;
use Beike\Repositories\ThemeRepo;
use Beike\Services\ApiTokenService;
use Beike\Libraries\Weight;
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
            'weight_classes'  => Weight::getWeightUnits(),
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
        $tab = $request->get('tab');
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
        $settingUrl   = str_replace($oldAdminName, $newAdminName, admin_route('settings.index', ['tab' => $tab]));

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
        $developerToken = (string) $request->get('developer_token');
        $normalizedDeveloperToken = $this->normalizeDeveloperToken($developerToken);

        if ($normalizedDeveloperToken === '') {
            return json_fail(trans('admin/setting.developer_token_required'));
        }

        $domain = request()->getHost();
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
                'message' => '开发者令牌不能为空',
                'data' => [
                    'developer_token_saved' => false,
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
                'message' => '开发者令牌保存成功，签名密钥已自动获取',
                'data' => [
                    'developer_token_saved' => true,
                    'signature_secret_auto_fetched' => true,
                    'secret_length' => strlen($secret),
                    'secret_preview' => $this->buildSecretPreview($secret),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'message' => '开发者令牌保存成功，但签名密钥自动获取失败，可稍后重试',
                'data' => [
                    'developer_token_saved' => true,
                    'signature_secret_auto_fetched' => false,
                    'signature_secret_error' => $e->getMessage(),
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
        return new ApiTokenService();
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
     * 获取签名密钥（用于本地签名）
     */
    public function fetchSignatureSecret(): JsonResponse
    {
        try {
            $secret = $this->makeApiTokenService()->fetchSignatureSecret();

            return json_success('签名密钥获取成功', [
                'secret_length' => strlen($secret),
                'secret_preview' => substr($secret, 0, 8) . '...' . substr($secret, -8),
            ]);
        } catch (\Exception $e) {
            return json_fail('获取签名密钥失败: ' . $e->getMessage());
        }
    }

    /**
     * 获取签名密钥状态
     */
    public function getSignatureSecretStatus(): JsonResponse
    {
        try {
            $secret = system_setting('base.signature_secret');

            return json_success('获取成功', [
                'has_secret' => !empty($secret),
                'secret_length' => $secret ? strlen($secret) : 0,
                'secret_preview' => $secret ? substr($secret, 0, 8) . '...' . substr($secret, -8) : '',
            ]);
        } catch (\Exception $e) {
            return json_fail('获取状态失败: ' . $e->getMessage());
        }
    }
}
