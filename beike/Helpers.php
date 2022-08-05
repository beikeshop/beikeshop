<?php

use Beike\Models\Customer;
use Beike\Models\Language;
use Beike\Models\AdminUser;
use Beike\Repositories\CurrencyRepo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Beike\Services\CurrencyService;
use Beike\Repositories\LanguageRepo;
use TorMorten\Eventy\Facades\Eventy;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
    $locales = [];
    $locales[] = [
        'name' => '中文简体',
        'code' => 'zh_cn',
    ];
    $locales[] = [
        'name' => 'English',
        'code' => 'en',
    ];

    return $locales;
}

/**
 * 获取当前语言
 *
 * @return string
 */
function locale(): string
{
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
        $currency = current_currency_code();
    }
    return CurrencyService::getInstance()->format($price, $currency, $value, $format);
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
function current_language(): string
{
    $code = locale();
    return Language::query()->where('code', $code)->first()->name;
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
    return CurrencyRepo::all()->where('status', true);
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
    $result = [
        'status' => 'success',
        'message' => $message,
        'data' => $data,
    ];
    return $result;
}

/**
 * 返回json序列化结果
 */
function json_fail($message, $data = []): array
{
    $result = [
        'status' => 'fail',
        'message' => $message,
        'data' => $data,
    ];
    return $result;
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
