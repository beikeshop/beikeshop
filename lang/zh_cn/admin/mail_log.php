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
    'mail_logs' => '邮件发送记录',
    'mail_log' => '邮件记录',
    'list' => '邮件记录列表',
    'detail' => '邮件详情',

    // 字段
    'to_email' => '收件人邮箱',
    'to_name' => '收件人姓名',
    'from_email' => '发件人邮箱',
    'from_name' => '发件人姓名',
    'subject' => '邮件主题',
    'content' => '邮件内容',
    'mail_type' => '邮件类型',
    'transport' => '传输方式',
    'status' => '发送状态',
    'error_message' => '错误信息',
    'sent_at' => '发送时间',
    'created_at' => '创建时间',
    'updated_at' => '更新时间',

    // 状态
    'status_pending' => '待发送',
    'status_sent' => '已发送',
    'status_failed' => '发送失败',

    // 邮件类型
    'type_customer_registration' => '用户注册',
    'type_customer_forgotten' => '忘记密码',
    'type_order_new' => '新订单通知',
    'type_order_update' => '订单状态更新',
    'type_rma_new' => '退货申请',
    'type_admin_forgotten' => '管理员忘记密码',
    'type_other' => '其他',

    // 传输方式
    'transport_smtp' => 'SMTP',
    'transport_sendmail' => 'Sendmail',
    'transport_mailgun' => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log' => '日志记录',
    'transport_array' => '数组存储',
    'transport_ses' => 'Amazon SES',
    'transport_postmark' => 'Postmark',
    'transport_unknown' => '未知方式',

    // 操作
    'view_detail' => '查看详情',
    'resend' => '重新发送',
    'delete' => '删除',
    'batch_delete' => '批量删除',
    'cleanup' => '清理旧记录',
    'statistics' => '统计信息',
    'back_to_list' => '返回列表',

    // 筛选
    'filter_status' => '发送状态',
    'filter_mail_type' => '邮件类型',
    'filter_transport' => '传输方式',
    'filter_recipient' => '收件人',
    'filter_start_date' => '开始日期',
    'filter_end_date' => '结束日期',
    'filter_submit' => '筛选',
    'filter_reset' => '重置',
    'all_status' => '全部状态',
    'all_types' => '全部类型',
    'all_transports' => '全部方式',

    // 统计
    'total_count' => '总计',
    'sent_count' => '已发送',
    'failed_count' => '发送失败',
    'pending_count' => '待发送',
    'recent_count' => '最近发送',

    // 消息
    'no_records' => '暂无邮件记录',
    'delete_confirm' => '确定要删除这条记录吗？',
    'batch_delete_confirm' => '确定要删除选中的记录吗？',
    'resend_confirm' => '确定要重新发送这封邮件吗？',
    'cleanup_confirm' => '请输入要保留的天数（将删除指定天数之前的记录）：',
    'cleanup_title' => '清理旧记录',
    'delete_success' => '删除成功',
    'batch_delete_success' => '批量删除成功',
    'resend_success' => '重新发送成功',
    'cleanup_success' => '清理成功',
    'operation_failed' => '操作失败',
    'delete_failed' => '删除失败',
    'resend_failed' => '重新发送失败',
    'cleanup_failed' => '清理失败',
    'please_select_records' => '请选择要操作的记录',
    'resend_not_available' => '该邮件已经发送成功，无需重新发送',
    'resend_not_implemented' => '重新发送功能暂未实现，请联系开发人员',
    'statistics_developing' => '统计功能开发中...',
    'total_records' => '共 :count 条记录',
    'recipient_placeholder' => '邮箱地址',

    // 控制器消息
    'select_records_to_delete' => '请选择要删除的记录',
    'delete_success_message' => '删除成功',
    'delete_failed_message' => '删除失败: :error',
    'select_records_to_operate' => '请选择要操作的记录',
    'batch_delete_success_message' => '批量删除成功',
    'batch_mark_sent_success' => '批量标记为已发送成功',
    'batch_mark_failed_success' => '批量标记为失败成功',
    'unsupported_operation' => '不支持的操作',
    'operation_failed_message' => '操作失败: :error',
    'days_must_greater_than_zero' => '天数必须大于0',
    'cleanup_success_message' => '成功清理了 :count 条记录',
    'cleanup_failed_message' => '清理失败: :error',
    'get_statistics_success' => '获取统计数据成功',
    'get_statistics_failed' => '获取统计数据失败: :error',
    'mail_already_sent' => '该邮件已经发送成功，无需重新发送',
    'resend_not_implemented_message' => '重新发送功能暂未实现，请联系开发人员',
    'resend_failed_message' => '重新发送失败: :error',

    // 模型中的翻译
    'unknown_status' => '未知状态',
    'unknown_type' => '未知类型',
    'unknown_transport' => '未知方式',

    // 详情页面
    'basic_info' => '邮件基本信息',
    'mail_content' => '邮件内容',
    'attachments' => '附件信息',
    'headers' => '邮件头信息',
    'operation_history' => '操作历史',
    'view_headers' => '查看详细头信息',
    'mail_created' => '邮件创建',
    'mail_sent' => '邮件发送',
    'mail_failed' => '发送失败',
    'unknown_file' => '未知文件',
    'system_default' => '系统默认',
    'not_sent' => '未发送',

    // 权限
    'permission_index' => '查看邮件记录',
    'permission_show' => '查看邮件详情',
    'permission_delete' => '删除邮件记录',
    'permission_update' => '更新邮件记录',

    // 权限名称（用于权限系统）
    'mail_logs_index' => '查看邮件记录',
    'mail_logs_show' => '查看邮件详情',
    'mail_logs_delete' => '删除邮件记录',
    'mail_logs_update' => '更新邮件记录',

    // 视图中使用的词条
    'confirm_resend' => '确定要重新发送这封邮件吗？',
    'confirm_delete' => '确定要删除这条记录吗？',
    'text_hint' => '提示',
];
