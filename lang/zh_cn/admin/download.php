<?php

return [
    // 下载相关错误信息
    'download_failed_empty_content' => '下载插件文件失败：文件内容为空',
    'unrecognized_beikeshop_plugin' => '无法识别的beikeshop插件',
    'get_download_info_failed'      => '获取插件下载信息失败',
    'missing_download_url'          => '下载信息中缺少下载链接',
    'oss_download_empty_content'    => '从OSS下载的插件文件为空',
    'download_plugin_failed'        => '下载插件失败',
    'oss_download_failed_status'    => 'OSS下载失败，HTTP状态码: :status',
    'oss_download_empty_file'       => '从OSS下载的文件内容为空',
    'oss_download_request_failed'   => 'OSS下载请求失败',
    'oss_download_exception'        => 'OSS下载异常',

    // ZIP文件验证错误
    'file_too_short'     => '文件内容过短，不是有效的ZIP文件',
    'invalid_zip_format' => '下载的文件不是有效的ZIP格式',
    'file_too_large'     => '插件文件过大，超过:sizeMB限制',
    'file_too_small'     => '插件文件过小，可能已损坏',

    // 解压相关错误
    'cannot_open_zip'               => '无法打开插件ZIP文件',
    'plugin_dir_info_incomplete'    => '插件目录信息不完整',
    'extract_to_target_failed'      => '解压插件到目标目录失败',
    'extract_plugin_failed'         => '解压插件失败',
    'source_dir_not_exists'         => '源目录不存在: :path',
    'rename_plugin_dir_failed'      => '重命名插件目录失败',
    'create_plugin_dir_failed'      => '创建插件目录失败: :path',
    'cannot_open_zip_or_not_exists' => '无法打开ZIP文件或文件不存在!',

    // 验证相关错误
    'invalid_plugin_code'              => '插件代码格式无效',
    'invalid_download_url_format'      => '无效的下载链接格式',
    'cannot_parse_download_url_domain' => '无法解析下载链接的域名',
    'download_url_must_https'          => '下载链接必须使用HTTPS协议',

    // 下载锁相关
    'plugin_download_in_progress' => '插件 :plugin 正在下载中，请稍后再试',
];
