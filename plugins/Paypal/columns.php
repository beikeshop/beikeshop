<?php

/**
 * Stripe 字段
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-06-29 21:16:23
 * @modified   2022-06-29 21:16:23
 */

return [
    [
        'name'        => 'api_mode',
        'label_key'   => 'setting.api_mode',
        'type'        => 'select',
        'value'       => 'rest',
        'options'     => [
            ['value' => 'rest', 'label_key' => 'setting.api_mode_rest'],
            ['value' => 'nvp', 'label_key' => 'setting.api_mode_nvp'],
        ],
        'required'    => true,
        'description_key' => 'setting.api_mode_desc',
    ],
    [
        'name'        => 'sandbox_client_id',
        'label'       => 'Sandbox Client ID',
        'type'        => 'string',
        'required'    => true,
        'description' => '沙盒模式 Client ID',
    ],
    [
        'name'        => 'sandbox_secret',
        'label'       => 'Sandbox Secret',
        'type'        => 'string',
        'required'    => true,
        'description' => '沙盒模式 Secret',
    ],
    [
        'name'        => 'live_client_id',
        'label'       => 'Live Client ID',
        'type'        => 'string',
        'required'    => true,
        'description' => '正式环境 Client ID',
    ],
    [
        'name'        => 'live_secret',
        'label'       => 'Live Secret',
        'type'        => 'string',
        'required'    => true,
        'description' => '正式环境 Secret',
    ],
    [
        'name'        => 'currency',
        'label'       => 'Currency Code',
        'type'        => 'string',
        'required'    => true,
        'rules'       => 'required|size:3',
        'description' => '结算货币代码',
    ],
    [
        'name'        => 'sandbox_mode',
        'label_key'   => 'setting.sandbox_mode',
        'type'        => 'select',
        'options'     => [
            ['value' => '1', 'label_key' => 'setting.enabled'],
            ['value' => '0', 'label' => '关闭'],
        ],
        'required'    => true,
        'description' => '',
    ],
    [
        'name'        => 'sandbox_api_username',
        'label_key'   => 'setting.sandbox_api_username',
        'type'        => 'string',
        'required'    => false,
        'description_key' => 'setting.nvp_credentials_desc',
    ],
    [
        'name'        => 'sandbox_api_password',
        'label_key'   => 'setting.sandbox_api_password',
        'type'        => 'string',
        'required'    => false,
        'description_key' => 'setting.nvp_credentials_desc',
    ],
    [
        'name'        => 'sandbox_api_signature',
        'label_key'   => 'setting.sandbox_api_signature',
        'type'        => 'string',
        'required'    => false,
        'description_key' => 'setting.nvp_credentials_desc',
    ],
    [
        'name'        => 'live_api_username',
        'label_key'   => 'setting.live_api_username',
        'type'        => 'string',
        'required'    => false,
        'description_key' => 'setting.nvp_credentials_desc',
    ],
    [
        'name'        => 'live_api_password',
        'label_key'   => 'setting.live_api_password',
        'type'        => 'string',
        'required'    => false,
        'description_key' => 'setting.nvp_credentials_desc',
    ],
    [
        'name'        => 'live_api_signature',
        'label_key'   => 'setting.live_api_signature',
        'type'        => 'string',
        'required'    => false,
        'description_key' => 'setting.nvp_credentials_desc',
    ],
];
