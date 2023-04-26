<?php
/**
 * account.php
 *
 * @copyright  2022 tuikehome.com - All Rights Reserved
 * @link       https://www.tuikehome.com
 * @author     Edward Yang <service@tuikehome.com>
 * @created    2022-08-04 10:59:15
 * @modified   2022-08-04 10:59:15
 */

return [
    'index'               => 'Personal Center',
    'revise_info'         => 'Modify Information',
    'collect'             => 'Collection',
    'coupon'              => 'Coupon',
    'my_order'            => 'My Orders',
    'orders'              => 'All Orders',
    'pending_payment'     => 'pending',
    'pending_send'        => 'To be shipped',
    'pending_receipt'     => 'To be received',
    'after_sales'         => 'After-sales',
    'no_order'            => 'You do not have an order yet！',
    'to_buy'              => 'Pergi dan pesan',
    'order_number'        => 'Nomor pesanan',
    'order_time'          => 'Waktu pemesanan',
    'state'               => 'Status',
    'amount'              => 'jumlah',
    'check_details'       => 'Lihat Detail',
    'all'                 => 'Umum',
    'items'               => 'Produk',
    'verify_code_expired' => 'Kode verifikasi Anda telah kedaluwarsa (10 menit), silakan dapatkan lagi',
    'verify_code_error'   => 'Captcha Anda salah',
    'account_not_exist'   => 'Akun tidak ada',

    'edit'                => [
        'index'                 => 'Koreksi Informasi Pribadi',
        'modify_avatar'         => 'Ubah avatar',
        'suggest'               => 'Unggah gambar JPG atau PNG. 300 x 300 direkomendasikan. ',
        'name'                  => 'Nama',
        'email'                 => 'kotak surat',
        'crop'                  => 'pangkas',
        'password_edit_success' => 'Kata sandi berhasil diubah',
        'origin_password_fail'  => 'kesalahan kata sandi asli',
    ],

    'wishlist'            => [
        'index'         => 'Koleksi Saya',
        'product'       => 'Komoditas',
        'price'         => 'Harga',
        'check_details' => 'Lihat Detail',
    ],

    'order'               => [
        'index'         => 'Pesanan Saya',
        'completed'     => 'Tanda terima dikonfirmasi',
        'cancelled'     => 'Pesanan dibatalkan',
        'order_details' => 'Detail pesanan',
        'amount'        => 'jumlah',
        'state'         => 'Status',
        'order_number'  => 'Nomor pesanan',
        'check'         => 'Lihat',

        'order_info'    => [
            'index'             => 'Detail pesanan',
            'order_details'     => 'Detail pesanan',
            'to_pay'            => 'Pergi bayar',
            'cancel'            => 'Batalkan Pesanan',
            'confirm_receipt'   => 'Pengakuan penerimaan',
            'order_number'      => 'Nomor pesanan',
            'order_date'        => 'Tanggal Pemesanan',
            'state'             => 'Status',
            'order_amount'      => 'Jumlah pesanan',
            'order_items'       => 'Pesan Barang',
            'apply_after_sales' => 'Terapkan untuk purna jual',
            'order_total'       => 'Total Pesanan',
            'logistics_status'  => 'Status logistik',
            'order_status'      => 'Status Pesanan',
            'remark'            => 'Keterangan',
            'update_time'       => 'Perbarui waktu',
        ],

        'order_success' => [
            'order_success'            => 'Selamat, pembuatan pesanan berhasil! ',
            'order_number'             => 'Nomor pesanan',
            'amounts_payable'          => 'Jumlah yang harus dibayar',
            'payment_method'           => 'Metode pembayaran',
            'view_order'               => 'Lihat detail pesanan',
            'pay_now'                  => 'Bayar Sekarang',
            'kind_tips'                => 'Tips: Pesanan Anda telah berhasil dibuat, harap selesaikan pembayaran sesegera mungkin ~ ',
            'also'                     => 'Kamu juga bisa',
            'continue_purchase'        => 'Lanjutkan pembelian',
            'contact_customer_service' => 'Jika Anda memiliki masalah selama proses pemesanan, Anda selalu dapat menghubungi layanan pelanggan kami',
            'emaill'                   => 'kotak surat',
            'service_hotline'          => 'Hotline Layanan',
        ],

    ],

    'addresses'           => [
        'index'           => 'Alamat Saya',
        'add_address'     => 'Tambahkan Alamat Baru',
        'default_address' => 'alamat default',
        'delete'          => 'hapus',
        'edit'            => 'Sunting',
        'enter_name'      => 'Silakan masukkan nama',
        'enter_phone'     => 'Silakan masukkan nomor kontak',
        'enter_address'   => 'Silakan masukkan alamat lengkap 1',
        'select_province' => 'Silakan pilih provinsi',
        'enter_city'      => 'Silakan isi kota',
        'confirm_delete'  => 'Anda yakin ingin menghapus alamatnya? ',
        'hint'            => 'Petunjuk',
        'check_form'      => 'Harap periksa apakah formulir diisi dengan benar',
    ],

    'rma'                 => [
        'index'         => 'Purna jual saya',
        'commodity'     => 'Komoditas',
        'quantity'      => 'kuantitas',
        'service_type'  => 'jenis layanan',
        'return_reason' => 'alasan pengembalian',
        'creation_time' => 'waktu penciptaan',
        'check'         => 'Lihat',

        'rma_info'      => [
            'index' => 'Detail purna jual',
        ],

        'rma_form'      => [
            'index'           => 'Kirim informasi purna jual',
            'service_type'    => 'jenis layanan',
            'return_quantity' => 'Jumlah pengembalian',
            'unpacked'        => 'Dibongkar',
            'return_reason'   => 'alasan pengembalian',
            'remark'          => 'Keterangan',
        ],
    ],
];
