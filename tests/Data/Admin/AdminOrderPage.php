<?php

namespace Tests\Data\Admin;

class AdminOrderPage
{
    public const Right = [
        'url'          => '/Admin/orders',
        'search_order' => '#orders-app > div > div.bg-light.p-4.mb-3 > form > div:nth-child(1) > div:nth-child(1) > div > div > input', //搜索栏--订单号
        'search_bth'   => '#orders-app > div > div.bg-light.p-4.mb-3 > div > div > button:nth-child(1)', //搜索按钮
        'view_btn'     => '#orders-app > div > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(10) > a', //查看按钮
    ];

    public const Child = [
        'mg_order'      => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(1)', //订单列表
        'mg_sale_after' => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(2)', //售后管理
        'ca_sale_after' => '.list-unstyled.navbar-nav:nth-child(2) li:nth-child(3)', //售后原因
        'add_rma_btn'   => '#tax-classes-app > div.card-body.h-min-600 > div > button',
        'zh_name'       => '#tax-classes-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div.el-form-item.language-inputs.is-required > div > div:nth-child(1) > div > div > input',
        'en_name'       => '#tax-classes-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div.el-form-item.language-inputs.is-required > div > div:nth-child(2) > div > div > input',
        'save_btn'      => '#tax-classes-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div.el-form-item.mt-5 > div > button.el-button.el-button--primary > span',
    ];

    public const Details = [//订单详情页
        'pull_btn' => '#app > form > div.el-form-item.is-required > div > div > div > span > span > i', //状态栏下拉按钮
        //    "paid"=>".el-select-dropdown__item",//已支付
        'paid'        => '.el-scrollbar__view.el-select-dropdown__list li:nth-child(1)', //已支付
        'cancel'      => '.el-scrollbar__view.el-select-dropdown__list li:nth-child(2)', //已取消
        'alter_btn'   => '.el-button.el-button--primary', //更新状态按钮
        'Shipped'     => '.el-scrollbar__view.el-select-dropdown__list li:nth-of-type(2)', //已发货
        'express_btn' => '#app > form > div:nth-child(3) > div > div > div > span > span > i', //快递下拉按钮
        'Completed'   => '.el-scrollbar__view.el-select-dropdown__list li:nth-child(1)', //已支付//

        'express_1'    => '.el-scrollbar__view.el-select-dropdown__list', //选择第一个快递
        'order_number' => '#app > form > div:nth-child(4) > div > div > input', //订单号  #orders-app > div > div.bg-light.p-4.mb-3 > form > div:nth-child(1) > div:nth-child(1) > div > div > input
        'submit'       => '#app > form > div:nth-child(7) > div > button', //提交按钮
        'submit_btn2'  => '#app > form > div:nth-child(5) > div > button', //提交按钮
        //#app > form > div:nth-child(5) > div > button

    ];

    public const Rams = [//售后详情页
        'Check_btn'  => '#customer-app > div > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(9) > a', //列表查看按钮
        'Pull_btn'   => '#app > form > div.el-form-item.is-required > div > div > div > span > span', //状态下拉按钮
        'Completed'  => '.el-scrollbar__view.el-select-dropdown__list li:nth-child(5)', //状态已完成
        'Update_btn' => '#app > form > div:nth-child(4) > div > button', //更新按钮

    ];
}
