<?php
/**
 * Stripe 字段
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-29 21:16:23
 * @modified   2022-06-29 21:16:23
 */

return [
    [
        'name' => 'sandbox_client_id',
        'label' => 'Sandbox Client ID',
        'type' => 'string',
        'required' => true,
        'description' => '沙盒模式 Client ID',
    ],
    [
        'name' => 'sandbox_Secret',
        'label' => 'Sandbox Secret',
        'type' => 'string',
        'required' => true,
        'description' => '沙盒模式 Secret',
    ],
    [
        'name' => 'live_client_id',
        'label' => 'Live Client ID',
        'type' => 'string',
        'required' => true,
        'description' => '正式环境 Client ID',
    ],
    [
        'name' => 'live_Secret',
        'label' => 'Live Secret',
        'type' => 'string',
        'required' => true,
        'description' => '正式环境 Secret',
    ],
    [
        'name' => 'sandbox_mode',
        'label' => '沙盒模式',
        'type' => 'select',
        'options' => [
            ['value' => '1', 'label' => '开启'],
            ['value' => '0', 'label' => '关闭']
        ],
        'required' => true,
        'description' => '',
    ]
];
