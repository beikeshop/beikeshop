<?php

namespace Tests\Data\Catalog;

class ProductOne
{
    public const Product = [
        'login_url'         => '/products/1',
        'product_1'         => '.btn.btn-dark.ms-3.fw-bold', //购买商品
        'Wishlist_icon'     => '#product-top > div:nth-child(2) > div > div.product-btns > div.add-wishlist > button', //收藏
        'add_cart'          => '.btn.btn-outline-dark.ms-md-3.add-cart.fw-bold',
        'product1_name'     => '#product-top > div:nth-child(2) > div > h1', //产品名字
        'quantity'          => '#product-top > div:nth-child(2) > div > div.product-btns > div.quantity-btns > div > input', //购买商品输入框
        'quantity_up'       => '.bi.bi-chevron-up', //增加数量按钮
        'buy_btn'           => '#product-top > div:nth-child(2) > div > div.quantity-btns > button.btn.btn-dark.ms-3.fw-bold', //购买按钮
        'address_btn'       => '#checkout-address-app > div.checkout-black > div.addresses-wrap > div > div > div > button', //添加地址
        'login_text'        => 'Home',
        'understock_assert' => '.layui-layer-content',
    ];
}
