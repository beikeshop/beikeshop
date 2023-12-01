<?php

namespace Tests\Data\Catalog;

class IndexPage
{
    public const Index = [
        'login_url'           => '/',
        'product_img'         => '#tab-product-s6e7e3vucriazzbi-0 > div > div:nth-child(1) > div > div.image > a > div > img', //购买商品图标
        'buy_btn'             => '#product-top > div:nth-child(2) > div > div.quantity-btns > button.btn.btn-dark.ms-3.fw-bold', //购买按钮
        'address_btn'         => '#checkout-address-app > div.checkout-black > div.addresses-wrap > div > div > div > button', //添加地址
        'login_text'          => 'Home',
        'right_icon'          => '.navbar-nav flex-row',
        'top_Sports'          => '.navbar-nav.mx-auto li:nth-child(1)',
        'top_Fashion'         => '.navbar-nav.mx-auto li:nth-child(2)',
        'top_Digital'         => '.navbar-nav.mx-auto li:nth-child(3)',
        'top_Hot'             => '.navbar-nav.mx-auto li:nth-child(4)',
        'top_Brand'           => '.navbar-nav.mx-auto li:nth-child(5)',
        'top_Latest_Products' => '.navbar-nav.mx-auto li:nth-child(6)',
    ];

    public const Index_Top = [
        //    "wishlist_btn" => "",//收藏商品图标
        'wishlist_btn' => '.navbar-nav.flex-row li:nth-child(2)', //收藏商品图标

        'buy_btn'     => '#product-top > div:nth-child(2) > div > div.quantity-btns > button.btn.btn-dark.ms-3.fw-bold', //购买按钮
        'address_btn' => '#checkout-address-app > div.checkout-black > div.addresses-wrap > div > div > div > button', //添加地址
        'login_text'  => 'Home',

    ];

    public const Index_Cart = [
        'cart_product_text' => '#offcanvas-right-cart > div.offcanvas-body.pt-0 > div > div > div.product-info.d-flex.align-items-center > div.right.flex-grow-1 > a',
        'cart_icon'         => '.nav-link.position-relative', //购物车图标
        'product_text'      => '#offcanvas-right-cart > div.offcanvas-body.pt-0 > div > div > div.product-info.d-flex.align-items-center > div.right.flex-grow-1 > a', //购物车内商品名字
        'Delete_btn'        => '#offcanvas-right-cart > div.offcanvas-body.pt-0 > div > div > div.product-info.d-flex.align-items-center > div.right.flex-grow-1 > div.product-bottom.d-flex.justify-content-between.align-items-center > span', //删除按钮
        'product_num'       => '#offcanvas-right-cart > div.offcanvas-footer > div.d-flex.justify-content-between.align-items-center.mb-2.p-3.bg-light.top-footer > div:nth-child(2) > strong:nth-child(2) > span',
        'cart_Checkout'     => '#offcanvas-right-cart > div.offcanvas-footer > div.p-4 > a.btn.w-100.fw-bold.btn-dark.to-checkout',
    ];

    public const Index_Account = [
        'login_icon' => '.navbar-nav.flex-row li:nth-child(3)', //登录图标
    ];

    public const Index_Login = [
        'login_icon' => '.navbar-nav.flex-row li:nth-child(3)', //登录图标
    ];
}
