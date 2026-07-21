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
    'mail_logs' => '郵件記錄',
    'mail_log'  => '郵件記錄',
    'list'      => '郵件記錄列表',
    'detail'    => '郵件詳情',

    // Fields
    'to_email'      => '收件人郵箱',
    'to_name'       => '收件人姓名',
    'from_email'    => '發件人郵箱',
    'from_name'     => '發件人姓名',
    'subject'       => '郵件主題',
    'content'       => '郵件內容',
    'mail_type'     => '郵件類型',
    'transport'     => '傳輸方式',
    'status'        => '發送狀態',
    'error_message' => '錯誤信息',
    'sent_at'       => '發送時間',
    'created_at'    => '創建時間',
    'updated_at'    => '更新時間',

    // Status
    'status_pending' => '待發送',
    'status_sent'    => '已發送',
    'status_failed'  => '發送失敗',

    // Mail Types
    'type_customer_registration' => '客戶註冊',
    'type_customer_forgotten'    => '忘記密碼',
    'type_order_new'             => '新訂單',
    'type_order_update'          => '訂單更新',
    'type_rma_new'               => '退貨申請',
    'type_admin_forgotten'       => '管理員忘記密碼',
    'type_other'                 => '其他',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => '日誌',
    'transport_array'     => '數組',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => '未知',

    // Actions
    'view_detail'  => '查看詳情',
    'resend'       => '重新發送',
    'delete'       => '刪除',
    'batch_delete' => '批量刪除',
    'cleanup'      => '清理舊記錄',
    'statistics'   => '統計信息',
    'back_to_list' => '返回列表',

    // Filters
    'filter_status'     => '發送狀態',
    'filter_mail_type'  => '郵件類型',
    'filter_transport'  => '傳輸方式',
    'filter_recipient'  => '收件人',
    'filter_start_date' => '開始日期',
    'filter_end_date'   => '結束日期',
    'filter_submit'     => '篩選',
    'filter_reset'      => '重置',
    'all_status'        => '全部狀態',
    'all_types'         => '全部類型',
    'all_transports'    => '全部方式',

    // Statistics
    'total_count'   => '總計',
    'sent_count'    => '已發送',
    'failed_count'  => '發送失敗',
    'pending_count' => '待發送',
    'recent_count'  => '最近',

    // Messages
    'no_records'             => '暫無郵件記錄',
    'delete_confirm'         => '確定要刪除這條記錄嗎？',
    'batch_delete_confirm'   => '確定要刪除選中的記錄嗎？',
    'resend_confirm'         => '確定要重新發送這封郵件嗎？',
    'cleanup_confirm'        => '請輸入要保留的天數（將刪除指定天數之前的記錄）：',
    'cleanup_title'          => '清理舊記錄',
    'delete_success'         => '刪除成功',
    'batch_delete_success'   => '批量刪除成功',
    'resend_success'         => '重新發送成功',
    'cleanup_success'        => '清理成功',
    'operation_failed'       => '操作失敗',
    'delete_failed'          => '刪除失敗',
    'resend_failed'          => '重新發送失敗',
    'cleanup_failed'         => '清理失敗',
    'please_select_records'  => '請選擇要操作的記錄',
    'resend_not_available'   => '該郵件已經發送成功，無需重新發送',
    'resend_not_implemented' => '重新發送功能暫未實現，請聯繫開發人員',
    'statistics_developing'  => '統計功能開發中...',
    'total_records'          => '共 :count 條記錄',
    'recipient_placeholder'  => '郵箱地址',

    // Controller messages
    'select_records_to_delete'       => '請選擇要刪除的記錄',
    'delete_success_message'         => '刪除成功',
    'delete_failed_message'          => '刪除失敗: :error',
    'select_records_to_operate'      => '請選擇要操作的記錄',
    'batch_delete_success_message'   => '批量刪除成功',
    'batch_mark_sent_success'        => '批量標記為已發送成功',
    'batch_mark_failed_success'      => '批量標記為失敗成功',
    'unsupported_operation'          => '不支持的操作',
    'operation_failed_message'       => '操作失敗: :error',
    'days_must_greater_than_zero'    => '天數必須大於0',
    'cleanup_success_message'        => '成功清理了 :count 條記錄',
    'cleanup_failed_message'         => '清理失敗: :error',
    'get_statistics_success'         => '獲取統計數據成功',
    'get_statistics_failed'          => '獲取統計數據失敗: :error',
    'mail_already_sent'              => '該郵件已經發送成功，無需重新發送',
    'resend_not_implemented_message' => '重新發送功能暫未實現，請聯繫開發人員',
    'resend_failed_message'          => '重新發送失敗: :error',

    // Model translations
    'unknown_status'    => '未知狀態',
    'unknown_type'      => '未知類型',
    'unknown_transport' => '未知方式',

    // Detail Page
    'basic_info'        => '基本信息',
    'mail_content'      => '郵件內容',
    'attachments'       => '附件',
    'headers'           => '郵件頭',
    'operation_history' => '操作歷史',
    'view_headers'      => '查看詳細頭信息',
    'mail_created'      => '郵件創建',
    'mail_sent'         => '郵件發送',
    'mail_failed'       => '發送失敗',
    'unknown_file'      => '未知文件',
    'system_default'    => '系統默認',
    'not_sent'          => '未發送',

    // Permissions
    'permission_index'  => '查看郵件記錄',
    'permission_show'   => '查看郵件詳情',
    'permission_delete' => '刪除郵件記錄',
    'permission_update' => '更新郵件記錄',

    // Permission Names (for permission system)
    'mail_logs_index'  => '查看郵件記錄',
    'mail_logs_show'   => '查看郵件詳情',
    'mail_logs_delete' => '刪除郵件記錄',
    'mail_logs_update' => '更新郵件記錄',

    // View translations
    'confirm_resend' => '確定要重新發送這封郵件嗎？',
    'confirm_delete' => '確定要刪除這條記錄嗎？',
    'text_hint'      => '提示',
];
