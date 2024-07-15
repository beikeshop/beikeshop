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
    'heading_title' => '新手引導',

    // Tab
    'tab_basic'            => '基本設定',
    'tab_language'         => '多語言與貨幣',
    'tab_product'          => '建立商品',
    'tab_theme'            => '店面裝修',
    'tab_payment_shipping' => '收款與物流',
    'tab_mail'             => '設定郵件',

    // Text
    'text_extension'  => '擴充',
    'text_success'    => '成功：新手引導已修改！ ',
    'text_edit'       => '編輯新手引導',
    'text_view'       => '顯示詳細...',
    'text_greeting'   => '恭喜，您的網站已成功安裝BeikeShop！ ',
    'text_greeting_1' => '我們將指引您對系統做一些基本的自訂配置，幫助您了解BeikeShop系統功能，快速開始使用！ ',
    'text_basic_1'    => '首先，您可在系統設定中配置以下重要資訊：',
    'text_language_1' => 'BeikeShop 系統支援多語言、多貨幣，在開始建立您的第一個商品前，可以先選擇商城前台預設的語言和貨幣！ ',
    'text_language_2' => '如果您只需要使用一種語言和貨幣，可以刪除其他語言和貨幣。 避免創建商品時，輸入多種語言資訊的麻煩。 ',
    'text_product_1'  => '系統安裝時，會自動匯入一些預設商品資料做示範使用。 您可以先試試 <a href="' . admin_route('products.create') . '">建立商品</a>！ ',
    'text_product_2'  => 'BeikeShop 提供了強大的商品管理能力！ 包括:<a href="' . admin_route('categories.index') . '">商品分類</a>，<a href="' . admin_route('brands.index') . '">品牌管理</a>，多規格商品，<a href="' . admin_route('multi_filter.index') . '">高級篩選</a>，<a href="' . admin_route('attributes.index') . '">商品屬性</a>等功能。 ',
    'text_theme_1'    => '系統預設安裝了一套預設主題模板，如果預設主題不滿足您的需求，也可以在<a href="' . admin_route('marketing.index', ['type' => ' theme']) . '">外掛市場</a>選購其他模版主題。 ',
    'text_theme_2'    => '此外，前台的主題模板的首頁是由模組透過佈局呈現的，您可能需要透過佈局調整一些模組的設定。 ',
    'text_theme_3'    => '如果您購買了APP，我們還提供了專為<a href="' . admin_route('design_app_home.index') . '">APP首頁設計</a>的功能。 ',
    'text_payment_1'  => 'BeikeShop 提供了海外常用的收款管道，例如預設的 PayPal、Stripe 等。 在正式開放下單前，您需要啟用並設定相應收款方式。 ',
    'text_payment_2'  => '注意：某些支付介面申請審核時間較長，請提前申請。 在國內使用的付款方式可能會要求網站域名備案。 ',
    'text_payment_3'  => '此外，您還需要設定物流配送方式供顧客選擇。 系統免費提供了固定運費插件。 ',
    'text_payment_4'  => '您也可以去BeikeShop<a href="' . admin_route('marketing.index') . '">「插件市場」</a>了解並下載更多的收款方式、物流方式 ！ ',
    'text_mail_1'     => '郵件通知可以讓您的客戶隨時了解訂單狀態，同時也可透過郵件註冊和找回密碼。 您可依實際業務需求設定 SMTP，SendCloud 等郵件引擎用於傳送郵件。 ',
    'text_mail_2'     => '溫馨提醒：經常發送郵件，可能讓您的郵件被標記為垃圾郵件，我們建議使用 SendCloud (收費服務) 發送郵件。 ',

    // Button
    'button_setting_general' => '網站基礎設定',
    'button_setting_store'   => '網站名稱',
    'button_setting_logo'    => '更換 Logo',
    'button_setting_option'  => '選項設定',
    'button_setting'         => '所有系統設定',
    'button_language'        => '語言管理',
    'button_currency'        => '金錢管理',
    'button_product'         => '查看商品',
    'button_product_create'  => '建立商品',
    'button_theme_pc'        => '範本設定',
    'button_theme_h5'        => '手機主題設定',
    'button_theme'           => '所有主題',
    'button_layout'          => '佈局管理',
    'button_payment'         => '付款方式',
    'button_shipping'        => '配送方式',
    'button_mail'            => '郵件設定',
    'button_sms'             => '簡訊配置',
    'button_hide'            => '不再顯示',
];
