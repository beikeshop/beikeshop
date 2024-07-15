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
    // Heading
    'heading_title' => 'Newbie guide',

    //Tab
    'tab_basic'            => 'Basic settings',
    'tab_language'         => 'Multiple languages and currencies',
    'tab_product'          => 'Create product',
    'tab_theme'            => 'Shop decoration',
    'tab_payment_shipping' => 'Payment and Logistics',
    'tab_mail'             => 'Configuration mail',

    //Text
    'text_extension'  => 'Extension',
    'text_success'    => 'Success: The novice guide has been modified! ',
    'text_edit'       => 'Guide to editing for newbies',
    'text_view'       => 'Show details...',
    'text_greeting'   => 'Congratulations, your website has successfully installed BeikeShop! ',
    'text_greeting_1' => 'We will guide you to make some basic custom configurations on the system to help you understand the functions of the BeikeShop system and start using it quickly! ',
    'text_basic_1'    => 'First, you can configure the following important information in the system settings:',
    'text_language_1' => 'The BeikeShop system supports multiple languages and currencies. Before you start creating your first product, you can select the default language and currency at the mall front desk! ',
    'text_language_2' => 'If you only need to use one language and currency, you can delete the other languages and currencies. Avoid the hassle of entering information in multiple languages when creating products. ',
    'text_product_1'  => 'During system installation, some default product data will be automatically imported for demonstration use. You can try <a href="' . admin_route('products.create') . '">Create products</a> first! ',
    'text_product_2'  => 'BeikeShop provides powerful product management capabilities! Including: <a href="' . admin_route('categories.index') . '">Product classification</a>, <a href="' . admin_route('brands.index') . '">Brand management</a>, multi-specification products, <a href="' . admin_route('multi_filter.index') . '">Advanced filtering</a>, <a href="' . admin_route('attributes.index') . '">Product attributes</a> and other functions. ',
    'text_theme_1'    => 'The system has a set of default theme templates installed by default. If the default theme does not meet your needs, you can also use <a href="' . admin_route('marketing.index', ['type' => ' theme']) . '">Plugin Market</a> to purchase other template themes. ',
    'text_theme_2'    => 'In addition, the home page of the front-end theme template is presented by the module through the layout. You may need to adjust some module settings through the layout. ',
    'text_theme_3'    => 'If you purchase the APP, we also provide a function specifically for <a href="' . admin_route('design_app_home.index') . '">APP homepage design</a>. ',
    'text_payment_1'  => 'BeikeShop provides commonly used overseas payment channels, such as the default PayPal, Stripe, etc. Before officially placing orders, you need to enable and configure the corresponding payment method. ',
    'text_payment_2'  => 'Note: Some payment interface applications take longer to review, please apply in advance. Payment methods used in China may require website domain name registration. ',
    'text_payment_3'  => 'In addition, you also need to set the logistics delivery method for customers to choose. The system provides a fixed shipping fee plug-in for free. ',
    'text_payment_4'  => 'You can also go to BeikeShop<a href="' . admin_route('marketing.index') . '">"Plug-in Market"</a> to learn and download more payment methods and logistics methods ! ',
    'text_mail_1'     => 'Email notifications can keep your customers informed of order status, and they can also register and retrieve passwords via email. You can configure SMTP according to actual business needs, and email engines such as SendCloud are used to send emails. ',
    'text_mail_2'     => 'Warm reminder: Frequently sending emails may cause your emails to be marked as spam. We recommend using SendCloud (paid service) to send emails. ',

    // Button
    'button_setting_general' => 'Website basic settings',
    'button_setting_store'   => 'Website name',
    'button_setting_logo'    => 'Change Logo',
    'button_setting_option'  => 'Option setting',
    'button_setting'         => 'All system settings',
    'button_language'        => 'Language management',
    'button_currency'        => 'Currency management',
    'button_product'         => 'View product',
    'button_product_create'  => 'Create product',
    'button_theme_pc'        => 'Template settings',
    'button_theme_h5'        => 'Mobile theme settings',
    'button_theme'           => 'All themes',
    'button_layout'          => 'Layout management',
    'button_payment'         => 'Payment method',
    'button_shipping'        => 'Shipping method',
    'button_mail'            => 'Mail settings',
    'button_sms'             => 'SMS configuration',
    'button_hide'            => 'Don\'t show again',
];
