<?php

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * 获取后台链接
 *
 * @param $route
 * @param array $params
 * @return string
 */
function admin_route($route, array $params = []): string
{
    return route('admin.' . $route, $params);
}

/**
 * 获取前台链接
 *
 * @param $route
 * @param array $params
 * @return string
 */
function shop_route($route, array $params = []): string
{
    return route('shop.' . $route, $params);
}

/**
 * 获取后台当前登录用户
 *
 * @return Authenticatable|null
 */
function logged_admin_user(): ?Authenticatable
{
    return auth()->guard(\Beike\Models\AdminUser::AUTH_GUARD)->user();
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
    return '$' . $price;
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
    if (\Illuminate\Support\Str::startsWith($image, 'http')) {
        return $image;
    }
    return asset($image);
}
