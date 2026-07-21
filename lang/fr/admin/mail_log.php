<?php

/**
 * mail_log.php
 *
 * @copyright  2025 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2025-07-22
 */

return [
    'mail_logs' => 'Journaux de courrier',
    'mail_log'  => 'Journal de courrier',
    'list'      => 'Liste des journaux de courrier',
    'detail'    => 'Détails du courrier',

    // Fields
    'to_email'      => 'Email du destinataire',
    'to_name'       => 'Nom du destinataire',
    'from_email'    => 'Email de l\'expéditeur',
    'from_name'     => 'Nom de l\'expéditeur',
    'subject'       => 'Sujet',
    'content'       => 'Contenu',
    'mail_type'     => 'Type de courrier',
    'transport'     => 'Transport',
    'status'        => 'Statut',
    'error_message' => 'Message d\'erreur',
    'sent_at'       => 'Envoyé à',
    'created_at'    => 'Créé à',
    'updated_at'    => 'Mis à jour à',

    // Status
    'status_pending' => 'En attente',
    'status_sent'    => 'Envoyé',
    'status_failed'  => 'Échec',

    // Mail Types
    'type_customer_registration' => 'Inscription client',
    'type_customer_forgotten'    => 'Mot de passe oublié',
    'type_order_new'             => 'Nouvelle commande',
    'type_order_update'          => 'Mise à jour de commande',
    'type_rma_new'               => 'Demande de retour',
    'type_admin_forgotten'       => 'Admin mot de passe oublié',
    'type_other'                 => 'Autre',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'Journal',
    'transport_array'     => 'Tableau',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => 'Inconnu',

    // Actions
    'view_detail'  => 'Voir les détails',
    'resend'       => 'Renvoyer',
    'delete'       => 'Supprimer',
    'batch_delete' => 'Suppression en lot',
    'cleanup'      => 'Nettoyer les anciens enregistrements',
    'statistics'   => 'Statistiques',
    'back_to_list' => 'Retour à la liste',

    // Filters
    'filter_status'     => 'Statut',
    'filter_mail_type'  => 'Type de courrier',
    'filter_transport'  => 'Transport',
    'filter_recipient'  => 'Destinataire',
    'filter_start_date' => 'Date de début',
    'filter_end_date'   => 'Date de fin',
    'filter_submit'     => 'Filtrer',
    'filter_reset'      => 'Réinitialiser',
    'all_status'        => 'Tous les statuts',
    'all_types'         => 'Tous les types',
    'all_transports'    => 'Tous les transports',

    // Statistics
    'total_count'   => 'Total',
    'sent_count'    => 'Envoyé',
    'failed_count'  => 'Échec',
    'pending_count' => 'En attente',
    'recent_count'  => 'Récent',

    // Messages
    'no_records'             => 'Aucun enregistrement de courrier trouvé',
    'delete_confirm'         => 'Êtes-vous sûr de vouloir supprimer cet enregistrement?',
    'batch_delete_confirm'   => 'Êtes-vous sûr de vouloir supprimer les enregistrements sélectionnés?',
    'resend_confirm'         => 'Êtes-vous sûr de vouloir renvoyer ce courrier?',
    'cleanup_confirm'        => 'Entrez le nombre de jours à conserver (les enregistrements plus anciens seront supprimés):',
    'cleanup_title'          => 'Nettoyer les anciens enregistrements',
    'delete_success'         => 'Supprimé avec succès',
    'batch_delete_success'   => 'Suppression en lot réussie',
    'resend_success'         => 'Renvoyé avec succès',
    'cleanup_success'        => 'Nettoyage réussi',
    'operation_failed'       => 'Opération échouée',
    'delete_failed'          => 'Suppression échouée',
    'resend_failed'          => 'Renvoi échoué',
    'cleanup_failed'         => 'Nettoyage échoué',
    'please_select_records'  => 'Veuillez sélectionner des enregistrements à traiter',
    'resend_not_available'   => 'Ce courrier a été envoyé avec succès, pas besoin de le renvoyer',
    'resend_not_implemented' => 'Fonction de renvoi pas encore implémentée, veuillez contacter le développeur',
    'statistics_developing'  => 'Fonction de statistiques en développement...',
    'total_records'          => 'Total :count enregistrements',
    'recipient_placeholder'  => 'Adresse email',

    // Controller messages
    'select_records_to_delete'       => 'Veuillez sélectionner des enregistrements à supprimer',
    'delete_success_message'         => 'Supprimé avec succès',
    'delete_failed_message'          => 'Suppression échouée: :error',
    'select_records_to_operate'      => 'Veuillez sélectionner des enregistrements à traiter',
    'batch_delete_success_message'   => 'Suppression en lot réussie',
    'batch_mark_sent_success'        => 'Marquage en lot comme envoyé réussi',
    'batch_mark_failed_success'      => 'Marquage en lot comme échoué réussi',
    'unsupported_operation'          => 'Opération non supportée',
    'operation_failed_message'       => 'Opération échouée: :error',
    'days_must_greater_than_zero'    => 'Les jours doivent être supérieurs à 0',
    'cleanup_success_message'        => ':count enregistrements nettoyés avec succès',
    'cleanup_failed_message'         => 'Nettoyage échoué: :error',
    'get_statistics_success'         => 'Obtention des statistiques réussie',
    'get_statistics_failed'          => 'Obtention des statistiques échouée: :error',
    'mail_already_sent'              => 'Ce courrier a été envoyé avec succès, pas besoin de le renvoyer',
    'resend_not_implemented_message' => 'Fonction de renvoi pas encore implémentée, veuillez contacter le développeur',
    'resend_failed_message'          => 'Renvoi échoué: :error',

    // Model translations
    'unknown_status'    => 'Statut inconnu',
    'unknown_type'      => 'Type inconnu',
    'unknown_transport' => 'Transport inconnu',

    // Detail Page
    'basic_info'        => 'Informations de base',
    'mail_content'      => 'Contenu du courrier',
    'attachments'       => 'Pièces jointes',
    'headers'           => 'En-têtes du courrier',
    'operation_history' => 'Historique des opérations',
    'view_headers'      => 'Voir les en-têtes détaillés',
    'mail_created'      => 'Courrier créé',
    'mail_sent'         => 'Courrier envoyé',
    'mail_failed'       => 'Envoi échoué',
    'unknown_file'      => 'Fichier inconnu',
    'system_default'    => 'Système par défaut',
    'not_sent'          => 'Non envoyé',

    // Permissions
    'permission_index'  => 'Voir les journaux de courrier',
    'permission_show'   => 'Voir les détails du courrier',
    'permission_delete' => 'Supprimer les journaux de courrier',
    'permission_update' => 'Mettre à jour les journaux de courrier',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'Voir les journaux de courrier',
    'mail_logs_show'   => 'Voir les détails du courrier',
    'mail_logs_delete' => 'Supprimer les journaux de courrier',
    'mail_logs_update' => 'Mettre à jour les journaux de courrier',

    // View translations
    'confirm_resend' => 'Êtes-vous sûr de vouloir renvoyer ce courrier?',
    'confirm_delete' => 'Êtes-vous sûr de vouloir supprimer cet enregistrement?',
    'text_hint'      => 'Indice',
];
