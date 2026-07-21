<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'Не удалось загрузить файл плагина: содержимое файла пустое',
    'unrecognized_beikeshop_plugin' => 'Неопознанный плагин beikeshop',
    'get_download_info_failed'      => 'Не удалось получить информацию о загрузке плагина',
    'missing_download_url'          => 'Отсутствует URL загрузки в информации о загрузке',
    'oss_download_empty_content'    => 'Файл плагина, загруженный из OSS, пуст',
    'download_plugin_failed'        => 'Не удалось загрузить плагин',
    'oss_download_failed_status'    => 'Загрузка OSS не удалась, HTTP статус код: :status',
    'oss_download_empty_file'       => 'Содержимое файла, загруженного из OSS, пустое',
    'oss_download_request_failed'   => 'Запрос загрузки OSS не удался',
    'oss_download_exception'        => 'Исключение загрузки OSS',

    // ZIP file validation errors
    'file_too_short'     => 'Содержимое файла слишком короткое, это не действительный ZIP файл',
    'invalid_zip_format' => 'Загруженный файл не является действительным ZIP форматом',
    'file_too_large'     => 'Файл плагина слишком большой, превышает лимит :sizeMB',
    'file_too_small'     => 'Файл плагина слишком маленький, возможно поврежден',

    // Extraction related errors
    'cannot_open_zip'               => 'Не удается открыть ZIP файл плагина',
    'plugin_dir_info_incomplete'    => 'Информация о директории плагина неполная',
    'extract_to_target_failed'      => 'Не удалось извлечь плагин в целевую директорию',
    'extract_plugin_failed'         => 'Не удалось извлечь плагин',
    'source_dir_not_exists'         => 'Исходная директория не существует: :path',
    'rename_plugin_dir_failed'      => 'Не удалось переименовать директорию плагина',
    'create_plugin_dir_failed'      => 'Не удалось создать директорию плагина: :path',
    'cannot_open_zip_or_not_exists' => 'Не удается открыть ZIP файл или файл не существует!',

    // Validation related errors
    'invalid_plugin_code'              => 'Неверный формат кода плагина',
    'invalid_download_url_format'      => 'Неверный формат URL загрузки',
    'cannot_parse_download_url_domain' => 'Не удается разобрать домен URL загрузки',
    'download_url_must_https'          => 'URL загрузки должен использовать протокол HTTPS',

    // Блокировка загрузки
    'plugin_download_in_progress' => 'Плагин :plugin в настоящее время загружается, попробуйте позже',
];
