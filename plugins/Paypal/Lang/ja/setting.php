<?php

/**
 * Lang.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-09-09 09:09:09
 * @modified   2023-09-07 08:46:48
 */

return [
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
    'enabled'      => '開きます',
    'sandbox_mode' => 'サンドボックス方式です',
];
