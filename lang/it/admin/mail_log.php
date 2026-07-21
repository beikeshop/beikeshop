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
    'mail_logs' => 'Registri email',
    'mail_log'  => 'Registro email',
    'list'      => 'Elenco registri email',
    'detail'    => 'Dettagli email',

    // Fields
    'to_email'      => 'Email destinatario',
    'to_name'       => 'Nome destinatario',
    'from_email'    => 'Email mittente',
    'from_name'     => 'Nome mittente',
    'subject'       => 'Oggetto',
    'content'       => 'Contenuto',
    'mail_type'     => 'Tipo email',
    'transport'     => 'Trasporto',
    'status'        => 'Stato',
    'error_message' => 'Messaggio di errore',
    'sent_at'       => 'Inviato il',
    'created_at'    => 'Creato il',
    'updated_at'    => 'Aggiornato il',

    // Status
    'status_pending' => 'In attesa',
    'status_sent'    => 'Inviato',
    'status_failed'  => 'Fallito',

    // Mail Types
    'type_customer_registration' => 'Registrazione cliente',
    'type_customer_forgotten'    => 'Password dimenticata',
    'type_order_new'             => 'Nuovo ordine',
    'type_order_update'          => 'Aggiornamento ordine',
    'type_rma_new'               => 'Richiesta di reso',
    'type_admin_forgotten'       => 'Admin password dimenticata',
    'type_other'                 => 'Altro',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'Log',
    'transport_array'     => 'Array',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => 'Sconosciuto',

    // Actions
    'view_detail'  => 'Visualizza dettagli',
    'resend'       => 'Reinvia',
    'delete'       => 'Elimina',
    'batch_delete' => 'Eliminazione batch',
    'cleanup'      => 'Pulisci record vecchi',
    'statistics'   => 'Statistiche',
    'back_to_list' => 'Torna all\'elenco',

    // Filters
    'filter_status'     => 'Stato',
    'filter_mail_type'  => 'Tipo email',
    'filter_transport'  => 'Trasporto',
    'filter_recipient'  => 'Destinatario',
    'filter_start_date' => 'Data inizio',
    'filter_end_date'   => 'Data fine',
    'filter_submit'     => 'Filtra',
    'filter_reset'      => 'Reimposta',
    'all_status'        => 'Tutti gli stati',
    'all_types'         => 'Tutti i tipi',
    'all_transports'    => 'Tutti i trasporti',

    // Statistics
    'total_count'   => 'Totale',
    'sent_count'    => 'Inviato',
    'failed_count'  => 'Fallito',
    'pending_count' => 'In attesa',
    'recent_count'  => 'Recente',

    // Messages
    'no_records'             => 'Nessun record email trovato',
    'delete_confirm'         => 'Sei sicuro di voler eliminare questo record?',
    'batch_delete_confirm'   => 'Sei sicuro di voler eliminare i record selezionati?',
    'resend_confirm'         => 'Sei sicuro di voler reinviare questa email?',
    'cleanup_confirm'        => 'Inserisci il numero di giorni da mantenere (i record più vecchi verranno eliminati):',
    'cleanup_title'          => 'Pulisci record vecchi',
    'delete_success'         => 'Eliminato con successo',
    'batch_delete_success'   => 'Eliminazione batch riuscita',
    'resend_success'         => 'Reinviato con successo',
    'cleanup_success'        => 'Pulizia riuscita',
    'operation_failed'       => 'Operazione fallita',
    'delete_failed'          => 'Eliminazione fallita',
    'resend_failed'          => 'Reinvio fallito',
    'cleanup_failed'         => 'Pulizia fallita',
    'please_select_records'  => 'Seleziona record da operare',
    'resend_not_available'   => 'Questa email è stata inviata con successo, non è necessario reinviarla',
    'resend_not_implemented' => 'Funzione di reinvio non ancora implementata, contatta lo sviluppatore',
    'statistics_developing'  => 'Funzione statistiche in sviluppo...',
    'total_records'          => 'Totale :count record',
    'recipient_placeholder'  => 'Indirizzo email',

    // Controller messages
    'select_records_to_delete'       => 'Seleziona record da eliminare',
    'delete_success_message'         => 'Eliminato con successo',
    'delete_failed_message'          => 'Eliminazione fallita: :error',
    'select_records_to_operate'      => 'Seleziona record da operare',
    'batch_delete_success_message'   => 'Eliminazione batch riuscita',
    'batch_mark_sent_success'        => 'Marcatura batch come inviato riuscita',
    'batch_mark_failed_success'      => 'Marcatura batch come fallito riuscita',
    'unsupported_operation'          => 'Operazione non supportata',
    'operation_failed_message'       => 'Operazione fallita: :error',
    'days_must_greater_than_zero'    => 'I giorni devono essere maggiori di 0',
    'cleanup_success_message'        => 'Puliti con successo :count record',
    'cleanup_failed_message'         => 'Pulizia fallita: :error',
    'get_statistics_success'         => 'Ottenimento statistiche riuscito',
    'get_statistics_failed'          => 'Ottenimento statistiche fallito: :error',
    'mail_already_sent'              => 'Questa email è già stata inviata con successo, non è necessario reinviarla',
    'resend_not_implemented_message' => 'Funzione di reinvio non ancora implementata, contatta lo sviluppatore',
    'resend_failed_message'          => 'Reinvio fallito: :error',

    // Model translations
    'unknown_status'    => 'Stato sconosciuto',
    'unknown_type'      => 'Tipo sconosciuto',
    'unknown_transport' => 'Trasporto sconosciuto',

    // Detail Page
    'basic_info'        => 'Informazioni di base',
    'mail_content'      => 'Contenuto email',
    'attachments'       => 'Allegati',
    'headers'           => 'Intestazioni email',
    'operation_history' => 'Cronologia operazioni',
    'view_headers'      => 'Visualizza intestazioni dettagliate',
    'mail_created'      => 'Email creata',
    'mail_sent'         => 'Email inviata',
    'mail_failed'       => 'Invio fallito',
    'unknown_file'      => 'File sconosciuto',
    'system_default'    => 'Sistema predefinito',
    'not_sent'          => 'Non inviato',

    // Permissions
    'permission_index'  => 'Visualizza registri email',
    'permission_show'   => 'Visualizza dettagli email',
    'permission_delete' => 'Elimina registri email',
    'permission_update' => 'Aggiorna registri email',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'Visualizza registri email',
    'mail_logs_show'   => 'Visualizza dettagli email',
    'mail_logs_delete' => 'Elimina registri email',
    'mail_logs_update' => 'Aggiorna registri email',

    // View translations
    'confirm_resend' => 'Sei sicuro di voler reinviare questa email?',
    'confirm_delete' => 'Sei sicuro di voler eliminare questo record?',
    'text_hint'      => 'Suggerimento',
];
