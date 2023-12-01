<?php

namespace Tests\Data\Catalog;

class CheckoutPage
{
    public const Checkout = [
        'url'    => '/checkout', //
        'submit' => '#submit-checkout', //提交按钮
        //订单成功验证信息
        'assert' => 'Order placed successfully, please pay',
        //订单号
        'order_num'         => '.fw-bold',
        'product_price'     => '.price.text-end',
        'quantity'          => '.quantity', //购买商品数量
        'product_total'     => '.totals li:nth-child(1) span:nth-child(2)', //商品总价
        'shipping_fee'      => '.totals li:nth-child(2) span:nth-child(2)', //运费
        'customer_discount' => '.totals li:nth-child(3) span:nth-child(2)', //折扣金额
        'order_total'       => '.totals li:nth-child(4) span:nth-child(2)', //实际金额
        'view_order'        => '.table.table-borderless tbody tr:nth-of-type(2) td:nth-of-type(2) a',
        'method_pay'        => '.radio-line-item',

    ];
}
