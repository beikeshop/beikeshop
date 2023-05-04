<?php
const customer_left = [
    "url" =>"/admin/customers",
//    "customer_list"=>"客户列表",//客户列表
//    "customer_group"=>"客户组",//客户组
//    "re_station"=>"回收站",//回收站
    "customer_list"=>".list-unstyled.navbar-nav:nth-child(2) li:nth-child(1)",//客户列表
    "customer_group"=>".list-unstyled.navbar-nav:nth-child(2) li:nth-child(2)",//客户组
    "re_station"=>".list-unstyled.navbar-nav:nth-child(2) li:nth-child(3)",//回收站
];
const cre_customer = [
    "name" =>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(1) > div > div > input",
    "email"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(2) > div > div > input",
    "pwd"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(3) > div > div > input",
    "customer_group"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(4) > div > div > div > span > span > i",
    "state"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(5) > div",
    "save_btn"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(6) > div > button.el-button.el-button--primary",
];
const alter_customer = [
    "name" =>"#pane-customer > div > div:nth-child(1) > div > div > input",
    "email"=>"#pane-customer > div > div:nth-child(2) > div > div > input",
    "pwd"=>"#pane-customer > div > div:nth-child(3) > div > div > input",
    "customer_group"=>"#pane-customer > div > div:nth-child(4) > div > div > div.el-input.el-input--suffix > span > span > i",
    "state"=>"#pane-customer > div > div:nth-child(5) > div > div > span",
    "save_btn"=>"#pane-customer > div > div:nth-child(6) > div > button",

];
const customer_list = [
    //创建客户
    "cre_customer"=>"#customer-app > div.card-body > div.d-flex.justify-content-between.mb-4 > button",
    //编辑客户
    "edit_customer" =>"#customer-app > div.card-body > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(8) > a",
    //删除客户
    "del_customer" =>"#customer-app > div.card-body > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(8) > button",
    "get_assert"=>"#customer-app > div.card-body > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(2)",
    "sure_btn"=>"确定",

];
const customer_group = [
    //创建客户组
    "cre_cus_group"=>"#customer-app > div.card-body > div.d-flex.justify-content-between.mb-4 > button",
    //编辑客户组
    "edit_cus_group" =>".btn.btn-outline-secondary.btn-sm",
    //删除客户组
    "del_cus_group" =>".btn.btn-outline-danger.btn-sm.ml-1",
    "get_assert"=>"#customer-app > div.card-body > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(2)",
    "sure_btn"=>"确定",

];
const cre_cus_group = [
    "ch_group_name" =>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div.el-form-item.language-inputs.is-required > div > div:nth-child(1) > div > div > input",
    "en_group_name"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div.el-form-item.language-inputs.is-required > div > div:nth-child(2) > div > div > input",
    "ch_description"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(2) > div > div:nth-child(1) > div > div > input",
    "en_description"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(2) > div > div:nth-child(2) > div > div > input",
    "discount"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(3) > div > div > input",
    "save_btn"=>"#customer-app > div.el-dialog__wrapper > div > div.el-dialog__body > form > div:nth-child(4) > div > div > button.el-button.el-button--primary",
];
const empty_recycle = [
    "empty_btn"=>"#customer-app > div.card-body > div.d-flex.justify-content-between.mb-4 > button",//清空数据按钮
    "recycle_btn"=>"#customer-app > div.card-body > div.table-push > table > tbody > tr > td:nth-child(8) > a",//恢复按钮
    "recycle_del"=>"#customer-app > div.card-body > div.table-push > table > tbody > tr > td:nth-child(8) > button",//删除按钮
    //获取即将被删除的客户email
    "customer_text"=>"#customer-app > div.card-body > div.table-push > table > tbody > tr > td:nth-child(2)",
    "sure_btn"=>"确定",
    "assert_text"=>"暂无数据～",

];


