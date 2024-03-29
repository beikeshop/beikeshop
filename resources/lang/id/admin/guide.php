<?php

/**
 * order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    // Menuju
    'heading_title' => 'Panduan pemula',

    //Tab
    'tab_basic'            => 'Pengaturan dasar',
    'tab_bahasa'           => 'Beberapa bahasa dan mata uang',
    'tab_product'          => 'Buat produk',
    'tab_theme'            => 'Dekorasi toko',
    'tab_Payment_shipping' => 'Pembayaran dan Logistik',
    'tab_mail'             => 'Email konfigurasi',

    //Teks
    'text_extension'  => 'Ekstensi',
    'text_success'    => 'Sukses: Panduan pemula telah dimodifikasi! ',
    'text_edit'       => 'Panduan mengedit untuk pemula',
    'text_view'       => 'Tampilkan detail...',
    'text_greeting'   => 'Selamat, website Anda telah berhasil menginstal BeikeShop! ',
    'text_greeting_1' => 'Kami akan memandu Anda membuat beberapa konfigurasi kustom dasar pada sistem untuk membantu Anda memahami fungsi sistem BeikeShop dan mulai menggunakannya dengan cepat! ',
    'text_basic_1'    => 'Pertama, Anda dapat mengonfigurasi informasi penting berikut di pengaturan sistem:',
    'text_lingual_1'  => 'Sistem BeikeShop mendukung berbagai bahasa dan mata uang. Sebelum Anda mulai membuat produk pertama, Anda dapat memilih bahasa dan mata uang default di meja depan mal! ',
    'text_lingual_2'  => 'Jika Anda hanya perlu menggunakan satu bahasa dan mata uang, Anda dapat menghapus bahasa dan mata uang lainnya. Hindari kerumitan memasukkan informasi dalam berbagai bahasa saat membuat produk. ',
    'text_product_1'  => 'Selama instalasi sistem, beberapa data produk default akan diimpor secara otomatis untuk penggunaan demonstrasi. Anda dapat mencoba <a href="' . admin_route('products.create') . '">Buat produk</a> terlebih dahulu! ',
    'text_product_2'  => 'BeikeShop menyediakan kemampuan manajemen produk yang hebat! Termasuk: <a href="' . admin_route('categories.index') . '">Klasifikasi produk</a>, <a href="' . admin_route('brands.index') . '">Manajemen merek</a>, produk multi-spesifikasi, <a href="' . admin_route('multi_filter.index') . '">Pemfilteran lanjutan</a>, <a href="' . admin_route('attributes.index') . '">Atribut produk</a> dan fungsi lainnya. ',
    'text_theme_1'    => 'Sistem memiliki seperangkat templat tema default yang terinstal secara default. Jika tema default tidak memenuhi kebutuhan Anda, Anda juga dapat menggunakan <a href="' . admin_route('marketing.index', [' ketik' => ' tema']) . '">Pasar Plugin</a> untuk membeli tema templat lainnya. ',
    'text_theme_2'    => 'Selain itu, halaman beranda templat tema front-end disajikan oleh modul melalui tata letak. Anda mungkin perlu menyesuaikan beberapa pengaturan modul melalui tata letak. ',
    'text_theme_3'    => 'Jika Anda membeli APLIKASI, kami juga menyediakan fungsi khusus untuk <a href="' . admin_route('design_app_home.index') . '">Desain beranda APLIKASI</a>. ',
    'text_Payment_1'  => 'BeikeShop menyediakan saluran pembayaran luar negeri yang umum digunakan, seperti PayPal default, Stripe, dll. Sebelum melakukan pemesanan secara resmi, Anda harus mengaktifkan dan mengonfigurasi metode pembayaran yang sesuai. ',
    'text_Payment_2'  => 'Catatan: Beberapa aplikasi antarmuka pembayaran membutuhkan waktu lebih lama untuk ditinjau, harap ajukan permohonan terlebih dahulu. Metode pembayaran yang digunakan di Tiongkok mungkin memerlukan registrasi nama domain situs web. ',
    'text_Payment_3'  => 'Selain itu, Anda juga perlu mengatur metode pengiriman logistik yang dapat dipilih pelanggan. Sistem menyediakan plug-in biaya pengiriman tetap secara gratis. ',
    'text_Payment_4'  => 'Anda juga dapat mengunjungi BeikeShop<a href="' . admin_route('marketing.index') . '">"Plug-in Market"</a> untuk mempelajari dan mengunduh lebih banyak metode pembayaran dan logistik metode! ',
    'text_mail_1'     => 'Pemberitahuan email dapat terus memberi tahu pelanggan Anda tentang status pesanan, dan mereka juga dapat mendaftar dan mengambil kata sandi melalui email. Anda dapat mengonfigurasi SMTP sesuai dengan kebutuhan bisnis sebenarnya, dan mesin email seperti SendCloud digunakan untuk mengirim email. ',
    'text_mail_2'     => 'Pengingat hangat: Sering mengirim email dapat menyebabkan email Anda ditandai sebagai spam. Kami merekomendasikan penggunaan SendCloud (layanan berbayar) untuk mengirim email. ',

    // Tombol
    'button_setting_general' => 'Pengaturan dasar situs web',
    'button_setting_store'   => 'Nama situs web',
    'button_setting_logo'    => 'Ganti Logo',
    'button_setting_option'  => 'Pengaturan opsi',
    'button_setting'         => 'Semua pengaturan sistem',
    'button_lingual'         => 'Manajemen bahasa',
    'button_currency'        => 'Manajemen mata uang',
    'button_product'         => 'Lihat produk',
    'button_product_create'  => 'Buat produk',
    'button_theme_pc'        => 'Pengaturan templat',
    'button_theme_h5'        => 'Pengaturan tema seluler',
    'button_theme'           => 'Semua tema',
    'button_layout'          => 'Manajemen tata letak',
    'button_Payment'         => 'Metode pembayaran',
    'button_shipping'        => 'Metode pengiriman',
    'button_mail'            => 'Pengaturan email',
    'button_sms'             => 'Konfigurasi SMS',
    'button_hide'            => 'Jangan tampilkan lagi',
];
