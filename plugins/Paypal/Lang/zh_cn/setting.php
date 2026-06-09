<?php
/**
 * setting.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-11 15:26:18
 * @modified   2022-08-11 15:26:18
 */

return [
    'api_mode'              => 'API 模式',
    'api_mode_desc'         => 'REST 保持现有 PayPal 集成不变；NVP/SOAP 使用旧版 Express Checkout API 凭证。',
    'api_mode_rest'         => 'REST API',
    'api_mode_nvp'          => 'NVP/SOAP API',
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
