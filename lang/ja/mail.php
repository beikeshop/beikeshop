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
    'order_success'           => '注文は正常に送信されました',
    'order_update'            => '注文状況の更新',
    'order_success_info'      => 'ご注文は正常に送信されました。以下が注文の詳細です',
    'order_success'           => 'ご注文は正常に送信されました',
    'not_oneself'             => '非個人的な操作は無視できます。 ',
    'customer_name'           => '親愛なる :name ユーザー様、こんにちは! ',
    'sincerely'               => '心から',
    'team'                    => 'チーム',
    'order_update_status'     => '注文 :number のステータスが更新されました',
    'welcome_register'        => '登録へようこそ',
    'new_register'            => '新規ユーザー登録',
    'customer_name_line'      => 'ユーザー名',
    'register_end'            => '登録が完了しました。モールに戻るには、下のボタンをクリックしてください。 ',
    'btn_buy_now'             => '今すぐ購入',
    'retrieve_password_title' => 'パスワードを取得',
    'retrieve_password_text'  => 'パスワードを取得しています。下のボタンをクリックして操作を完了してください。 ',
    'retrieve_password_btn'   => 'ここをクリックしてメールを確認',
    'rma_success'             => 'アフターサービスの申請が正常に送信されました',
    'rma_success_admin'       => '新しいアフターサービスの注文があります',
    'admin_name'              => '親愛なる管理者様、こんにちは! ',
    'rma_product'             => '商品情報',
    'new_order'               => '新しい注文があります',
    'order_update_admin'      => '注文番号 :number のステータスが更新されました',

    // SendCloud メール送信エラーメッセージ
    'sendcloud_invalid_message_type'         => 'メッセージは Symfony\Component\Mime\Email のインスタンスである必要があります',
    'sendcloud_send_failed'                  => 'SendCloud メール送信に失敗しました',
    'sendcloud_from_address_empty'           => 'SendCloud 送信者アドレスは空にできません',
    'sendcloud_from_address_invalid'         => 'SendCloud 送信者アドレスの形式が無効です: :address',
    'sendcloud_example_domain_not_supported' => 'SendCloud はサンプルドメインアドレスをサポートしていません: :address。バックエンド設定で実際の送信者メールアドレスを設定してください。',
    'sendcloud_to_address_empty'             => 'SendCloud 受信者アドレスは空にできません',
    'sendcloud_to_address_invalid'           => 'SendCloud 受信者アドレスの形式が無効です: :address',
    'sendcloud_api_call_failed'              => 'SendCloud API 呼び出しに失敗しました',
    'sendcloud_api_error'                    => 'SendCloud エラー [:status_code]: :message',
    'sendcloud_send_success'                 => 'SendCloud メールが正常に送信されました',
];
