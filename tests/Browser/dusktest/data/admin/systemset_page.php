<?php
const common = [
    "save_btn"=>".btn.btn-lg.btn-primary.submit-form"
];

const system_set = [
    "basic_set"=>".nav.nav-tabs.nav-bordered.mb-5 li:nth-child(1)",//基础设置
    "store_set"=>".nav.nav-tabs.nav-bordered.mb-5 li:nth-child(2)",//商店设置
    "pay_set"=>".nav.nav-tabs.nav-bordered.mb-5 li:nth-child(3)",//结账设置
    "images_set"=>".nav.nav-tabs.nav-bordered.mb-5 li:nth-child(4)",//图片设置
    "express_set"=>".nav.nav-tabs.nav-bordered.mb-5 li:nth-child(5)",//快递公司
    "advanced_filter"=>".nav.nav-tabs.nav-bordered.mb-5 li:nth-child(6)",//高级筛选
    "email_set"=>".nav.nav-tabs.nav-bordered.mb-5 li:nth-child(7)",//邮件设置
    "close_visitor_checkout"=>"#tab-checkout > div:nth-child(1) > div > div > div:nth-child(2) > label",//游客结账  禁用
    "open_visitor_checkout"=>"#guest_checkout-1",//游客结账  启用
];
const express_set = [  //快递公司
    "add_btn"=>".bi.bi-plus-circle.cursor-pointer.fs-4",//加号
    "express_company"=>'input[name="express_company[0][name]"]',//公司名字
    "express_code"=>'input[name="express_company[0][code]"]',//code
    "save_btn"=>"#content > div.page-title-box.py-1.d-flex.align-items-center.justify-content-between > div > button",
];
const express_assert = [  //断言信息
    "assert_ful"=>"更新成功!",
];

