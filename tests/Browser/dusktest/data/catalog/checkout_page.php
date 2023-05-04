<?php


const checkout = [
    'url' => '/checkout',//提交按钮
    'submit' => 'Submit Order',//提交按钮
    //订单成功验证信息
    'assert' => 'Congratulations, the order was successfully generated!',
    //订单号
    'order_num'=>'.fw-bold',
//    'view_order'=>'View Order ',
    'view_order'=>'.table.table-borderless tbody tr:nth-of-type(2) td:nth-of-type(2) a',
    'method_pay'=>'.radio-line-item',

];
