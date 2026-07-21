<?php

return [
    'plugin' => [
        'max_file_size'        => env('PLUGIN_MAX_FILE_SIZE', 200 * 1024 * 1024),
        'min_file_size'        => env('PLUGIN_MIN_FILE_SIZE', 1024),
        'download_timeout'     => env('PLUGIN_DOWNLOAD_TIMEOUT', 300),
        'lock_ttl'             => env('PLUGIN_LOCK_TTL', 300),
        'plugin_dir'           => env('PLUGIN_DIR', 'plugins'),
        'download_lock_prefix' => env('PLUGIN_DOWNLOAD_LOCK_PREFIX', 'plugin_download_lock:'),
    ],
    'security' => [
        'allowed_mime_types' => [
            'application/zip',
            'application/x-zip-compressed',
        ],
        'dangerous_extensions'     => ['.exe', '.bat', '.cmd', '.scr', '.pif', '.com'],
        'scan_for_malicious_files' => env('PLUGIN_SCAN_MALICIOUS', false),
        'validate_file_content'    => env('PLUGIN_VALIDATE_CONTENT', true),
    ],
    'permissions' => [
        'directory'  => env('PLUGIN_DIR_PERMISSIONS', 0750),
        'file'       => env('PLUGIN_FILE_PERMISSIONS', 0640),
        'executable' => env('PLUGIN_EXEC_PERMISSIONS', 0750),
    ],
    'api' => [
        'local_endpoint' => env('PLUGIN_LOCAL_API_ENDPOINT', '/v1/plugins/{plugin}/download'),
        'oss_endpoint'   => env('PLUGIN_OSS_API_ENDPOINT', '/v2/plugins/{plugin}/download'),
    ],
    'download_sources' => [
        'local' => [
            'name'        => 'Local API',
            'description' => 'Download from local API server',
            'enabled'     => env('PLUGIN_LOCAL_DOWNLOAD_ENABLED', true),
        ],
        'oss' => [
            'name'        => 'OSS Storage',
            'description' => 'Download from OSS cloud storage',
            'enabled'     => env('PLUGIN_OSS_DOWNLOAD_ENABLED', true),
        ],
    ],
    'default_source' => env('PLUGIN_DEFAULT_DOWNLOAD_SOURCE', 'local'),
];
