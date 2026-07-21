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
    'mail_logs' => 'E-Mail-Protokolle',
    'mail_log'  => 'E-Mail-Protokoll',
    'list'      => 'E-Mail-Protokoll-Liste',
    'detail'    => 'E-Mail-Details',

    // Fields
    'to_email'      => 'Empfänger-E-Mail',
    'to_name'       => 'Empfängername',
    'from_email'    => 'Absender-E-Mail',
    'from_name'     => 'Absendername',
    'subject'       => 'Betreff',
    'content'       => 'Inhalt',
    'mail_type'     => 'E-Mail-Typ',
    'transport'     => 'Transport',
    'status'        => 'Status',
    'error_message' => 'Fehlermeldung',
    'sent_at'       => 'Gesendet am',
    'created_at'    => 'Erstellt am',
    'updated_at'    => 'Aktualisiert am',

    // Status
    'status_pending' => 'Ausstehend',
    'status_sent'    => 'Gesendet',
    'status_failed'  => 'Fehlgeschlagen',

    // Mail Types
    'type_customer_registration' => 'Kundenregistrierung',
    'type_customer_forgotten'    => 'Passwort vergessen',
    'type_order_new'             => 'Neue Bestellung',
    'type_order_update'          => 'Bestellungsupdate',
    'type_rma_new'               => 'Rücksendeanfrage',
    'type_admin_forgotten'       => 'Admin Passwort vergessen',
    'type_other'                 => 'Andere',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'Protokoll',
    'transport_array'     => 'Array',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => 'Unbekannt',

    // Actions
    'view_detail'  => 'Details anzeigen',
    'resend'       => 'Erneut senden',
    'delete'       => 'Löschen',
    'batch_delete' => 'Stapellöschung',
    'cleanup'      => 'Alte Datensätze bereinigen',
    'statistics'   => 'Statistiken',
    'back_to_list' => 'Zurück zur Liste',

    // Filters
    'filter_status'     => 'Status',
    'filter_mail_type'  => 'E-Mail-Typ',
    'filter_transport'  => 'Transport',
    'filter_recipient'  => 'Empfänger',
    'filter_start_date' => 'Startdatum',
    'filter_end_date'   => 'Enddatum',
    'filter_submit'     => 'Filtern',
    'filter_reset'      => 'Zurücksetzen',
    'all_status'        => 'Alle Status',
    'all_types'         => 'Alle Typen',
    'all_transports'    => 'Alle Transporte',

    // Statistics
    'total_count'   => 'Gesamt',
    'sent_count'    => 'Gesendet',
    'failed_count'  => 'Fehlgeschlagen',
    'pending_count' => 'Ausstehend',
    'recent_count'  => 'Kürzlich',

    // Messages
    'no_records'             => 'Keine E-Mail-Datensätze gefunden',
    'delete_confirm'         => 'Sind Sie sicher, dass Sie diesen Datensatz löschen möchten?',
    'batch_delete_confirm'   => 'Sind Sie sicher, dass Sie die ausgewählten Datensätze löschen möchten?',
    'resend_confirm'         => 'Sind Sie sicher, dass Sie diese E-Mail erneut senden möchten?',
    'cleanup_confirm'        => 'Geben Sie die Anzahl der zu behaltenden Tage ein (ältere Datensätze werden gelöscht):',
    'cleanup_title'          => 'Alte Datensätze bereinigen',
    'delete_success'         => 'Erfolgreich gelöscht',
    'batch_delete_success'   => 'Stapellöschung erfolgreich',
    'resend_success'         => 'Erfolgreich erneut gesendet',
    'cleanup_success'        => 'Bereinigung erfolgreich',
    'operation_failed'       => 'Operation fehlgeschlagen',
    'delete_failed'          => 'Löschen fehlgeschlagen',
    'resend_failed'          => 'Erneutes Senden fehlgeschlagen',
    'cleanup_failed'         => 'Bereinigung fehlgeschlagen',
    'please_select_records'  => 'Bitte wählen Sie Datensätze zum Bearbeiten aus',
    'resend_not_available'   => 'Diese E-Mail wurde bereits erfolgreich gesendet, kein erneutes Senden erforderlich',
    'resend_not_implemented' => 'Funktion zum erneuten Senden noch nicht implementiert, bitte kontaktieren Sie den Entwickler',
    'statistics_developing'  => 'Statistikfunktion in Entwicklung...',
    'total_records'          => 'Gesamt :count Datensätze',
    'recipient_placeholder'  => 'E-Mail-Adresse',

    // Controller messages
    'select_records_to_delete'       => 'Bitte wählen Sie Datensätze zum Löschen aus',
    'delete_success_message'         => 'Erfolgreich gelöscht',
    'delete_failed_message'          => 'Löschen fehlgeschlagen: :error',
    'select_records_to_operate'      => 'Bitte wählen Sie Datensätze zum Bearbeiten aus',
    'batch_delete_success_message'   => 'Stapellöschung erfolgreich',
    'batch_mark_sent_success'        => 'Stapelmarkierung als gesendet erfolgreich',
    'batch_mark_failed_success'      => 'Stapelmarkierung als fehlgeschlagen erfolgreich',
    'unsupported_operation'          => 'Nicht unterstützte Operation',
    'operation_failed_message'       => 'Operation fehlgeschlagen: :error',
    'days_must_greater_than_zero'    => 'Tage müssen größer als 0 sein',
    'cleanup_success_message'        => ':count Datensätze erfolgreich bereinigt',
    'cleanup_failed_message'         => 'Bereinigung fehlgeschlagen: :error',
    'get_statistics_success'         => 'Statistiken erfolgreich abgerufen',
    'get_statistics_failed'          => 'Statistiken abrufen fehlgeschlagen: :error',
    'mail_already_sent'              => 'Diese E-Mail wurde bereits erfolgreich gesendet, kein erneutes Senden erforderlich',
    'resend_not_implemented_message' => 'Funktion zum erneuten Senden noch nicht implementiert, bitte kontaktieren Sie den Entwickler',
    'resend_failed_message'          => 'Erneutes Senden fehlgeschlagen: :error',

    // Model translations
    'unknown_status'    => 'Unbekannter Status',
    'unknown_type'      => 'Unbekannter Typ',
    'unknown_transport' => 'Unbekannter Transport',

    // Detail Page
    'basic_info'        => 'Grundinformationen',
    'mail_content'      => 'E-Mail-Inhalt',
    'attachments'       => 'Anhänge',
    'headers'           => 'E-Mail-Header',
    'operation_history' => 'Operationshistorie',
    'view_headers'      => 'Detaillierte Header anzeigen',
    'mail_created'      => 'E-Mail erstellt',
    'mail_sent'         => 'E-Mail gesendet',
    'mail_failed'       => 'Senden fehlgeschlagen',
    'unknown_file'      => 'Unbekannte Datei',
    'system_default'    => 'Systemstandard',
    'not_sent'          => 'Nicht gesendet',

    // Permissions
    'permission_index'  => 'E-Mail-Protokolle anzeigen',
    'permission_show'   => 'E-Mail-Details anzeigen',
    'permission_delete' => 'E-Mail-Protokolle löschen',
    'permission_update' => 'E-Mail-Protokolle aktualisieren',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'E-Mail-Protokolle anzeigen',
    'mail_logs_show'   => 'E-Mail-Details anzeigen',
    'mail_logs_delete' => 'E-Mail-Protokolle löschen',
    'mail_logs_update' => 'E-Mail-Protokolle aktualisieren',

    // View translations
    'confirm_resend' => 'Sind Sie sicher, dass Sie diese E-Mail erneut senden möchten?',
    'confirm_delete' => 'Sind Sie sicher, dass Sie diesen Datensatz löschen möchten?',
    'text_hint'      => 'Hinweis',
];
