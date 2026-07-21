<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'Failed to download plugin file: file content is empty',
    'unrecognized_beikeshop_plugin' => 'Unrecognized beikeshop plugin',
    'get_download_info_failed'      => 'Failed to get plugin download information',
    'missing_download_url'          => 'Missing download URL in download information',
    'oss_download_empty_content'    => 'Plugin file downloaded from OSS is empty',
    'download_plugin_failed'        => 'Failed to download plugin',
    'oss_download_failed_status'    => 'OSS download failed, HTTP status code: :status',
    'oss_download_empty_file'       => 'File content downloaded from OSS is empty',
    'oss_download_request_failed'   => 'OSS download request failed',
    'oss_download_exception'        => 'OSS download exception',

    // ZIP file validation errors
    'file_too_short'     => 'File content is too short, not a valid ZIP file',
    'invalid_zip_format' => 'Downloaded file is not a valid ZIP format',
    'file_too_large'     => 'Plugin file is too large, exceeds :sizeMB limit',
    'file_too_small'     => 'Plugin file is too small, may be corrupted',

    // Extraction related errors
    'cannot_open_zip'               => 'Cannot open plugin ZIP file',
    'plugin_dir_info_incomplete'    => 'Plugin directory information is incomplete',
    'extract_to_target_failed'      => 'Failed to extract plugin to target directory',
    'extract_plugin_failed'         => 'Failed to extract plugin',
    'source_dir_not_exists'         => 'Source directory does not exist: :path',
    'rename_plugin_dir_failed'      => 'Failed to rename plugin directory',
    'create_plugin_dir_failed'      => 'Failed to create plugin directory: :path',
    'cannot_open_zip_or_not_exists' => 'Cannot open ZIP file or file does not exist!',

    // Validation related errors
    'invalid_plugin_code'              => 'Invalid plugin code format',
    'invalid_download_url_format'      => 'Invalid download URL format',
    'cannot_parse_download_url_domain' => 'Cannot parse download URL domain',
    'download_url_must_https'          => 'Download URL must use HTTPS protocol',

    // Download lock related
    'plugin_download_in_progress' => 'Plugin :plugin is currently being downloaded, please try again later',
];
