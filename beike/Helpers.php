<?php

use Beike\Models\Setting;
use Beike\Models\Customer;
use Beike\Models\AdminUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Auth\Authenticatable;

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
 * 获取后台管理前缀名称, 默认为 admin
 */
function admin_name(): string
{
    if ($envAdminName = env('ADMIN_NAME')) {
        return Str::snake($envAdminName);
    } elseif ($settingAdminName = setting('base.admin_name')) {
        return Str::snake($settingAdminName);
    }
    return 'admin';
}

/**
 * 获取后台设置项
 */
function load_settings()
{
    $settings = Setting::all(['type', 'space', 'name', 'value', 'json'])
        ->groupBy('space');

    $result = [];
    foreach ($settings as $space => $groupSettings) {
        $space = $space ?: 'system';
        foreach ($groupSettings as $groupSetting) {
            $name = $groupSetting->name;
            $value = $groupSetting->value;
            if ($groupSetting->json) {
                $result[$space][$name] = json_decode($value, true);
            } else {
                $result[$space][$name] = $value;
            }
        }
    }
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
 * @return Authenticatable|null
 */
function current_user(): ?Authenticatable
{
    return auth()->guard(AdminUser::AUTH_GUARD)->user();
}

/**
 * 获取前台当前登录客户
 *
 * @return Authenticatable|null
 */
function current_customer(): ?Authenticatable
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
    return 'zh_cn';
}

/**
 * 货币格式化
 *
 * @param $price
 * @return string
 */
function currency_format($price): string
{
    return '$' . number_format($price, 2);
}

/**
 * 图片缩放
 *
 * @param $image
 * @param int $width
 * @param int $height
 * @return mixed|void
 */
function image_resize($image, int $width = 100, int $height = 100)
{
    if (Str::startsWith($image, 'http')) {
        return $image;
    }
    return asset($image);
}

/**
 * 当前语言ID
 *
 * @return int
 */
function current_language_id(): int
{
    return 1;
}

/**
 * 获取当前货币
 *
 * @return string
 */
function current_currency_code(): string
{
    return 'CNY';
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
