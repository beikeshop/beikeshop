<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'Error al descargar archivo del plugin: el contenido del archivo está vacío',
    'unrecognized_beikeshop_plugin' => 'Plugin de beikeshop no reconocido',
    'get_download_info_failed'      => 'Error al obtener información de descarga del plugin',
    'missing_download_url'          => 'Falta URL de descarga en la información de descarga',
    'oss_download_empty_content'    => 'El archivo del plugin descargado desde OSS está vacío',
    'download_plugin_failed'        => 'Error al descargar plugin',
    'oss_download_failed_status'    => 'Descarga OSS fallida, código de estado HTTP: :status',
    'oss_download_empty_file'       => 'El contenido del archivo descargado desde OSS está vacío',
    'oss_download_request_failed'   => 'Solicitud de descarga OSS fallida',
    'oss_download_exception'        => 'Excepción de descarga OSS',

    // ZIP file validation errors
    'file_too_short'     => 'El contenido del archivo es muy corto, no es un archivo ZIP válido',
    'invalid_zip_format' => 'El archivo descargado no es un formato ZIP válido',
    'file_too_large'     => 'El archivo del plugin es muy grande, excede el límite de :sizeMB',
    'file_too_small'     => 'El archivo del plugin es muy pequeño, puede estar dañado',

    // Extraction related errors
    'cannot_open_zip'               => 'No se puede abrir el archivo ZIP del plugin',
    'plugin_dir_info_incomplete'    => 'La información del directorio del plugin está incompleta',
    'extract_to_target_failed'      => 'Error al extraer plugin al directorio de destino',
    'extract_plugin_failed'         => 'Error al extraer plugin',
    'source_dir_not_exists'         => 'El directorio fuente no existe: :path',
    'rename_plugin_dir_failed'      => 'Error al renombrar directorio del plugin',
    'create_plugin_dir_failed'      => 'Error al crear directorio del plugin: :path',
    'cannot_open_zip_or_not_exists' => 'No se puede abrir el archivo ZIP o el archivo no existe!',

    // Validation related errors
    'invalid_plugin_code'              => 'Formato de código de plugin inválido',
    'invalid_download_url_format'      => 'Formato de URL de descarga inválido',
    'cannot_parse_download_url_domain' => 'No se puede analizar el dominio de la URL de descarga',
    'download_url_must_https'          => 'La URL de descarga debe usar el protocolo HTTPS',

    // Bloqueo de descarga
    'plugin_download_in_progress' => 'El plugin :plugin se está descargando actualmente, inténtelo de nuevo más tarde',
];
