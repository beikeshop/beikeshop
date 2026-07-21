<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'Plugin-Datei-Download fehlgeschlagen: Dateiinhalt ist leer',
    'unrecognized_beikeshop_plugin' => 'Unbekanntes beikeshop-Plugin',
    'get_download_info_failed'      => 'Plugin-Download-Informationen konnten nicht abgerufen werden',
    'missing_download_url'          => 'Download-URL fehlt in den Download-Informationen',
    'oss_download_empty_content'    => 'Von OSS heruntergeladene Plugin-Datei ist leer',
    'download_plugin_failed'        => 'Plugin-Download fehlgeschlagen',
    'oss_download_failed_status'    => 'OSS-Download fehlgeschlagen, HTTP-Statuscode: :status',
    'oss_download_empty_file'       => 'Von OSS heruntergeladener Dateiinhalt ist leer',
    'oss_download_request_failed'   => 'OSS-Download-Anfrage fehlgeschlagen',
    'oss_download_exception'        => 'OSS-Download-Ausnahme',

    // ZIP file validation errors
    'file_too_short'     => 'Dateiinhalt ist zu kurz, keine gültige ZIP-Datei',
    'invalid_zip_format' => 'Heruntergeladene Datei ist kein gültiges ZIP-Format',
    'file_too_large'     => 'Plugin-Datei ist zu groß, überschreitet :sizeMB-Limit',
    'file_too_small'     => 'Plugin-Datei ist zu klein, möglicherweise beschädigt',

    // Extraction related errors
    'cannot_open_zip'               => 'Plugin-ZIP-Datei kann nicht geöffnet werden',
    'plugin_dir_info_incomplete'    => 'Plugin-Verzeichnisinformationen sind unvollständig',
    'extract_to_target_failed'      => 'Plugin-Extraktion in Zielverzeichnis fehlgeschlagen',
    'extract_plugin_failed'         => 'Plugin-Extraktion fehlgeschlagen',
    'source_dir_not_exists'         => 'Quellverzeichnis existiert nicht: :path',
    'rename_plugin_dir_failed'      => 'Plugin-Verzeichnisumbenennung fehlgeschlagen',
    'create_plugin_dir_failed'      => 'Plugin-Verzeichniserstellung fehlgeschlagen: :path',
    'cannot_open_zip_or_not_exists' => 'ZIP-Datei kann nicht geöffnet werden oder existiert nicht!',

    // Validation related errors
    'invalid_plugin_code'              => 'Ungültiges Plugin-Code-Format',
    'invalid_download_url_format'      => 'Ungültiges Download-URL-Format',
    'cannot_parse_download_url_domain' => 'Download-URL-Domain kann nicht analysiert werden',
    'download_url_must_https'          => 'Download-URL muss HTTPS-Protokoll verwenden',

    // Download-Sperre
    'plugin_download_in_progress' => 'Plugin :plugin wird gerade heruntergeladen, bitte versuchen Sie es später erneut',
];
