<?php

namespace Tests\Data\Admin;

class SystemSetPage
{
    public const Common = [
        'save_btn' => '.btn.btn-lg.btn-primary.submit-form', //保存按钮
    ];

    public const System_Left = [
        'system_set'      => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(1)', //系统设置
        'personal_center' => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(2)', //个人中心
        'admin_user'      => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(3)', //后台用户
        'area_group'      => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(4)', //区域分组
        'ta
    x_rate_set'           => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(5)', //税率设置
        'tax_category'    => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(6)', //税费类别
        'currency_mg'     => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(7)', //货币管理
        'language_mg'     => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(8)', //语言管理
        'state_mg'        => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(9)', //国家管理
        'province_mg'     => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(10)', //省份管理
    ];

    public const System_Set = [
        'basic_set'              => '.nav.nav-tabs.nav-bordered.mb-5 li:nth-child(1)', //基础设置
        'store_set'              => '.nav.nav-tabs.nav-bordered.mb-5 li:nth-child(2)', //商店设置
        'pay_set'                => '.nav.nav-tabs.nav-bordered.mb-5 li:nth-child(3)', //结账设置
        'images_set'             => '.nav.nav-tabs.nav-bordered.mb-5 li:nth-child(4)', //图片设置
        'express_set'            => '.nav.nav-tabs.nav-bordered.mb-5 li:nth-child(5)', //快递公司
        'advanced_filter'        => '.nav.nav-tabs.nav-bordered.mb-5 li:nth-child(6)', //高级筛选
        'email_set'              => '.nav.nav-tabs.nav-bordered.mb-5 li:nth-child(7)', //邮件设置
        'close_visitor_checkout' => '#guest_checkout-0', //游客结账  禁用
        'open_visitor_checkout'  => '#tab-checkout > div:nth-child(2) > div > div > div:nth-child(1)', //游客结账  启用
    ];

    public const System_Express

        = [  //快递公司
            'add_btn'         => '.bi.bi-plus-circle.cursor-pointer.fs-4', //加号
            'express_company' => 'input[name="express_company[0][name]"]', //公司名字
            'express_code'    => 'input[name="express_company[0][code]"]', //code
            'save_btn'        => '#content > div.container-fluid.p-0 > div.page-bottom-btns > button.btn.btn-lg.w-min-100.btn-primary.submit-form',

        ];

    public const Assert = [  //断言信息
        'assert_ful' => '更新成功!',
    ];
}
