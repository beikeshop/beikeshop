<?php

function admin_route($route, $params = []): string
{
    return route('admin.' . $route, $params);
}

function shop_route($route, $params = []): string
{
    return route('shop.' . $route, $params);
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

