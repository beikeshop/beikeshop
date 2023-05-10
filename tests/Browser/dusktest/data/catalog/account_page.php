<?php

const account = [
    'url'         => '/account',
    'go_index'    => '.logo', //beikeshop图标
    'go_account'  => '.list-group-item.d-flex.justify-content-between.align-items-center:nth-child(1)', //
    'go_Edit'     => '.list-group-item.d-flex.justify-content-between.align-items-center:nth-child(2)', //编辑信息
    'go_order'    => '.list-group-item.d-flex.justify-content-between.align-items-center:nth-child(3)', //查看订单
    'go_address'  => '.list-group-item.d-flex.justify-content-between.align-items-center:nth-child(4)', //添加地址
    'go_Wishlist' => '.list-group-item.d-flex.justify-content-between.align-items-center:nth-child(5)', //添收藏
    'go_rma'      => '.list-group-item.d-flex.justify-content-between.align-items-center:nth-child(6)', //售后
    'SignOut'     => '.list-group-item.d-flex.justify-content-between.align-items-center:nth-child(7)', //sign out
];
const address = [
    'login_url'    => '/account/addresses',
    'add_btn'      => '.btn.btn-dark.mb-3', //点击添加地址
    'add_name'     => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(1) > div > div > input', //add_name
    'add_phone'    => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(2) > div > div > input', //add_phone
    'add_country'  => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(3) > div > div > div:nth-child(1) > div > div > div > div.el-input.el-input--suffix > span > span', //选择国家
    'add_address'  => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(3) > div > div > div:nth-child(3) > div > div > div.el-input > input',
    'add_province' => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(3) > div > div > div:nth-child(2) > div > div > div > div.el-input.el-input--suffix > span > span',
    'add_code'     => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(4) > div > div > input', //add_code
    'add_address1' => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(5) > div > div > input', //add_address1
    'add_address2' => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(6) > div > div > input', //add_address2
    'default'      => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(7) > div > div > span', //default
    'save'         => '#address-app > div:nth-child(2) > div > div > div.el-dialog__body > form > div:nth-child(8) > div > button.el-button.el-button--primary', //save
    'assert'       => 'Default Address',
    //addresses
];
const Edit = [
    'login_url'   => '/account/edit',
    'upload_btn'  => '#address-app > div > div.col-12.col-md-9 > div > div.card-body.h-600 > form > div.bg-light.rounded-3.p-4.mb-4 > div > div > label', //上传头像图标
    'Confirm_btn' => 'Confirm',
    'user_name'   => '#address-app > div > div.col-12.col-md-9 > div > div.card-body.h-600 > form > div.row.gx-4.gy-3 > div:nth-child(1) > input', //更改用户名
    'user_email'  => '#address-app > div > div.col-12.col-md-9 > div > div.card-body.h-600 > form > div.row.gx-4.gy-3 > div:nth-child(2) > input', //更改用户email
    'Submit'      => '.btn.btn-primary.mt-sm-0', //add_phone
    'assert'      => 'Modify Success!',
];
const Wishlist = [
    'login_url'       => '/account/edit',
    'go_Wishlist'     => 'Wishlist', //点击Wishlist
    'Check_Details'   => '.btn.btn-dark.btn-sm.add-cart', //查看详情按钮
    'remove_Wishlist' => '.btn.btn-danger.btn-sm.remove-wishlist', //移除按钮

    'no_data' => '.d-flex.flex-column.align-center.align-items-center.mb-4',

];
