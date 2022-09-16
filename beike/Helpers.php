<?php

use Beike\Models\Customer;
use Beike\Models\Language;
use Beike\Models\AdminUser;
use Illuminate\Support\Str;
use Beike\Repositories\PageRepo;
use Beike\Repositories\BrandRepo;
use Illuminate\Support\Collection;
use Beike\Repositories\ProductRepo;
use Beike\Services\CurrencyService;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\CurrencyRepo;
use Beike\Repositories\LanguageRepo;
use TorMorten\Eventy\Facades\Eventy;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;

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
 * 获取后台管理前缀名称, 默认为 admin
 */
function admin_name(): string
{
    if ($envAdminName = env('ADMIN_NAME')) {
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
    if (is_installer() || !file_exists(__DIR__ . '/../storage/installed')) {
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
 * 获取插件链接
 *
 * @param $route
 * @param mixed $params
 * @return string
 */
function plugin_route($route, $params = []): string
{
    return route('plugin.' . $route, $params);
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
    $types = ['category', 'product', 'brand', 'page', 'order', 'rma', 'static', 'custom'];
    if (empty($type) || empty($value) || !in_array($type, $types)) {
        return '';
    }
    if (is_array($value)) {
        throw new \Exception('Value must be integer, string or object');
    }

    if ($type == 'category') {
        return shop_route('categories.show', ['category' => $value]);
    } elseif ($type == 'product') {
        return shop_route('products.show', ['product' => $value]);
    } elseif ($type == 'brand') {
        return shop_route('brands.show', [$value]);
    } elseif ($type == 'page') {
        return shop_route('pages.show', ['page' => $value]);
    } elseif ($type == 'order') {
        return shop_route('account.order.show', ['number' => $value]);
    } elseif ($type == 'rma') {
        return shop_route('account.rma.show', ['id' => $value]);
    } elseif ($type == 'static') {
        return shop_route($value);
    } elseif ($type == 'custom') {
        return $value;
    }
    return '';
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
    $types = ['category', 'product', 'brand', 'page', 'static', 'custom'];
    if (empty($type) || empty($value) || !in_array($type, $types)) {
        return '';
    }

    $locale = locale();
    $text = $texts[$locale] ?? '';
    if ($text) {
        return $text;
    }

    if ($type == 'category') {
        return CategoryRepo::getName($value);
    } elseif ($type == 'product') {
        return ProductRepo::getName($value);
    } elseif ($type == 'brand') {
        return BrandRepo::getName($value);
    } elseif ($type == 'page') {
        return PageRepo::getName($value);
    } elseif ($type == 'static') {
        return trans('shop/' . $value);
    } elseif ($type == 'custom') {
        return $text;
    }
    return '';
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
    $type = $link['type'] ?? '';
    $value = $link['value'] ?? '';
    $texts = $link['text'] ?? [];

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
    $uri = request()->getRequestUri();
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
    return Str::startsWith($uri, "/installer");
}


/**
 * 是否为当前访问路由
 *
 * @param $routeName
 * @return bool
 */
function equal_route($routeName): bool
{
    return $routeName == Route::getCurrentRoute()->getName();
}

/**
 * 获取后台当前登录用户
 *
 * @return mixed
 */
function current_user(): ?AdminUser
{
    return auth()->guard(AdminUser::AUTH_GUARD)->user();
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
 * 获取缩略图
 *
 * @param $path
 * @return string
 */
function thumbnail($path): string
{
    return $path;
}

/**
 * 获取语言列表
 *
 * @return array
 */
function locales(): array
{
    $locales = LanguageRepo::enabled()->toArray();
    $locales = array_map(function ($item) {
        return [
            'name' => $item['name'],
            'code' => $item['code']
        ];
    }, $locales);

    return $locales;
}

/**
 * 获取当前语言
 *
 * @return string
 */
function locale(): string
{
    if (is_admin()) {
        return current_user()->locale ?? 'en';
    }
    return Session::get('locale') ?? system_setting('base.locale');
}

/**
 * 货币格式化
 *
 * @param $price
 * @param string $currency
 * @param string $value
 * @param bool $format
 * @return string
 */
function currency_format($price, string $currency = '', string $value = '', bool $format = true): string
{
    if (!$currency) {
        $currency = is_admin() ? system_setting('base.currency') : current_currency_code();
    }
    return CurrencyService::getInstance()->format($price, $currency, $value, $format);
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
    $plugin = app('plugin')->getPlugin($pluginCode);
    if (Str::startsWith($image, 'http')) {
        return $image;
    }
    $pluginDirName = $plugin->getDirname();
    return (new \Beike\Services\ImageService($image))->setPluginDirName($pluginDirName)->resize($width, $height);
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
    $packages = language_packages();
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
    return array_values(array_diff(scandir($languageDir), array('..', '.')));
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
    } else {
        return $quantity;
    }
}

/**
 * 返回json序列化结果
 */
function json_success($message, $data = []): array
{
    return [
        'status' => 'success',
        'message' => $message,
        'data' => $data,
    ];
}

/**
 * 返回json序列化结果
 */
function json_fail($message, $data = []): array
{
    return [
        'status' => 'fail',
        'message' => $message,
        'data' => $data,
    ];
}

if (!function_exists('sub_string')) {
    /**
     * @param $string
     * @param int $length
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
function to_sql($builder)
{
    $sql = $builder->toSql();
    foreach ($builder->getBindings() as $binding) {
        $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
        $sql = preg_replace('/\?/', $value, $sql, 1);
    }
    return $sql;
}

/**
 * 递归创建文件夹
 * @param $directoryPath
 */
function create_directories($directoryPath)
{
    $path = '';
    $directories = explode('/', $directoryPath);
    foreach ($directories as $directory) {
        $path = $path . '/' . $directory;
        if (!is_dir(public_path($path))) {
            @mkdir(public_path($path), 0755);
        }
    }
}

/**
 * hook filter 埋点
 *
 * @param $hookKey
 * @param $hookValue
 * @return mixed
 */
function hook_filter($hookKey, $hookValue)
{
    return Eventy::filter($hookKey, $hookValue);
}

/**
 * hook action 埋点
 *
 * @param $hookKey
 * @param $hookValue
 * @return mixed
 */
function hook_action($hookKey, $hookValue)
{
    Eventy::action($hookKey, $hookValue);
}

/**
 * 添加 Filter
 *
 * @param $hook
 * @param $callback
 * @param int $priority
 * @param int $arguments
 * @return mixed
 */
function add_filter($hook, $callback, int $priority = 20, int $arguments = 1)
{
    return Eventy::addFilter($hook, $callback, $priority, $arguments);
}

/**
 * 添加 Action
 *
 * @param $hook
 * @param $callback
 * @param int $priority
 * @param int $arguments
 */
function add_action($hook, $callback, int $priority = 20, int $arguments = 1)
{
    Eventy::addAction($hook, $callback, $priority, $arguments);
}

/**
 * 检测系统是否已安装
 *
 * @return bool
 */
function installed()
{
    return file_exists(storage_path('installed'));
}

/**
 * 是否为移动端访问
 *
 * @return bool
 */
function is_mobile()
{
    return (new \Jenssegers\Agent\Agent())->isMobile();
}


/**
 * 当前访问协议是否为 https
 *
 * @return bool
 */
function is_secure()
{
    if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
        return true;
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
        return true;
    } elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
        return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && intval($_SERVER['SERVER_PORT']) === 443) {
        return true;
    } elseif (isset($_SERVER['REQUEST_SCHEME']) && strtolower($_SERVER['REQUEST_SCHEME']) === 'https') {
        return true;
    }
    return false;
}
