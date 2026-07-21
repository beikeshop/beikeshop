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
    'mail_logs' => 'Mail Logs',
    'mail_log'  => 'Mail Log',
    'list'      => 'Mail Log List',
    'detail'    => 'Mail Details',

    // Fields
    'to_email'      => 'Recipient Email',
    'to_name'       => 'Recipient Name',
    'from_email'    => 'Sender Email',
    'from_name'     => 'Sender Name',
    'subject'       => 'Subject',
    'content'       => 'Content',
    'mail_type'     => 'Mail Type',
    'transport'     => 'Transport',
    'status'        => 'Status',
    'error_message' => 'Error Message',
    'sent_at'       => 'Sent At',
    'created_at'    => 'Created At',
    'updated_at'    => 'Updated At',

    // Status
    'status_pending' => 'Pending',
    'status_sent'    => 'Sent',
    'status_failed'  => 'Failed',

    // Mail Types
    'type_customer_registration' => 'Customer Registration',
    'type_customer_forgotten'    => 'Forgot Password',
    'type_order_new'             => 'New Order',
    'type_order_update'          => 'Order Update',
    'type_rma_new'               => 'RMA Request',
    'type_admin_forgotten'       => 'Admin Forgot Password',
    'type_other'                 => 'Other',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'Log',
    'transport_array'     => 'Array',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => 'Unknown',

    // Actions
    'view_detail'  => 'View Details',
    'resend'       => 'Resend',
    'delete'       => 'Delete',
    'batch_delete' => 'Batch Delete',
    'cleanup'      => 'Cleanup Old Records',
    'statistics'   => 'Statistics',
    'back_to_list' => 'Back to List',

    // Filters
    'filter_status'     => 'Status',
    'filter_mail_type'  => 'Mail Type',
    'filter_transport'  => 'Transport',
    'filter_recipient'  => 'Recipient',
    'filter_start_date' => 'Start Date',
    'filter_end_date'   => 'End Date',
    'filter_submit'     => 'Filter',
    'filter_reset'      => 'Reset',
    'all_status'        => 'All Status',
    'all_types'         => 'All Types',
    'all_transports'    => 'All Transports',

    // Statistics
    'total_count'   => 'Total',
    'sent_count'    => 'Sent',
    'failed_count'  => 'Failed',
    'pending_count' => 'Pending',
    'recent_count'  => 'Recent',

    // Messages
    'no_records'             => 'No mail records found',
    'delete_confirm'         => 'Are you sure you want to delete this record?',
    'batch_delete_confirm'   => 'Are you sure you want to delete selected records?',
    'resend_confirm'         => 'Are you sure you want to resend this email?',
    'cleanup_confirm'        => 'Enter the number of days to keep (records older than this will be deleted):',
    'cleanup_title'          => 'Cleanup Old Records',
    'delete_success'         => 'Deleted successfully',
    'batch_delete_success'   => 'Batch deleted successfully',
    'resend_success'         => 'Resent successfully',
    'cleanup_success'        => 'Cleaned up successfully',
    'operation_failed'       => 'Operation failed',
    'delete_failed'          => 'Delete failed',
    'resend_failed'          => 'Resend failed',
    'cleanup_failed'         => 'Cleanup failed',
    'please_select_records'  => 'Please select records to operate',
    'resend_not_available'   => 'This email has been sent successfully, no need to resend',
    'resend_not_implemented' => 'Resend function not implemented yet, please contact developer',
    'statistics_developing'  => 'Statistics feature is under development...',
    'total_records'          => 'Total :count records',
    'recipient_placeholder'  => 'Email address',

    // Controller messages
    'select_records_to_delete'       => 'Please select records to delete',
    'delete_success_message'         => 'Deleted successfully',
    'delete_failed_message'          => 'Delete failed: :error',
    'select_records_to_operate'      => 'Please select records to operate',
    'batch_delete_success_message'   => 'Batch delete successful',
    'batch_mark_sent_success'        => 'Batch mark as sent successful',
    'batch_mark_failed_success'      => 'Batch mark as failed successful',
    'unsupported_operation'          => 'Unsupported operation',
    'operation_failed_message'       => 'Operation failed: :error',
    'days_must_greater_than_zero'    => 'Days must be greater than 0',
    'cleanup_success_message'        => 'Successfully cleaned up :count records',
    'cleanup_failed_message'         => 'Cleanup failed: :error',
    'get_statistics_success'         => 'Get statistics successful',
    'get_statistics_failed'          => 'Get statistics failed: :error',
    'mail_already_sent'              => 'This email has been sent successfully, no need to resend',
    'resend_not_implemented_message' => 'Resend function not implemented yet, please contact developer',
    'resend_failed_message'          => 'Resend failed: :error',

    // Model translations
    'unknown_status'    => 'Unknown Status',
    'unknown_type'      => 'Unknown Type',
    'unknown_transport' => 'Unknown Transport',

    // Detail Page
    'basic_info'        => 'Basic Information',
    'mail_content'      => 'Mail Content',
    'attachments'       => 'Attachments',
    'headers'           => 'Mail Headers',
    'operation_history' => 'Operation History',
    'view_headers'      => 'View Detailed Headers',
    'mail_created'      => 'Mail Created',
    'mail_sent'         => 'Mail Sent',
    'mail_failed'       => 'Send Failed',
    'unknown_file'      => 'Unknown File',
    'system_default'    => 'System Default',
    'not_sent'          => 'Not Sent',

    // Permissions
    'permission_index'  => 'View Mail Logs',
    'permission_show'   => 'View Mail Details',
    'permission_delete' => 'Delete Mail Logs',
    'permission_update' => 'Update Mail Logs',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'View Mail Logs',
    'mail_logs_show'   => 'View Mail Details',
    'mail_logs_delete' => 'Delete Mail Logs',
    'mail_logs_update' => 'Update Mail Logs',

    // View translations
    'confirm_resend' => 'Are you sure you want to resend this email?',
    'confirm_delete' => 'Are you sure you want to delete this record?',
    'text_hint'      => 'Hint',
];
