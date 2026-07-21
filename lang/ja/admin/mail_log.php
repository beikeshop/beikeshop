<?php

/**
 * mail_log.php
 *
 * @copyright  2025 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2025-07-22
 */

return [
    'mail_logs' => 'メールログ',
    'mail_log'  => 'メールログ',
    'list'      => 'メールログ一覧',
    'detail'    => 'メール詳細',

    // Fields
    'to_email'      => '宛先メール',
    'to_name'       => '宛先名',
    'from_email'    => '送信者メール',
    'from_name'     => '送信者名',
    'subject'       => '件名',
    'content'       => '内容',
    'mail_type'     => 'メールタイプ',
    'transport'     => '送信方法',
    'status'        => 'ステータス',
    'error_message' => 'エラーメッセージ',
    'sent_at'       => '送信日時',
    'created_at'    => '作成日時',
    'updated_at'    => '更新日時',

    // Status
    'status_pending' => '保留中',
    'status_sent'    => '送信済み',
    'status_failed'  => '送信失敗',

    // Mail Types
    'type_customer_registration' => '顧客登録',
    'type_customer_forgotten'    => 'パスワード忘れ',
    'type_order_new'             => '新規注文',
    'type_order_update'          => '注文更新',
    'type_rma_new'               => '返品リクエスト',
    'type_admin_forgotten'       => '管理者パスワード忘れ',
    'type_other'                 => 'その他',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'ログ',
    'transport_array'     => '配列',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => '不明',

    // Actions
    'view_detail'  => '詳細表示',
    'resend'       => '再送信',
    'delete'       => '削除',
    'batch_delete' => '一括削除',
    'cleanup'      => '古いレコードをクリーンアップ',
    'statistics'   => '統計',
    'back_to_list' => 'リストに戻る',

    // Filters
    'filter_status'     => 'ステータス',
    'filter_mail_type'  => 'メールタイプ',
    'filter_transport'  => '送信方法',
    'filter_recipient'  => '宛先',
    'filter_start_date' => '開始日',
    'filter_end_date'   => '終了日',
    'filter_submit'     => 'フィルター',
    'filter_reset'      => 'リセット',
    'all_status'        => '全てのステータス',
    'all_types'         => '全てのタイプ',
    'all_transports'    => '全ての送信方法',

    // Statistics
    'total_count'   => '合計',
    'sent_count'    => '送信済み',
    'failed_count'  => '送信失敗',
    'pending_count' => '保留中',
    'recent_count'  => '最近',

    // Messages
    'no_records'             => 'メールレコードが見つかりません',
    'delete_confirm'         => 'このレコードを削除してもよろしいですか？',
    'batch_delete_confirm'   => '選択したレコードを削除してもよろしいですか？',
    'resend_confirm'         => 'このメールを再送信してもよろしいですか？',
    'cleanup_confirm'        => '保持する日数を入力してください（それより古いレコードは削除されます）：',
    'cleanup_title'          => '古いレコードをクリーンアップ',
    'delete_success'         => '削除に成功しました',
    'batch_delete_success'   => '一括削除に成功しました',
    'resend_success'         => '再送信に成功しました',
    'cleanup_success'        => 'クリーンアップに成功しました',
    'operation_failed'       => '操作に失敗しました',
    'delete_failed'          => '削除に失敗しました',
    'resend_failed'          => '再送信に失敗しました',
    'cleanup_failed'         => 'クリーンアップに失敗しました',
    'please_select_records'  => '操作するレコードを選択してください',
    'resend_not_available'   => 'このメールは既に送信済みです。再送信の必要はありません',
    'resend_not_implemented' => '再送信機能はまだ実装されていません。開発者にお問い合わせください',
    'statistics_developing'  => '統計機能は開発中です...',
    'total_records'          => '合計 :count 件のレコード',
    'recipient_placeholder'  => 'メールアドレス',

    // Controller messages
    'select_records_to_delete'       => '削除するレコードを選択してください',
    'delete_success_message'         => '削除に成功しました',
    'delete_failed_message'          => '削除に失敗しました: :error',
    'select_records_to_operate'      => '操作するレコードを選択してください',
    'batch_delete_success_message'   => '一括削除に成功しました',
    'batch_mark_sent_success'        => '一括送信済みマークに成功しました',
    'batch_mark_failed_success'      => '一括失敗マークに成功しました',
    'unsupported_operation'          => 'サポートされていない操作です',
    'operation_failed_message'       => '操作に失敗しました: :error',
    'days_must_greater_than_zero'    => '日数は0より大きくなければなりません',
    'cleanup_success_message'        => ':count 件のレコードのクリーンアップに成功しました',
    'cleanup_failed_message'         => 'クリーンアップに失敗しました: :error',
    'get_statistics_success'         => '統計の取得に成功しました',
    'get_statistics_failed'          => '統計の取得に失敗しました: :error',
    'mail_already_sent'              => 'このメールは既に送信済みです。再送信の必要はありません',
    'resend_not_implemented_message' => '再送信機能はまだ実装されていません。開発者にお問い合わせください',
    'resend_failed_message'          => '再送信に失敗しました: :error',

    // Model translations
    'unknown_status'    => '不明なステータス',
    'unknown_type'      => '不明なタイプ',
    'unknown_transport' => '不明な送信方法',

    // Detail Page
    'basic_info'        => '基本情報',
    'mail_content'      => 'メール内容',
    'attachments'       => '添付ファイル',
    'headers'           => 'メールヘッダー',
    'operation_history' => '操作履歴',
    'view_headers'      => '詳細ヘッダーを表示',
    'mail_created'      => 'メール作成',
    'mail_sent'         => 'メール送信',
    'mail_failed'       => '送信失敗',
    'unknown_file'      => '不明なファイル',
    'system_default'    => 'システムデフォルト',
    'not_sent'          => '未送信',

    // Permissions
    'permission_index'  => 'メールログを表示',
    'permission_show'   => 'メール詳細を表示',
    'permission_delete' => 'メールログを削除',
    'permission_update' => 'メールログを更新',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'メールログを表示',
    'mail_logs_show'   => 'メール詳細を表示',
    'mail_logs_delete' => 'メールログを削除',
    'mail_logs_update' => 'メールログを更新',

    // View translations
    'confirm_resend' => 'このメールを再送信してもよろしいですか？',
    'confirm_delete' => 'このレコードを削除してもよろしいですか？',
    'text_hint'      => 'ヒント',
];
