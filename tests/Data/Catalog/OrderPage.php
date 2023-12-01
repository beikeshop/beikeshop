<?php

namespace Tests\Data\Catalog;

class OrderPage
{
    public const Order = [
        'login_url'   => '/login',
        'product'     => '#tab-product-0 > div > div:nth-child(1) > div > div.image > a > div > img', //购买商品
        'buy_btn'     => '#product-top > div:nth-child(2) > div > div.quantity-btns > button.btn.btn-dark.ms-3.fw-bold', //购买按钮
        'address_btn' => '#checkout-address-app > div.checkout-black > div.addresses-wrap > div > div > div > button', //添加地址
        'login_text'  => 'Home',
    ];

    public const Order_Status = [
        'Unpaid'    => 'Unpaid', //待支付
        'Paid'      => 'Paid', //已支付
        'Shipped'   => 'Shipped', //已发货
        'Completed' => 'Completed', //已完成  Cancelled
        'Cancelled' => 'Cancelled', //已完成  Cancelled
    ];

    public const Get_Order_Status = [
        'status_text' => '.table.table-borderless.mb-0 tbody tr:first-child td:nth-child(3)', //获取当前状态
    ];

    public const Paypal_Plugin = [
        'Paypal_iframe'  => '#paypal-button-container',
        'Paypal_foot'    => '.css-ltr-1ccpklf',
        'Paypal_Login'   => '登录', //获取当前状态
        'Paypal_Email'   => '#email',
        'Next_Btn'       => '#btnNext',
        'Paypal_Pwd'     => '#password',
        'Login_Btn'      => '#btnLogin',
        'Payment_Method' => '#hermione-container > div > main > div.PaymentOptions_container_1ELkE > section > div:nth-child(3) > div:nth-child(1) > div > div.FundingInstrument_item_3lQ2z > div.VerticalRadioButton_container_3W21c > div > label > span > span',
        'Submit_Btn'     => '#payment-submit-btn',

    ];

    public const Stripe_Plugin = [
        'Cardholder_Name' => '#card-cardholder-name > input', //Cardholder Name
        'Card_Number'     => '.InputElement.is-empty.Input.Input--empty', //Card Number
        'Expiration_Date' => '#root > form > span:nth-child(4) > span > span > input',
        'CVV'             => '#root > form > span:nth-child(4) > span > span > input',
        'Submit_Btn'      => '.btn.btn-primary.btn-lg',
        'Assert_Test'     => 'Thank you for your order!',

    ];
}
