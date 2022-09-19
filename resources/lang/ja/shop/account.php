<?php
/**
 * account.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-04 10:59:15
 * @modified   2022-08-04 10:59:15
 */

return [
    'index' => 'パーソナルセンター',
    'revise_info' => '情報の改訂',
    'collect' => '集める',
    'coupon' => 'クーポン',
    'my_order' => '私の注文',
    'orders' => 'すべての注文',
    'pending_payment' => '支払い予定',
    'pending_send' => '発送予定',
    'pending_receipt' => '受信予定',
    'after_sales' => 'after-sales',
    'no_order' => 'まだ注文がありません! ',
    'to_buy' => '注文に行く',
    'order_number' => '注文番号',
    'order_time' => '注文時間',
    'state' => '状態',
    'amount' => '金額',
    'check_details' => '詳細を確認',
    'all' => '合計',
    'items' => 'アイテム',
    'verify_code_expired' => '確認コードの有効期限が切れています (10 分)。もう一度取得してください',
    'verify_code_error' => '確認コードが間違っています',
    'account_not_exist' => 'アカウントが存在しません',

    'edit' => [
        'index' => '個人情報の変更',
        'modify_avatar' => 'アバターの変更',
        'suggest' => 'JPG または PNG 画像をアップロードします。 300 x 300 をお勧めします。 ',
        'name' => '名前',
        'email' => 'メールボックス',
        'crop' => 'クロップ',
        'password_edit_success' => 'パスワードが正常に変更されました',
        'origin_password_fail' => '元のパスワード エラー',
    ],

    'wishlist' => [
        'index' => 'お気に入り',
        'product' => '製品',
        'price' => '価格',
        'check_details' => '詳細を確認',
    ],

    'order' => [
        'index' => '私の注文',
        'completed' => '受領確認',
        'cancelled' => '注文がキャンセルされました',
        'order_details' => '注文の詳細',
        'amount' => '金額',
        'state' => '状態',
        'order_number' => '注文番号',
        'check' => 'チェック',

        'order_info' => [
            'index' => '注文詳細',
            'order_details' => '注文の詳細',
            'to_pay' => '支払う',
            'cancel' => '注文をキャンセル',
            'confirm_receipt' => '領収書の確認',
            'order_number' => '注文番号',
            'order_date' => '注文日',
            'state' => '状態',
            'order_amount' => '注文金額',
            'order_items' => '注文商品',
            'apply_after_sales' => '販売後に適用',
            'order_total' => '注文合計',
            'logistics_status' => '物流ステータス',
            'order_status' => '注文ステータス',
            'remark' => 'コメント',
            'update_time' => '更新時間',
        ],

        'order_success' => [
            'order_success' => 'おめでとう、注文は正常に生成されました! ',
            'order_number' => '注文番号',
            'amounts_payable' => '支払金額',
            'payment_method' => '支払い方法',
            'view_order' => '注文の詳細を表示',
            'pay_now' => '今すぐ支払う',
            'kind_tips' => 'ご注意: ご注文は正常に処理されました。できるだけ早くお支払いを完了してください~',
            'also' => 'あなたもできる',
            'continue_purchase' => '購入を続ける',
            'contact_customer_service' => '注文プロセス中にご不明な点がございましたら、いつでもカスタマー サービス スタッフにお問い合わせください',
            'emaill' => 'メールボックス',
            'service_hotline' => 'サービス ホットライン',
        ]

    ],

    'addresses' => [
        'index' => '私のアドレス',
        'add_address' => '新しいアドレスを追加',
        'default_address' => 'デフォルトアドレス',
        'delete' => '削除',
        'edit' => '編集',
        'enter_name' => 'あなたの名前を入力してください',
        'enter_phone' => '電話番号を入力してください',
        'enter_address' => '詳しい住所を入力してください 1',
        'select_province' => '都道府県を選択してください',
        'enter_city' => '都市を入力してください',
        'confirm_delete' => '本当にアドレスを削除しますか? ',
        'hint' => 'ヒント',
        'check_form' => 'フォームが正しく記入されているか確認してください',
    ],

    'rma' => [
        'index' => '私のアフターセールス',
        'commodity' => '商品',
        'quantity' => '数量',
        'service_type' => 'サービス タイプ',
        'return_reason' => '返品理由',
        'creation_time' => '作成時間',
        'check' => 'チェック',

        'rma_info' => [
            'index' => 'アフターセールスの詳細',
        ],

        'rma_form' => [
            'index' => 'アフターセールス情報の送信',
            'service_type' => 'サービス タイプ',
            'return_quantity' => '返品数量',
            'unpacked' => 'unpacked',
            'return_reason' => '返品理由',
            'remark' => 'コメント',
        ]
    ]
];
