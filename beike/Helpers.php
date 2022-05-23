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

