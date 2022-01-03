<?php

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
