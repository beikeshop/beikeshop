<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'Download del file del plugin fallito: il contenuto del file è vuoto',
    'unrecognized_beikeshop_plugin' => 'Plugin beikeshop non riconosciuto',
    'get_download_info_failed'      => 'Impossibile ottenere le informazioni di download del plugin',
    'missing_download_url'          => 'URL di download mancante nelle informazioni di download',
    'oss_download_empty_content'    => 'Il file del plugin scaricato da OSS è vuoto',
    'download_plugin_failed'        => 'Download del plugin fallito',
    'oss_download_failed_status'    => 'Download OSS fallito, codice di stato HTTP: :status',
    'oss_download_empty_file'       => 'Il contenuto del file scaricato da OSS è vuoto',
    'oss_download_request_failed'   => 'Richiesta di download OSS fallita',
    'oss_download_exception'        => 'Eccezione di download OSS',

    // ZIP file validation errors
    'file_too_short'     => 'Il contenuto del file è troppo corto, non è un file ZIP valido',
    'invalid_zip_format' => 'Il file scaricato non è un formato ZIP valido',
    'file_too_large'     => 'Il file del plugin è troppo grande, supera il limite di :sizeMB',
    'file_too_small'     => 'Il file del plugin è troppo piccolo, potrebbe essere danneggiato',

    // Extraction related errors
    'cannot_open_zip'               => 'Impossibile aprire il file ZIP del plugin',
    'plugin_dir_info_incomplete'    => 'Le informazioni della directory del plugin sono incomplete',
    'extract_to_target_failed'      => 'Estrazione del plugin nella directory di destinazione fallita',
    'extract_plugin_failed'         => 'Estrazione del plugin fallita',
    'source_dir_not_exists'         => 'La directory sorgente non esiste: :path',
    'rename_plugin_dir_failed'      => 'Rinomina della directory del plugin fallita',
    'create_plugin_dir_failed'      => 'Creazione della directory del plugin fallita: :path',
    'cannot_open_zip_or_not_exists' => 'Impossibile aprire il file ZIP o il file non esiste!',

    // Validation related errors
    'invalid_plugin_code'              => 'Formato del codice del plugin non valido',
    'invalid_download_url_format'      => 'Formato URL di download non valido',
    'cannot_parse_download_url_domain' => 'Impossibile analizzare il dominio dell\'URL di download',
    'download_url_must_https'          => 'L\'URL di download deve utilizzare il protocollo HTTPS',

    // Blocco download
    'plugin_download_in_progress' => 'Il plugin :plugin è attualmente in download, riprova più tardi',
];
