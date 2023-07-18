<?php

use Barryvdh\Debugbar\Facades\Debugbar;
use Beike\Hook\Facades\Hook;
use Beike\Models\AdminUser;
use Beike\Models\Currency;
use Beike\Models\Customer;
use Beike\Models\Language;
use Beike\Plugin\Plugin;
use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Services\CurrencyService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use TorMorten\Eventy\Facades\Eventy;

/**
 * 获取后台设置到 settings 表的值
 *
 * @param $key
 * @param null $default
 * @return mixed
 */
function setting($key, $default = null)
{
    return config("bk.{$key}", $default);
}

/**
 * 获取系统 settings
 *
 * @param $key
 * @param null $default
 * @return mixed
 */
function system_setting($key, $default = null)
{
    return setting("system.{$key}", $default);
}

/**
 * 获取后台设置到 settings 表的值
 *
 * @param $key
 * @param null $default
 * @return mixed
 */
function plugin_setting($key, $default = null)
{
    return setting("plugin.{$key}", $default);
}

/**
 * 获取插件静态文件
 *
 * @param $code , 插件编码
 * @param $filePath , 相对于插件目录 static 的文件路径
 */
function plugin_asset($code, $filePath): string
{
    return shop_route('plugin.asset', ['code' => $code, 'path' => $filePath]);
}

/**
 * 获取后台管理前缀名称, 默认为 admin
 */
function admin_name(): string
{
    if ($envAdminName = config('beike.admin_name')) {
        return Str::snake($envAdminName);
    } elseif ($settingAdminName = system_setting('base.admin_name')) {
        return Str::snake($settingAdminName);
    }

    return 'admin';
}

/**
 * 获取后台设置项
 */
function load_settings()
{
    if (is_installer() || ! file_exists(__DIR__ . '/../storage/installed')) {
        return;
    }
    $result = \Beike\Repositories\SettingRepo::getGroupedSettings();
    config(['bk' => $result]);
}

/**
 * 获取后台链接
 *
 * @param $route
 * @param mixed $params
 * @return string
 */
function admin_route($route, $params = []): string
{
    $adminName = admin_name();

    return route("{$adminName}.{$route}", $params);
}

/**
 * 获取前台链接
 *
 * @param $route
 * @param mixed $params
 * @return string
 */
function shop_route($route, $params = []): string
{
    return route('shop.' . $route, $params);
}

/**
 * 获取 category, product, brand, page, static, custom 路由链接
 *
 * @param $type
 * @param $value
 * @return string
 * @throws Exception
 */
function type_route($type, $value): string
{
    return \Beike\Libraries\Url::getInstance()->link($type, $value);
}

/**
 * 获取 category, product, brand, page, static, custom 链接名称
 *
 * @param $type
 * @param $value
 * @param array $texts
 * @return string
 */
function type_label($type, $value, array $texts = []): string
{
    return \Beike\Libraries\Url::getInstance()->label($type, $value, $texts);
}

/**
 * 处理配置链接
 *
 * @param $link
 * @return array
 * @throws Exception
 */
function handle_link($link): array
{
    $type  = $link['type']  ?? '';
    $value = $link['value'] ?? '';
    $texts = $link['text']  ?? [];

    $link['link'] = type_route($type, $value);
    $link['text'] = type_label($type, $value, $texts);

    return $link;
}

/**
 * 是否访问的后端
 * @return bool
 */
function is_admin(): bool
{
    $adminName = admin_name();
    $uri       = request()->getRequestUri();
    if (Str::startsWith($uri, "/{$adminName}")) {
        return true;
    }

    return false;
}

/**
 * 是否访问安装页面
 * @return bool
 */
function is_installer(): bool
{
    $uri = request()->getRequestUri();

    return Str::startsWith($uri, '/installer');
}

/**
 * 是否为当前访问路由
 *
 * @param $routeName
 * @return bool
 */
function equal_route($routeName): bool
{
    if (is_array($routeName)) {
        return in_array(Route::getCurrentRoute()->getName(), $routeName);
    }

    return $routeName == Route::getCurrentRoute()->getName();
}

/**
 * 获取后台当前登录用户
 *
 * @return mixed
 */
function current_user(): ?AdminUser
{
    $user = auth()->guard(AdminUser::AUTH_GUARD)->user();
    if (empty($user)) {
        $user = registry('admin_user');
    }

    return $user;
}

/**
 * 获取前台当前登录客户
 *
 * @return mixed
 */
function current_customer(): ?Customer
{
    return auth()->guard(Customer::AUTH_GUARD)->user();
}

/**
 * 获取语言列表
 *
 * @return array
 */
function locales(): array
{
    $locales = LanguageRepo::enabled()->toArray();

    return array_map(function ($item) {
        return [
            'name' => $item['name'],
            'code' => $item['code'],
        ];
    }, $locales);
}

/**
 * 获取当前语言
 *
 * @return string
 */
function locale(): string
{
    if (is_admin()) {
        $locales    = collect(locales())->pluck('code');
        $userLocale = current_user()->locale;

        return ($locales->contains($userLocale)) ? $userLocale : 'en';
    }

    return Session::get('locale') ?? system_setting('base.locale');
}

/**
 * 获取后台当前语言
 *
 * @return string
 */
function admin_locale(): string
{
    if (is_admin()) {
        return current_user()->locale;
    }

    return locale();
}

/**
 * 货币格式化
 *
 * @param $price
 * @param string $currency
 * @param string $value
 * @param bool   $format
 * @return string
 */
function currency_format($price, string $currency = '', string $value = '', bool $format = true): string
{
    if (! $currency) {
        $currency = is_admin() ? system_setting('base.currency') : current_currency_code();
    }

    return CurrencyService::getInstance()->format($price, $currency, $value, $format);
}

/**
 * 获取指定货币汇率
 *
 * @return string
 */
function current_currency_rate(): float
{
    $currency = current_currency_code();

    return Currency::query()->where('code', $currency)->value('value') ?? 1;
}

/**
 * 时间格式化
 *
 * @param null $datetime
 * @return false|string
 */
function time_format($datetime = null)
{
    $format = 'Y-m-d H:i:s';
    if ($datetime instanceof Illuminate\Support\Carbon) {
        return $datetime->format($format);
    } elseif (is_int($datetime)) {
        return date($format, $datetime);
    }

    return date($format);
}

/**
 * 获取插件根目录
 *
 * @param string $path
 * @return string
 */
function plugin_path(string $path = ''): string
{
    return base_path('plugins') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
}

/**
 * @param $code
 * @return Plugin|null
 */
function plugin($code): ?Plugin
{
    return app('plugin')->getPlugin($code);
}

/**
 * 插件图片缩放
 *
 * @param $pluginCode
 * @param $image
 * @param int $width
 * @param int $height
 * @return mixed|void
 * @throws Exception
 */
function plugin_resize($pluginCode, $image, int $width = 100, int $height = 100)
{
    if (Str::startsWith($image, 'http')) {
        return $image;
    }

    $plugin        = plugin($pluginCode);
    $pluginDirName = $plugin->getDirname();

    return (new \Beike\Services\ImageService($image))->setPluginDirName($pluginDirName)->resize($width, $height);
}

/**
 * Get origin image from plugin
 *
 * @param $pluginCode
 * @param $image
 * @return mixed|void
 * @throws Exception
 */
function plugin_origin($pluginCode, $image)
{
    if (Str::startsWith($image, 'http')) {
        return $image;
    }

    $plugin        = plugin($pluginCode);
    $pluginDirName = $plugin->getDirname();

    return (new \Beike\Services\ImageService($image))->setPluginDirName($pluginDirName)->originUrl();
}

/**
 * 图片缩放
 *
 * @param $image
 * @param int $width
 * @param int $height
 * @return mixed|void
 * @throws Exception
 */
function image_resize($image, int $width = 100, int $height = 100)
{
    if (Str::startsWith($image, 'http')) {
        return $image;
    }

    return (new \Beike\Services\ImageService($image))->resize($width, $height);
}

/**
 * 获取原图地址
 * @throws Exception
 */
function image_origin($image)
{
    if (Str::startsWith($image, 'http')) {
        return $image;
    }

    return (new \Beike\Services\ImageService($image))->originUrl();
}

/**
 * 获取后台开启的所有语言
 *
 * @return Collection
 */
function languages(): Collection
{
    return LanguageRepo::enabled()->pluck('code');
}

/**
 * 当前语言名称
 *
 * @return string
 */
function current_language()
{
    $code = locale();

    return Language::query()->where('code', $code)->first();
}

/**
 * 获取后台所有语言包列表
 *
 * @return array
 */
function admin_languages(): array
{
    $packages       = language_packages();
    $adminLanguages = collect($packages)->filter(function ($package) {
        return file_exists(resource_path("lang/{$package}/admin"));
    })->toArray();

    return array_values($adminLanguages);
}

/**
 * 获取语言包列表
 * @return array
 */
function language_packages(): array
{
    $languageDir = resource_path('lang');

    return array_values(array_diff(scandir($languageDir), ['..', '.']));
}

/**
 * @return Builder[]|\Illuminate\Database\Eloquent\Collection
 */
function currencies()
{
    return CurrencyRepo::listEnabled();
}

/**
 * 获取当前货币
 *
 * @return string
 */
function current_currency_code(): string
{
    return Session::get('currency') ?? system_setting('base.currency');
}

/**
 * 获取当前货币
 *
 * @return string
 */
function current_currency_id(): string
{
    $currencyCode = current_currency_code();
    $currency     = \Beike\Models\Currency::query()->where('code', $currencyCode)->first();

    return $currency->id ?? 0;
}

/**
 * 数量格式化, 用于商品、订单统计
 *
 * @param $quantity
 * @return mixed|string
 */
function quantity_format($quantity)
{
    if ($quantity > 1000000000000) {
        return round($quantity / 1000000000000, 1) . 'T';
    } elseif ($quantity > 1000000000) {
        return round($quantity / 1000000000, 1) . 'B';
    } elseif ($quantity > 1000000) {
        return round($quantity / 1000000, 1) . 'M';
    } elseif ($quantity > 1000) {
        return round($quantity / 1000, 1) . 'K';
    }

    return $quantity;

}

/**
 * 返回json序列化结果
 */
function json_success($message, $data = [])
{
    return [
        'status'  => 'success',
        'message' => $message,
        'data'    => $data,
    ];

}

/**
 * 返回json序列化结果
 */
function json_fail($message, $data = [], $status = 422): JsonResponse
{
    $data = [
        'status'  => 'fail',
        'message' => $message,
        'data'    => $data,
    ];

    return response()->json($data, $status);
}

if (! function_exists('sub_string')) {
    /**
     * @param $string
     * @param int    $length
     * @param string $dot
     * @return string
     */
    function sub_string($string, int $length = 16, string $dot = '...'): string
    {
        $strLength = mb_strlen($string);
        if ($length <= 0) {
            return $string;
        } elseif ($strLength <= $length) {
            return $string;
        }

        return mb_substr($string, 0, $length) . $dot;
    }
}

/**
 * 根据 $builder 对象输出SQL语句
 * @param mixed $builder
 * @return string|string[]|null
 */
function to_sql($builder): array|string|null
{
    $sql = $builder->toSql();
    foreach ($builder->getBindings() as $binding) {
        $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
        $sql   = preg_replace('/\?/', $value, $sql, 1);
    }

    return $sql;
}

/**
 * 递归创建文件夹
 * @param $directoryPath
 */
function create_directories($directoryPath)
{
    $path        = '';
    $directories = explode('/', $directoryPath);
    foreach ($directories as $directory) {
        $path = $path . '/' . $directory;
        if (! is_dir(public_path($path))) {
            @mkdir(public_path($path), 0755);
        }
    }
}

/**
 * 是否安装 debugbar
 *
 * @return bool
 */
function has_debugbar(): bool
{
    return class_exists(\Barryvdh\Debugbar\Facades\Debugbar::class);
}

/**
 * PHP 代码 hook filter 埋点
 *
 * @param $hookKey
 * @param $hookValue
 * @return mixed
 */
function hook_filter($hookKey, $hookValue): mixed
{
    if (config('app.debug') && has_debugbar()) {
        Debugbar::log('HOOK === hook_filter: ' . $hookKey);
    }

    return Eventy::filter($hookKey, $hookValue);
}

/**
 * PHP 代码 hook action 埋点
 *
 * @param $hookKey
 * @param $hookValue
 */
function hook_action($hookKey, $hookValue)
{
    if (config('app.debug') && has_debugbar()) {
        Debugbar::log('HOOK === hook_action: ' . $hookKey);
    }
    Eventy::action($hookKey, $hookValue);
}

/**
 * 添加 Filter, 执行 PHP 逻辑
 *
 * @param $hookKey
 * @param $callback
 * @param int $priority
 * @param int $arguments
 * @return mixed
 */
function add_hook_filter($hookKey, $callback, int $priority = 20, int $arguments = 1): mixed
{
    return Eventy::addFilter($hookKey, $callback, $priority, $arguments);
}

/**
 * 添加 Action, 执行 PHP 逻辑
 *
 * @param $hookKey
 * @param $callback
 * @param int $priority
 * @param int $arguments
 */
function add_hook_action($hookKey, $callback, int $priority = 20, int $arguments = 1)
{
    Eventy::addAction($hookKey, $callback, $priority, $arguments);
}

/**
 * 采用 Hook 修改 Blade 代码
 *
 * @param $hookKey
 * @param $callback
 * @param int $priority
 */
function add_hook_blade($hookKey, $callback, int $priority = 0)
{
    Hook::listen($hookKey, $callback, $priority);
}

/**
 * 检测系统是否已安装
 *
 * @return bool
 */
function installed(): bool
{
    return file_exists(storage_path('installed'));
}

/**
 * 是否为移动端访问
 *
 * @return bool
 */
function is_mobile(): bool
{
    return (new \Jenssegers\Agent\Agent())->isMobile();
}

/**
 * 当前访问协议是否为 https
 *
 * @return bool
 */
function is_secure(): bool
{
    if (! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        return true;
    } elseif (! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT']) === 443) {
        return true;
    } elseif (isset($_SERVER['REQUEST_SCHEME']) && strtolower($_SERVER['REQUEST_SCHEME']) === 'https') {
        return true;
    }

    return false;
}

/**
 * 每页商品显示数量
 *
 * @return int
 */
function perPage(): int
{
    return (int) system_setting('base.product_per_page', 20);
}

/**
 * @param $key
 * @param $value
 * @param bool $force
 */
function register($key, $value, bool $force = false)
{
    \Beike\Libraries\Registry::set($key, $value, $force);
}

/**
 * @param $key
 * @param null $default
 * @return mixed
 */
function registry($key, $default = null): mixed
{
    return \Beike\Libraries\Registry::get($key, $default);
}

/**
 * Check domain ha license.
 * 删除版权信息, 请先购买授权 https://beikeshop.com/vip/subscription
 *
 * @return bool
 */
function check_license(): bool
{
    $configLicenceCode = system_setting('base.license_code');
    $appDomain         = config('app.url');
    $domain            = new \Utopia\Domains\Domain($appDomain);
    $registerDomain    = $domain->getRegisterable();
    if (empty($registerDomain)) {
        return true;
    }

    return $configLicenceCode == md5(mb_substr(md5($registerDomain), 2, 8));
}
