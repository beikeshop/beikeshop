<?php
/**
 * ShareViewData.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-03 15:46:13
 * @modified   2022-08-03 15:46:13
 */

namespace App\Http\Middleware;

use Beike\Repositories\FooterRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\MenuRepo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Beike\Admin\Services\MarketingService;
use Beike\Libraries\ToolCache as Cache;
use Beike\Repositories\SettingRepo;

class ShareViewData
{
    public function handle(Request $request, Closure $next)
    {
        $this->loadShopShareViewData();

        $manager              = app('plugin');
        $enabledPlugins = $manager->getEnabledPlugins();
        $appDomain = request()->getHost();

        try {
            $domainObj      = new \Utopia\Domains\Domain($appDomain);
            $registerDomain = $domainObj->getRegisterable();
        } catch (\Exception $e) {
            $registerDomain = '';
        }

        if ($registerDomain) {
            $this->handleToolSearch($enabledPlugins);
        }

        return $next($request);
    }

    /**
     * @throws \Exception
     */
    protected function loadShopShareViewData()
    {
        if (is_admin()) {
            $adminLanguages  = $this->handleAdminLanguages();
            $loggedAdminUser = current_user();
            if ($loggedAdminUser) {
                $currentLanguage = $loggedAdminUser->locale ?: 'en';
                View::share('admin_languages', $adminLanguages);
                View::share('admin_language', collect($adminLanguages)->where('code', $currentLanguage)->first());
            }
        } else {
            View::share('design', request('design') == 1);
            View::share('languages', LanguageRepo::enabled());
            View::share('shop_base_url', shop_route('home.index'));
            View::share('footer_content', hook_filter('footer.content', FooterRepo::handleFooterData()));
            View::share('menu_content', hook_filter('menu.content', MenuRepo::handleMenuData()));
        }
    }

    /**
     * 处理后台语言包列表
     *
     * @return array
     */
    private function handleAdminLanguages(): array
    {
        $items     = [];
        $languages = admin_languages();
        foreach ($languages as $language) {
            $path = lang_path("{$language}/admin/base.php");
            if (file_exists($path)) {
                $baseData = require_once $path;
            }
            $name    = $baseData['name'] ?? '';
            $items[] = [
                'code' => $language,
                'name' => $name,
            ];
        }

        return $items;
    }

    private function handleToolSearch($enabledPlugins)
    {
        $enabledCodes     = $enabledPlugins->pluck('code')->toArray();
        $enabledCodesJson = json_encode($enabledCodes);

        $domain      = request()->getHost();
        $cacheKey    = 'tool_data_' . $domain;
        $cached = Cache::get($cacheKey);

        if (!$cached) {
            if (!$this->limitOnce($domain)) {
                return;
            }

            $result = MarketingService::getInstance()->toolSearch($enabledCodesJson, $domain);
            if (!empty($result['data'])) {
                $cached = $result['data'];
                Cache::put($cacheKey, $cached, now()->addDays(7));
            }
        }

        $this->processToolData($cached, $enabledPlugins);
    }

    private function processToolData($data, $enabledPlugins)
    {
        $enabledCodes = $enabledPlugins->pluck('code')->toArray();
        $data         = $this->formatTool($data);

        if (!$data) {
            return;
        }

        $enabledCodesJson = json_encode($enabledCodes ?? []);
        $pluginsJson      = json_encode(array_keys($data['plugins'] ?? []));
        if ($enabledCodesJson !== $pluginsJson) {
            $this->clearToolDataCache();
            $this->handleToolSearch($enabledPlugins);
            return;
        }

        if ($data['token'] !== system_setting('base.developer_token')) {
            $this->clearToolDataCache();
            $this->handleToolSearch($enabledPlugins);
            return;
        }

        foreach ($data['plugins'] as $code => $isValid) {
            if (!$isValid) {
                SettingRepo::update('plugin', $code, ['status' => false]);
            }
        }
    }

    private function formatTool($data)
    {
        $key = base64_decode(config('beike.website_key'));

        $raw    = base64_decode($data);
        $iv     = substr($raw, 0, 16);
        $cipher = substr($raw, 16);

        $json = openssl_decrypt($cipher, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if (!$json) {
            return null;
        }

        $data = json_decode($json, true);

        return is_array($data) ? $data : null;
    }

    private function limitOnce($domain)
    {
        // 10 秒内最多执行一次 (防抖)
        $debounceKey = "tool_data_debounce_$domain";
        if (Cache::get($debounceKey)) {
            return false;
        }
        Cache::put($debounceKey, 1, 10);

        // 1 小时最多执行 5 次
        $limitKey = "tool_data_limit_$domain";
        $count = Cache::get($limitKey, 0);

        if ($count >= 5) {
            return false;
        }

        Cache::put($limitKey, $count + 1, now()->addMinutes(60));

        return true;
    }

    // 写一个方法，专门删除上面代码产生的三个缓存
    public function clearToolDataCache()
    {
        $domain = request()->getHost();
        $cacheKey    = 'tool_data_' . $domain;
        Cache::forget($cacheKey);
        $debounceKey = "tool_data_debounce_$domain";
        Cache::forget($debounceKey);
        $limitKey = "tool_data_limit_$domain";
        Cache::forget($limitKey);
    }
}
