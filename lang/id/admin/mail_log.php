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
    'mail_logs' => 'Log Email',
    'mail_log'  => 'Log Email',
    'list'      => 'Daftar Log Email',
    'detail'    => 'Detail Email',

    // Fields
    'to_email'      => 'Email Penerima',
    'to_name'       => 'Nama Penerima',
    'from_email'    => 'Email Pengirim',
    'from_name'     => 'Nama Pengirim',
    'subject'       => 'Subjek',
    'content'       => 'Konten',
    'mail_type'     => 'Jenis Email',
    'transport'     => 'Transport',
    'status'        => 'Status',
    'error_message' => 'Pesan Error',
    'sent_at'       => 'Dikirim Pada',
    'created_at'    => 'Dibuat Pada',
    'updated_at'    => 'Diperbarui Pada',

    // Status
    'status_pending' => 'Menunggu',
    'status_sent'    => 'Terkirim',
    'status_failed'  => 'Gagal',

    // Mail Types
    'type_customer_registration' => 'Registrasi Pelanggan',
    'type_customer_forgotten'    => 'Lupa Password',
    'type_order_new'             => 'Pesanan Baru',
    'type_order_update'          => 'Update Pesanan',
    'type_rma_new'               => 'Permintaan Pengembalian',
    'type_admin_forgotten'       => 'Admin Lupa Password',
    'type_other'                 => 'Lainnya',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'Log',
    'transport_array'     => 'Array',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => 'Tidak Diketahui',

    // Actions
    'view_detail'  => 'Lihat Detail',
    'resend'       => 'Kirim Ulang',
    'delete'       => 'Hapus',
    'batch_delete' => 'Hapus Massal',
    'cleanup'      => 'Bersihkan Record Lama',
    'statistics'   => 'Statistik',
    'back_to_list' => 'Kembali ke Daftar',

    // Filters
    'filter_status'     => 'Status',
    'filter_mail_type'  => 'Jenis Email',
    'filter_transport'  => 'Transport',
    'filter_recipient'  => 'Penerima',
    'filter_start_date' => 'Tanggal Mulai',
    'filter_end_date'   => 'Tanggal Akhir',
    'filter_submit'     => 'Filter',
    'filter_reset'      => 'Reset',
    'all_status'        => 'Semua Status',
    'all_types'         => 'Semua Jenis',
    'all_transports'    => 'Semua Transport',

    // Statistics
    'total_count'   => 'Total',
    'sent_count'    => 'Terkirim',
    'failed_count'  => 'Gagal',
    'pending_count' => 'Menunggu',
    'recent_count'  => 'Terbaru',

    // Messages
    'no_records'             => 'Tidak ada record email ditemukan',
    'delete_confirm'         => 'Apakah Anda yakin ingin menghapus record ini?',
    'batch_delete_confirm'   => 'Apakah Anda yakin ingin menghapus record yang dipilih?',
    'resend_confirm'         => 'Apakah Anda yakin ingin mengirim ulang email ini?',
    'cleanup_confirm'        => 'Masukkan jumlah hari untuk disimpan (record yang lebih lama akan dihapus):',
    'cleanup_title'          => 'Bersihkan Record Lama',
    'delete_success'         => 'Berhasil dihapus',
    'batch_delete_success'   => 'Hapus massal berhasil',
    'resend_success'         => 'Berhasil dikirim ulang',
    'cleanup_success'        => 'Pembersihan berhasil',
    'operation_failed'       => 'Operasi gagal',
    'delete_failed'          => 'Penghapusan gagal',
    'resend_failed'          => 'Pengiriman ulang gagal',
    'cleanup_failed'         => 'Pembersihan gagal',
    'please_select_records'  => 'Silakan pilih record untuk dioperasikan',
    'resend_not_available'   => 'Email ini sudah berhasil dikirim, tidak perlu dikirim ulang',
    'resend_not_implemented' => 'Fitur kirim ulang belum diimplementasikan, silakan hubungi developer',
    'statistics_developing'  => 'Fitur statistik sedang dalam pengembangan...',
    'total_records'          => 'Total :count record',
    'recipient_placeholder'  => 'Alamat email',

    // Controller messages
    'select_records_to_delete'       => 'Silakan pilih record untuk dihapus',
    'delete_success_message'         => 'Berhasil dihapus',
    'delete_failed_message'          => 'Penghapusan gagal: :error',
    'select_records_to_operate'      => 'Silakan pilih record untuk dioperasikan',
    'batch_delete_success_message'   => 'Hapus massal berhasil',
    'batch_mark_sent_success'        => 'Tandai massal sebagai terkirim berhasil',
    'batch_mark_failed_success'      => 'Tandai massal sebagai gagal berhasil',
    'unsupported_operation'          => 'Operasi tidak didukung',
    'operation_failed_message'       => 'Operasi gagal: :error',
    'days_must_greater_than_zero'    => 'Hari harus lebih besar dari 0',
    'cleanup_success_message'        => 'Berhasil membersihkan :count record',
    'cleanup_failed_message'         => 'Pembersihan gagal: :error',
    'get_statistics_success'         => 'Mendapatkan statistik berhasil',
    'get_statistics_failed'          => 'Mendapatkan statistik gagal: :error',
    'mail_already_sent'              => 'Email ini sudah berhasil dikirim, tidak perlu dikirim ulang',
    'resend_not_implemented_message' => 'Fitur kirim ulang belum diimplementasikan, silakan hubungi developer',
    'resend_failed_message'          => 'Pengiriman ulang gagal: :error',

    // Model translations
    'unknown_status'    => 'Status Tidak Diketahui',
    'unknown_type'      => 'Jenis Tidak Diketahui',
    'unknown_transport' => 'Transport Tidak Diketahui',

    // Detail Page
    'basic_info'        => 'Informasi Dasar',
    'mail_content'      => 'Konten Email',
    'attachments'       => 'Lampiran',
    'headers'           => 'Header Email',
    'operation_history' => 'Riwayat Operasi',
    'view_headers'      => 'Lihat Header Detail',
    'mail_created'      => 'Email Dibuat',
    'mail_sent'         => 'Email Dikirim',
    'mail_failed'       => 'Pengiriman Gagal',
    'unknown_file'      => 'File Tidak Diketahui',
    'system_default'    => 'Default Sistem',
    'not_sent'          => 'Tidak Dikirim',

    // Permissions
    'permission_index'  => 'Lihat Log Email',
    'permission_show'   => 'Lihat Detail Email',
    'permission_delete' => 'Hapus Log Email',
    'permission_update' => 'Update Log Email',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'Lihat Log Email',
    'mail_logs_show'   => 'Lihat Detail Email',
    'mail_logs_delete' => 'Hapus Log Email',
    'mail_logs_update' => 'Update Log Email',

    // View translations
    'confirm_resend' => 'Apakah Anda yakin ingin mengirim ulang email ini?',
    'confirm_delete' => 'Apakah Anda yakin ingin menghapus record ini?',
    'text_hint'      => 'Petunjuk',
];
