<?php

return [
    // 下載相關錯誤訊息
    'download_failed_empty_content' => '下載插件檔案失敗：檔案內容為空',
    'unrecognized_beikeshop_plugin' => '無法識別的beikeshop插件',
    'get_download_info_failed'      => '獲取插件下載資訊失敗',
    'missing_download_url'          => '下載資訊中缺少下載連結',
    'oss_download_empty_content'    => '從OSS下載的插件檔案為空',
    'download_plugin_failed'        => '下載插件失敗',
    'oss_download_failed_status'    => 'OSS下載失敗，HTTP狀態碼: :status',
    'oss_download_empty_file'       => '從OSS下載的檔案內容為空',
    'oss_download_request_failed'   => 'OSS下載請求失敗',
    'oss_download_exception'        => 'OSS下載異常',

    // ZIP檔案驗證錯誤
    'file_too_short'     => '檔案內容過短，不是有效的ZIP檔案',
    'invalid_zip_format' => '下載的檔案不是有效的ZIP格式',
    'file_too_large'     => '插件檔案過大，超過:sizeMB限制',
    'file_too_small'     => '插件檔案過小，可能已損壞',

    // 解壓相關錯誤
    'cannot_open_zip'               => '無法開啟插件ZIP檔案',
    'plugin_dir_info_incomplete'    => '插件目錄資訊不完整',
    'extract_to_target_failed'      => '解壓插件到目標目錄失敗',
    'extract_plugin_failed'         => '解壓插件失敗',
    'source_dir_not_exists'         => '來源目錄不存在: :path',
    'rename_plugin_dir_failed'      => '重新命名插件目錄失敗',
    'create_plugin_dir_failed'      => '建立插件目錄失敗: :path',
    'cannot_open_zip_or_not_exists' => '無法開啟ZIP檔案或檔案不存在!',

    // 驗證相關錯誤
    'invalid_plugin_code'              => '插件代碼格式無效',
    'invalid_download_url_format'      => '無效的下載連結格式',
    'cannot_parse_download_url_domain' => '無法解析下載連結的網域',
    'download_url_must_https'          => '下載連結必須使用HTTPS協定',

    // 下載鎖相關
    'plugin_download_in_progress' => '插件 :plugin 正在下載中，請稍後再試',
];
