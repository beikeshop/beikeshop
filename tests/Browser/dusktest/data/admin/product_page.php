<?php
const products_top = [
    "login_url" =>"/admin/products",
    ""=>"",
    "create_product"=>"#product-app > div > div > div.d-flex.justify-content-between.my-4 > a > button",//创建商品按钮
    //编辑商品按钮
    "edit_product" =>"#product-app > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > a.btn.btn-outline-secondary.btn-sm",
    //删除按钮
    "del_product"=>"#product-app > div > div > div.table-push > table > tbody > tr:nth-child(1) > td.text-end > a.btn.btn-outline-danger.btn-sm",//创建商品按钮
    "sure_btn"=>"确定",
    "get_name"=>"#product-app > div > div > div.table-push > table > tbody > tr:nth-child(1) > td:nth-child(4) > a",
];
const products_left = [
    "product_cate"=>".list-unstyled.navbar-nav li:nth-child(1)",//商品分类
    "product_mg"=>".list-unstyled.navbar-nav li:nth-child(2)",//商品管理
    "product_brand"=>".list-unstyled.navbar-nav li:nth-child(3)",//商品品牌
    "attribute_group"=>".list-unstyled.navbar-nav li:nth-child(4)",//属性组
    "attribute"=>".list-unstyled.navbar-nav li:nth-child(5)",//属性
    "Recy_station"=>".list-unstyled.navbar-nav li:nth-child(6)",//回收站
];
const product_cla = [
    "cre_cate_btn"=>"#category-app > div > a",//创建分类按钮

];
const cre_assert = [
    "cre_ful_assert"=>"创建成功!",
    "alter_ful_assert" =>"更新成功!",
    "del_ful_assert" =>"删除成功!",

];
