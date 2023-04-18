<?php
const products_top = [
    "login_url" =>"/admin/products",
    "create_product"=>"#product-app > div > div > div.d-flex.justify-content-between.my-4 > a > button",//创建商品按钮
    //编辑商品按钮
    "edit_product" =>"#product-app > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > a.btn.btn-outline-secondary.btn-sm",
    //删除按钮
    "del_product"=>"#product-app > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > a.btn.btn-outline-danger.btn-sm",//创建商品按钮
    "sure_btn"=>"确定",
    "get_name"=>"#product-app > div > div > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(4) > a",
];
const cre_assert = [
    "cre_ful_assert"=>"创建成功!",
    "alter_ful_assert" =>"更新成功!",
    "del_ful_assert" =>"删除成功!",

];
