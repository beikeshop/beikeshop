<?php

namespace Tests\Data\Admin;

class AdminPage
{
    public const TOP = [
        'login_url'                 => '/admin',
        'root'                      => '.ml-2',
        'mg_index'                  => '.list-unstyled.navbar-nav li:nth-child(1)', //管理首页  .list-unstyled.navbar-nav
        'mg_order'                  => '.list-unstyled.navbar-nav li:nth-child(2)', //管理订单
        'mg_product'                => '.list-unstyled.navbar-nav li:nth-child(3)', //管理商品
        'mg_customers'              => '.list-unstyled.navbar-nav li:nth-child(4)', //管理客户
        'mg_article'                => '.list-unstyled.navbar-nav li:nth-child(5)', //管理文章
        'mg_report'                 => '.list-unstyled.navbar-nav li:nth-child(6)', //报表
        'mg_design'                 => '.list-unstyled.navbar-nav li:nth-child(7)', //设计
        'mg_plugin'                 => '.list-unstyled.navbar-nav li:nth-child(8)', //插件
        'system_set'                => '.list-unstyled.navbar-nav li:nth-child(9)', //系统设置
        'go_catalog'                => '.dropdown-menu.dropdown-menu-end.show li:nth-child(1)', //去往前台
        'personal_center'           => '.dropdown-menu.dropdown-menu-end.show li:nth-child(2)', //个人中心
        'sign_out'                  => '.dropdown-menu.dropdown-menu-end.show li:nth-child(4)', //退出登录
        'Alter'                     => '.navbar.navbar-right li:nth-child(1)', //更新按钮
        'buy_copyright'             => '.navbar.navbar-right li:nth-child(3)', //版权与服务
        'plugins_market'            => '.navbar.navbar-right li:nth-child(4)', //插件市场
        'sw_language'               => '.navbar.navbar-right li:nth-child(5)', //切换语言
        //'sw_language'     => '.navbar.navbar-right li:nth-child(5)', //
        'en_language'     => '.dropdown-menu.dropdown-menu-end.show li:nth-child(2)', //切换英语
        'ch_language'     => '.dropdown-menu.dropdown-menu-end.show li:nth-child(10)', //切换中文
    ];

    public const Assert = [
        'vip_assert'     => 'License',
        'plugins_assert' => '/admin/marketing',
        'en_assert'      => 'Admin Panel', //切换为中文断言
        'ch_assert'      => '后台管理', //切换为英文断言
    ];
}
