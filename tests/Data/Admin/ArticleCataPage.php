<?php

namespace Tests\Data\Admin;

class ArticleCataPage
{
    public const Common = [
        'Save_btn'  => '#content > div.container-fluid.p-0 > div.page-bottom-btns > button.btn.w-min-100.btn-primary.submit-form.btn-lg', //保存按钮
    ];

    public const Top = [
        'Cn'  => '#tab-content > ul > li:nth-child(1) > button', //中文基本信息
        'En'  => '#tab-content > ul > li:nth-child(2) > button', //英文基本信息
    ];

    public const Cn_info = [
        'title'   => '#tab-zh_cn > div:nth-child(1) > div > input', //标题
        'summary' => '#tab-zh_cn > div:nth-child(2) > div > div > textarea', //摘要
    ];

    public const En_info = [
        'title'   => '#tab-en > div:nth-child(1) > div > input', //标题
        'summary' => '#tab-en > div:nth-child(2) > div > div > textarea', //摘要
    ];
}
