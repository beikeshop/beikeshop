<?php

function admin_route($route, $params = []): string
{
    return route('admin.' . $route, $params);
}

function image_thumbnail($path): string
{
    return 'https://dummyimage.com/100.jpg';
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

