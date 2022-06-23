<?php

function admin_route($route, $params = []): string
{
    return route('admin.' . $route, $params);
}

function shop_route($route, $params = []): string
{
    return route('shop.' . $route, $params);
}

function logged_admin_user()
{
    return auth()->guard(\Beike\Models\AdminUser::AUTH_GUARD)->user();
}

function thumbnail($path): string
{
    return $path;
}

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

