<?php

return [
    // Download related error messages
    'download_failed_empty_content' => 'Échec du téléchargement du fichier du plugin : le contenu du fichier est vide',
    'unrecognized_beikeshop_plugin' => 'Plugin beikeshop non reconnu',
    'get_download_info_failed'      => 'Échec de l\'obtention des informations de téléchargement du plugin',
    'missing_download_url'          => 'URL de téléchargement manquante dans les informations de téléchargement',
    'oss_download_empty_content'    => 'Le fichier du plugin téléchargé depuis OSS est vide',
    'download_plugin_failed'        => 'Échec du téléchargement du plugin',
    'oss_download_failed_status'    => 'Téléchargement OSS échoué, code de statut HTTP : :status',
    'oss_download_empty_file'       => 'Le contenu du fichier téléchargé depuis OSS est vide',
    'oss_download_request_failed'   => 'Demande de téléchargement OSS échouée',
    'oss_download_exception'        => 'Exception de téléchargement OSS',

    // ZIP file validation errors
    'file_too_short'     => 'Le contenu du fichier est trop court, ce n\'est pas un fichier ZIP valide',
    'invalid_zip_format' => 'Le fichier téléchargé n\'est pas un format ZIP valide',
    'file_too_large'     => 'Le fichier du plugin est trop volumineux, dépasse la limite de :sizeMB',
    'file_too_small'     => 'Le fichier du plugin est trop petit, peut être corrompu',

    // Extraction related errors
    'cannot_open_zip'               => 'Impossible d\'ouvrir le fichier ZIP du plugin',
    'plugin_dir_info_incomplete'    => 'Les informations du répertoire du plugin sont incomplètes',
    'extract_to_target_failed'      => 'Échec de l\'extraction du plugin vers le répertoire cible',
    'extract_plugin_failed'         => 'Échec de l\'extraction du plugin',
    'source_dir_not_exists'         => 'Le répertoire source n\'existe pas : :path',
    'rename_plugin_dir_failed'      => 'Échec du renommage du répertoire du plugin',
    'create_plugin_dir_failed'      => 'Échec de la création du répertoire du plugin : :path',
    'cannot_open_zip_or_not_exists' => 'Impossible d\'ouvrir le fichier ZIP ou le fichier n\'existe pas !',

    // Validation related errors
    'invalid_plugin_code'              => 'Format de code de plugin invalide',
    'invalid_download_url_format'      => 'Format d\'URL de téléchargement invalide',
    'cannot_parse_download_url_domain' => 'Impossible d\'analyser le domaine de l\'URL de téléchargement',
    'download_url_must_https'          => 'L\'URL de téléchargement doit utiliser le protocole HTTPS',

    // Verrou de téléchargement
    'plugin_download_in_progress' => 'Le plugin :plugin est en cours de téléchargement, veuillez réessayer plus tard',
];
