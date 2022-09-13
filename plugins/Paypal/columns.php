<?php
/**
 * Stripe 字段
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-29 21:16:23
 * @modified   2022-06-29 21:16:23
 */

return [
    [
        'name' => 'sandbox_client_id',
        'label' => 'Sandbox Client ID',
        'type' => 'string',
        'required' => true,
        'rules' => 'required|size:80',
        'description' => '沙盒模式 Client ID',
    ],
    [
        'name' => 'sandbox_secret',
        'label' => 'Sandbox Secret',
        'type' => 'string',
        'required' => true,
        'rules' => 'required|size:80',
        'description' => '沙盒模式 Secret',
    ],
    [
        'name' => 'live_client_id',
        'label' => 'Live Client ID',
        'type' => 'string',
        'required' => true,
        'rules' => 'required|size:80',
        'description' => '正式环境 Client ID',
    ],
    [
        'name' => 'live_secret',
        'label' => 'Live Secret',
        'type' => 'string',
        'required' => true,
        'rules' => 'required|size:80',
        'description' => '正式环境 Secret',
    ],
    [
        'name' => 'sandbox_mode',
        'label_key' => 'setting.sandbox_mode',
        'type' => 'select',
        'options' => [
            ['value' => '1', 'label_key' => 'setting.enabled'],
            ['value' => '0', 'label' => '关闭']
        ],
        'required' => true,
        'description' => '',
    ]
];
