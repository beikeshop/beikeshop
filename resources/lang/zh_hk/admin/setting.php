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
    'index'                  => '系統設置',
    'settings_index'         => '查看系統設置',
    'settings_update'        => '修改系統設置',
    'design_index'           => '首頁編輯器',
    'design_footer_index'    => '頁尾編輯器',
    'design_menu_index'      => '導航編輯器',
    'product_per_page'       => '每頁顯示商品個數',

    'checkout_settings'      => '結賬設置',
    'basic_settings'         => '基礎設置',
    'store_settings'         => '商店設置',
    'picture_settings'       => '圖片設置',
    'use_queue'              => '是否使用隊列',
    'mail_settings'          => '郵件設置',
    'mail_engine'            => '郵件引擎',
    'smtp_host'              => '主機',
    'smtp_username'          => '用戶',
    'smtp_encryption'        => '加密方式',
    'smtp_encryption_info'   => 'SSL 或者 TLS',
    'smtp_password'          => '密碼',
    'smtp_password_info'     => '設置 SMTP 密碼。 Gmail 請參考：https://security.google.com/settings/security/apppasswords',
    'smtp_port'              => '端口',
    'smtp_timeout'           => '超時',
    'sendmail_path'          => '執行路徑',
    'mailgun_domain'         => '域名',
    'mailgun_secret'         => '密鑰',
    'mailgun_endpoint'       => '端口',
    'mail_log'               => '說明：日誌引擎一般用於測試目的！郵件將不會被真實發送至收件地址，郵件內容會以日誌形式保存在 `/storage/logs/laravel.log`',
    'express_code_help'      => '數字、字母、下劃線',

    'guest_checkout'         => '遊客結賬',
    'theme_default'          => '默認主題',
    'theme_black'            => '黑色主題',
    'shipping_address'       => '發貨地址',
    'payment_address'        => '賬單地址',
    'meta_title'             => 'Meta 標題',
    'meta_description'       => 'Meta 描述',
    'meta_keywords'          => 'Meta 關鍵詞',
    'telephone'              => '聯繫電話',
    'email'                  => '郵箱',
    'default_address'        => '默認地址',
    'default_country_set'    => '默認國家設置',
    'default_zone_set'       => '默認省份設置',
    'default_language'       => '默認語言',
    'default_currency'       => '默認貨幣',
    'default_customer_group' => '默認客戶組',
    'admin_name'             => '後台目錄',
    'admin_name_info'        => '管理後台目錄,默認為admin',
    'enable_tax'             => '啟用稅費',
    'enable_tax_info'        => '是否啟用稅費計算',
    'tax_address'            => '稅費地址',
    'tax_address_info'       => '按什麼地址計算稅費',
    'logo'                   => '網站 Logo',
    'favicon'                => 'favicon',
    'favicon_info'           => '顯示在瀏覽器選項卡上的小圖標，必須為PNG格式大小為：32*32',
    'placeholder_image'      => '佔位圖',
    'placeholder_image_info' => '無圖片或圖片未找到時顯示的佔位圖，建議尺寸：500*500',
    'head_code'              => '插入代碼',
    'head_code_info'         => '會將輸入框中的代碼插入到前端頁面 head 中，可用於統計代碼或者添加特殊插件等',
    'rate_api_key'           => '匯率 API KEY',
];
