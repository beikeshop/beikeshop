<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'プラグインファイルのダウンロードに失敗しました：ファイルの内容が空です',
    'unrecognized_beikeshop_plugin' => '認識されないbeikeshopプラグイン',
    'get_download_info_failed'      => 'プラグインのダウンロード情報の取得に失敗しました',
    'missing_download_url'          => 'ダウンロード情報にダウンロードURLがありません',
    'oss_download_empty_content'    => 'OSSからダウンロードされたプラグインファイルが空です',
    'download_plugin_failed'        => 'プラグインのダウンロードに失敗しました',
    'oss_download_failed_status'    => 'OSSダウンロードに失敗しました、HTTPステータスコード: :status',
    'oss_download_empty_file'       => 'OSSからダウンロードされたファイルの内容が空です',
    'oss_download_request_failed'   => 'OSSダウンロードリクエストに失敗しました',
    'oss_download_exception'        => 'OSSダウンロード例外',

    // ZIP file validation errors
    'file_too_short'     => 'ファイルの内容が短すぎます、有効なZIPファイルではありません',
    'invalid_zip_format' => 'ダウンロードされたファイルは有効なZIP形式ではありません',
    'file_too_large'     => 'プラグインファイルが大きすぎます、:sizeMBの制限を超えています',
    'file_too_small'     => 'プラグインファイルが小さすぎます、破損している可能性があります',

    // Extraction related errors
    'cannot_open_zip'               => 'プラグインZIPファイルを開くことができません',
    'plugin_dir_info_incomplete'    => 'プラグインディレクトリ情報が不完全です',
    'extract_to_target_failed'      => 'プラグインのターゲットディレクトリへの展開に失敗しました',
    'extract_plugin_failed'         => 'プラグインの展開に失敗しました',
    'source_dir_not_exists'         => 'ソースディレクトリが存在しません: :path',
    'rename_plugin_dir_failed'      => 'プラグインディレクトリの名前変更に失敗しました',
    'create_plugin_dir_failed'      => 'プラグインディレクトリの作成に失敗しました: :path',
    'cannot_open_zip_or_not_exists' => 'ZIPファイルを開くことができないか、ファイルが存在しません！',

    // Validation related errors
    'invalid_plugin_code'              => '無効なプラグインコード形式',
    'invalid_download_url_format'      => '無効なダウンロードURL形式',
    'cannot_parse_download_url_domain' => 'ダウンロードURLドメインを解析できません',
    'download_url_must_https'          => 'ダウンロードURLはHTTPSプロトコルを使用する必要があります',

    // ダウンロードロック
    'plugin_download_in_progress' => 'プラグイン :plugin は現在ダウンロード中です。後でもう一度お試しください',
];
