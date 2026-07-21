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
    'api_mode'              => 'API Mode',
    'api_mode_desc'         => 'REST keeps the existing PayPal integration unchanged. NVP/SOAP uses legacy Express Checkout API credentials.',
    'api_mode_rest'         => 'REST API',
    'api_mode_nvp'          => 'NVP/SOAP API',
    'checkout_solution'     => 'PayPal Checkout',
    'choose_api_title'      => 'Choose PayPal API Mode',
    'choose_api_desc'       => 'REST API is recommended by PayPal for new integrations. NVP/SOAP is kept only for legacy Express Checkout credentials.',
    'rest_recommended'      => 'Recommended',
    'rest_title'            => 'REST API (Recommended)',
    'rest_desc'             => 'Uses Client ID and Secret. Best for new merchants and long-term PayPal Checkout maintenance.',
    'nvp_title'             => 'NVP/SOAP API (Legacy)',
    'nvp_desc'              => 'Uses API Username, Password, and Signature. Use it only when a merchant already depends on legacy Express Checkout credentials.',
    'base_settings'         => 'Base Settings',
    'base_settings_desc'    => 'These settings apply to both API modes.',
    'currency_desc'         => 'Enter a 3-letter PayPal settlement currency code, such as USD, EUR, or HKD.',
    'rest_credentials'      => 'REST API Credentials',
    'rest_credentials_desc' => 'Create an app in PayPal Developer Dashboard to get the Client ID and Secret.',
    'open_paypal_dashboard' => 'Open PayPal Dashboard',
    'sandbox_credentials'   => 'Sandbox',
    'live_credentials'      => 'Live',
    'nvp_credentials'       => 'NVP/SOAP API Credentials',
    'legacy_api'            => 'Legacy API',
    'nvp_warning'           => 'NVP/SOAP is a legacy PayPal API. PayPal still supports existing integrations, but REST API is recommended for new integrations.',
    'sandbox_mode'          => 'Sandbox Mode',
    'enabled'               => 'Enabled',
    'sandbox_api_username'  => 'Sandbox API Username',
    'sandbox_api_password'  => 'Sandbox API Password',
    'sandbox_api_signature' => 'Sandbox API Signature',
    'live_api_username'     => 'Live API Username',
    'live_api_password'     => 'Live API Password',
    'live_api_signature'    => 'Live API Signature',
    'nvp_credentials_desc'  => 'Required only when API Mode is NVP/SOAP.',
];
