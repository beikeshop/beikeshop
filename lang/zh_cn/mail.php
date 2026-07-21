<?php

/**
 * address.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-22 18:18:59
 * @modified   2022-08-22 18:18:59
 */

return [
    'order_success'           => '订单提交成功',
    'order_update'            => '订单状态更新',
    'order_success_info'      => '您的订单已提交成功，下面是订单明细',
    'order_success'           => '您的订单已提交成功',
    'not_oneself'             => '非本人操作可忽略。',
    'customer_name'           => '尊敬的 :name 用户，您好',
    'sincerely'               => '此致',
    'team'                    => '团队',
    'order_update_status'     => '您的订单 :number 状态更新为',
    'welcome_register'        => '欢迎注册',
    'new_register'            => '新用户注册',
    'customer_name_line'      => '用户名',
    'register_end'            => '完成注册，请点击下面按钮回到商城。',
    'btn_buy_now'             => '立即购买',
    'retrieve_password_title' => '找回密码',
    'retrieve_password_text'  => '您正在找回密码，请点击下面按钮完成操作。',
    'retrieve_password_btn'   => '点击此处重置密码',
    'rma_success'             => '售后服务提交成功',
    'rma_success_admin'       => '有新的售后服务订单',
    'admin_name'              => '尊敬的管理员用户，您好',
    'rma_product'             => '商品信息',
    'new_order'               => '有新订单',
    'order_update_admin'      => '订单号 :number 状态更新为',

    // SendCloud 邮件传输错误消息
    'sendcloud_invalid_message_type'         => '消息必须是 Symfony\Component\Mime\Email 的实例',
    'sendcloud_send_failed'                  => 'SendCloud 邮件发送失败',
    'sendcloud_from_address_empty'           => 'SendCloud 发件人地址不能为空',
    'sendcloud_from_address_invalid'         => 'SendCloud 发件人地址格式无效: :address',
    'sendcloud_example_domain_not_supported' => 'SendCloud 不支持示例域名地址: :address。请在后台设置中配置真实的发件人邮箱地址。',
    'sendcloud_to_address_empty'             => 'SendCloud 收件人地址不能为空',
    'sendcloud_to_address_invalid'           => 'SendCloud 收件人地址格式无效: :address',
    'sendcloud_api_call_failed'              => 'SendCloud API 调用失败',
    'sendcloud_api_error'                    => 'SendCloud 错误 [:status_code]: :message',
    'sendcloud_send_success'                 => 'SendCloud 邮件发送成功',
];
