<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'Gagal mengunduh file plugin: konten file kosong',
    'unrecognized_beikeshop_plugin' => 'Plugin beikeshop tidak dikenali',
    'get_download_info_failed'      => 'Gagal mendapatkan informasi unduhan plugin',
    'missing_download_url'          => 'URL unduhan hilang dalam informasi unduhan',
    'oss_download_empty_content'    => 'File plugin yang diunduh dari OSS kosong',
    'download_plugin_failed'        => 'Gagal mengunduh plugin',
    'oss_download_failed_status'    => 'Unduhan OSS gagal, kode status HTTP: :status',
    'oss_download_empty_file'       => 'Konten file yang diunduh dari OSS kosong',
    'oss_download_request_failed'   => 'Permintaan unduhan OSS gagal',
    'oss_download_exception'        => 'Pengecualian unduhan OSS',

    // ZIP file validation errors
    'file_too_short'     => 'Konten file terlalu pendek, bukan file ZIP yang valid',
    'invalid_zip_format' => 'File yang diunduh bukan format ZIP yang valid',
    'file_too_large'     => 'File plugin terlalu besar, melebihi batas :sizeMB',
    'file_too_small'     => 'File plugin terlalu kecil, mungkin rusak',

    // Extraction related errors
    'cannot_open_zip'               => 'Tidak dapat membuka file ZIP plugin',
    'plugin_dir_info_incomplete'    => 'Informasi direktori plugin tidak lengkap',
    'extract_to_target_failed'      => 'Gagal mengekstrak plugin ke direktori target',
    'extract_plugin_failed'         => 'Gagal mengekstrak plugin',
    'source_dir_not_exists'         => 'Direktori sumber tidak ada: :path',
    'rename_plugin_dir_failed'      => 'Gagal mengganti nama direktori plugin',
    'create_plugin_dir_failed'      => 'Gagal membuat direktori plugin: :path',
    'cannot_open_zip_or_not_exists' => 'Tidak dapat membuka file ZIP atau file tidak ada!',

    // Validation related errors
    'invalid_plugin_code'              => 'Format kode plugin tidak valid',
    'invalid_download_url_format'      => 'Format URL unduhan tidak valid',
    'cannot_parse_download_url_domain' => 'Tidak dapat mengurai domain URL unduhan',
    'download_url_must_https'          => 'URL unduhan harus menggunakan protokol HTTPS',

    // Kunci unduhan
    'plugin_download_in_progress' => 'Plugin :plugin sedang diunduh, silakan coba lagi nanti',
];
