<?php
/**
 * order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    'index'                  => 'System Settings',
    'settings_index'         => 'View Setting',
    'settings_update'        => 'Update Setting',
    'design_index'           => 'Design Builder',
    'design_footer_index'    => 'Design Footer',
    'design_menu_index'      => 'Design Menu',
    'product_per_page'       => 'The Number of Per Page',

    'checkout_settings'      => 'checkout settings',
    'basic_settings'         => 'Basic Settings',
    'store_settings'         => 'Store Settings',
    'picture_settings'       => 'Picture Settings',
    'use_queue'              => 'whether to use the queue',
    'mail_settings'          => 'mail settings',
    'mail_engine'            => 'mail engine',
    'smtp_host'              => 'host',
    'smtp_username'          => 'user',
    'smtp_encryption'        => 'encryption method',
    'smtp_encryption_info'   => 'SSL or TLS',
    'smtp_password'          => 'password',
    'smtp_password_info'     => 'Set SMTP password. For Gmail, please refer to: https://security.google.com/settings/security/apppasswords',
    'smtp_port'              => 'port',
    'smtp_timeout'           => 'timeout',
    'sendmail_path'          => 'execution path',
    'mailgun_domain'         => 'domain name',
    'mailgun_secret'         => 'Key',
    'mailgun_endpoint'       => 'port',
    'mail_log'               => 'Description: The log engine is generally used for testing purposes! The email will not be actually sent to the recipient address, and the email content will be saved in `/storage/logs/laravel.log` in the form of a log',
    'express_code_help'      => 'numbers, letters, underscore',

    'guest_checkout'         => 'visitor checkout',
    'theme_default'          => 'Default Theme',
    'theme_black'            => 'Black Theme',
    'shipping_address'       => 'Shipping Address',
    'payment_address'        => 'Billing Address',

    'meta_title'             => 'Meta Title',
    'meta_description'       => 'Meta Description',
    'meta_keywords'          => 'Meta Keyword',
    'telephone'              => 'Contact Phone',
    'email'                  => 'Mailbox',
    'default_address'        => 'Default Address',
    'default_country_set'    => 'Default Country Setting',
    'default_zone_set'       => 'Default Province Setting',
    'default_language'       => 'Default Language',
    'default_currency'       => 'Default Currency',
    'default_customer_group' => 'Default Customer Group',
    'admin_name'             => 'Background Directory',
    'admin_name_info'        => 'Management background directory, the default is admin',
    'enable_tax'             => 'Enable Tax',
    'enable_tax_info'        => 'Whether to enable tax calculation',
    'tax_address'            => 'Tax Address',
    'tax_address_info'       => 'According to what address to calculate tax',
    'logo'                   => 'Website Logo',
    'favicon'                => 'Favicon',
    'favicon_info'           => 'The small icon displayed on the browser tab must be in PNG format and the size is: 32*32',
    'placeholder_image'      => 'Placeholder Image',
    'placeholder_image_info' => 'The placeholder image displayed when there is no image or the image is not found, recommended size: 500*500',
    'head_code'              => 'Insert code',
    'head_code_info'         => 'The code in the input box will be inserted into the head of the front-end page, which can be used to count the code or add special plug-ins, etc',
    'rate_api_key'           => 'Exchange rate API KEY',
    'multi_filter'           => 'Multi Filter',
    'please_select'          => 'Please select',
    'multi_filter_helper'    => 'Please select the attributes that need to be displayed in the filter area of the product list at the front desk, if left blank, all will be displayed',
    'filter_attribute'       => 'Attribute Filter',
    'license_code'           => 'License Code',
    'order_auto_cancel'      => 'Order Auto Cancel',
    'order_auto_complete'    => 'Order Auto Complete',
];
