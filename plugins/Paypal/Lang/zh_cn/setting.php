<?php

/**
 * setting.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-11 15:26:18
 * @modified   2022-08-11 15:26:18
 */

return [
    'api_mode'              => 'API 模式',
    'api_mode_desc'         => 'REST 保持现有 PayPal 集成不变；NVP/SOAP 使用旧版 Express Checkout API 凭证。',
    'api_mode_rest'         => 'REST API',
    'api_mode_nvp'          => 'NVP/SOAP API',
    'checkout_solution'     => 'PayPal Checkout',
    'choose_api_title'      => '选择 PayPal 接口模式',
    'choose_api_desc'       => '推荐使用 REST API，这是 PayPal 官方建议的新模式；NVP/SOAP 仅用于兼容旧版 Express Checkout 凭证。',
    'rest_recommended'      => '官方推荐',
    'rest_title'            => 'REST API（推荐）',
    'rest_desc'             => '使用 Client ID 和 Secret，适合新商户和长期维护的 PayPal Checkout 集成。',
    'nvp_title'             => 'NVP/SOAP API（旧版兼容）',
    'nvp_desc'              => '使用 API Username、Password、Signature，仅建议已有旧版 Express Checkout 凭证的商户继续使用。',
    'base_settings'         => '基础设置',
    'base_settings_desc'    => '这些设置对两种接口模式都生效。',
    'currency_desc'         => '请输入 3 位 PayPal 结算货币代码，例如 USD、EUR、HKD。',
    'rest_credentials'      => 'REST API 凭证',
    'rest_credentials_desc' => '在 PayPal Developer Dashboard 创建应用后获取 Client ID 和 Secret。',
    'open_paypal_dashboard' => '打开 PayPal 后台',
    'sandbox_credentials'   => '沙盒环境',
    'live_credentials'      => '正式环境',
    'nvp_credentials'       => 'NVP/SOAP API 凭证',
    'legacy_api'            => 'Legacy API',
    'nvp_warning'           => 'NVP/SOAP 属于 PayPal 旧版接口，官方仍支持现有集成，但新集成建议使用 REST API。',
    'sandbox_mode'          => '沙盒模式',
    'enabled'               => '开启',
    'sandbox_api_username'  => '沙盒 API 用户名',
    'sandbox_api_password'  => '沙盒 API 密码',
    'sandbox_api_signature' => '沙盒 API 签名',
    'live_api_username'     => '正式 API 用户名',
    'live_api_password'     => '正式 API 密码',
    'live_api_signature'    => '正式 API 签名',
    'nvp_credentials_desc'  => '仅在 API 模式选择 NVP/SOAP 时需要填写。',
];
