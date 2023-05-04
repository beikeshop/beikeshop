<?php
const order_right = [
    "url"=>"/admin/orders",
    "search_order" =>"#app > form > div:nth-child(1) > div:nth-child(1) > div > div > input",//搜索栏--订单号
    "search_bth"=>"#app > div > div > button:nth-child(1)",//搜索按钮
    "view_btn"=>"#customer-app > div > div.table-push > table > tbody > tr > td:nth-child(9) > a",//查看按钮
];
const order_child = [
    "mg_order" =>".list-unstyled.navbar-nav:nth-child(2) li:nth-child(1)",//订单列表
    "mg_sale_after"=>".list-unstyled.navbar-nav:nth-child(2) li:nth-child(2)",//售后管理
    "ca_sale_after"=>".list-unstyled.navbar-nav:nth-child(2) li:nth-child(3)",//售后原因
];
const order_details = [//订单详情页
    "pull_btn"=>"#app > form > div.el-form-item.is-required > div > div > div > span > span > i",//状态栏下拉按钮
//    "paid"=>".el-select-dropdown__item",//已支付
    "paid"=>".el-scrollbar__view.el-select-dropdown__list li:nth-child(1)",//已支付
    "cancel"=>".el-scrollbar__view.el-select-dropdown__list li:nth-child(2)",//已取消
    "alter_btn"=>".el-button.el-button--primary",//更新状态按钮
    "Shipped"=>".el-scrollbar__view.el-select-dropdown__list li:nth-of-type(2)",//已发货
    "express_btn"=>"#app > form > div:nth-child(3) > div > div > div > span > span > i",//快递下拉按钮
    "Completed"=>".el-scrollbar__view.el-select-dropdown__list li:nth-child(1)",//已支付//

    "express_1"=>".el-scrollbar__view.el-select-dropdown__list",//选择第一个快递
    "order_number"=>"#app > form > div:nth-child(4) > div > div > input",//订单号
    "submit"=>"#app > form > div:nth-child(7) > div > button",//提交按钮
    "submit_btn2"=>"#app > form > div:nth-child(5) > div > button",//提交按钮
    //#app > form > div:nth-child(5) > div > button
    ""=>"",
];



