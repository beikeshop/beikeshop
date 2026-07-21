<?php

/**
 * Lang.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-09-09 09:09:09
 * @modified   2023-09-08 07:19:53
 */

return [
    'order_success'           => '訂單提交成功',
    'order_update'            => '訂單狀態更新',
    'order_success_info'      => '您的訂單已提交成功，下面是訂單明細',
    'order_success'           => '您的訂單已提交成功',
    'not_oneself'             => '非本人操作可忽略。 ',
    'customer_name'           => '尊敬的 :name 用戶，您好！ ',
    'sincerely'               => '此致',
    'team'                    => '團隊',
    'order_update_status'     => '您的訂單 :number 狀態更新為',
    'welcome_register'        => '歡迎註冊',
    'new_register'            => '新用戶註冊',
    'customer_name_line'      => '用戶名',
    'register_end'            => '完成註冊，請點擊下面按鈕回到商城。 ',
    'btn_buy_now'             => '立即購買',
    'retrieve_password_title' => '找回密碼',
    'retrieve_password_text'  => '您正在找回密碼，請點擊下面按鈕完成操作。 ',
    'retrieve_password_btn'   => '點擊此處驗證郵箱',
    'rma_success'             => '售後服務提交成功',
    'rma_success_admin'       => '有新的售後服務訂單',
    'admin_name'              => '尊敬的管理員用戶，您好',
    'rma_product'             => '商品信息',
    'new_order'               => '有新訂單',
    'order_update_admin'      => '訂單號 :number 狀態更新為',

    // SendCloud 郵件傳輸錯誤消息
    'sendcloud_invalid_message_type'         => '消息必須是 Symfony\Component\Mime\Email 的實例',
    'sendcloud_send_failed'                  => 'SendCloud 郵件發送失敗',
    'sendcloud_from_address_empty'           => 'SendCloud 發件人地址不能為空',
    'sendcloud_from_address_invalid'         => 'SendCloud 發件人地址格式無效: :address',
    'sendcloud_example_domain_not_supported' => 'SendCloud 不支持示例域名地址: :address。請在後台設置中配置真實的發件人郵箱地址。',
    'sendcloud_to_address_empty'             => 'SendCloud 收件人地址不能為空',
    'sendcloud_to_address_invalid'           => 'SendCloud 收件人地址格式無效: :address',
    'sendcloud_api_call_failed'              => 'SendCloud API 調用失敗',
    'sendcloud_api_error'                    => 'SendCloud 錯誤 [:status_code]: :message',
    'sendcloud_send_success'                 => 'SendCloud 郵件發送成功',
];
