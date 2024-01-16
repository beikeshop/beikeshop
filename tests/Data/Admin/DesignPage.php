<?php

namespace Tests\Data\Admin;

class DesignPage
{
    public const Article_Left = [
        'url'           => '/Admin/themes',
        'temp_set'      => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(1)', //模版设置
        'navigate_set'  => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(2)', //导航设置
        'home_decorate' => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(3)', //首页装修
        'end_decorate'  => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(4)', //页尾装修
    ];

    public const Article_Common = [
        'add_btn'  => '#content > div.container-fluid.p-0 > div > div > div.d-flex.justify-content-between.mb-4 > a', //添加按钮
        'edit_btn' => '#content > div.container-fluid.p-0 > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > a', //编辑按钮
        'del_btn'  => '#content > div.container-fluid.p-0 > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > button', //删除按钮

    ];
}
