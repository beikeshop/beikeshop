<?php

/**
 * Lang.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-09-09 09:09:09
 * @modified   2023-09-08 07:19:38
 */

return [
    'order_success'           => 'ordine inviato con successo',
    'order_update'            => 'aggiornamento dello stato dell\'ordine',
    'order_success_info'      => 'Il tuo ordine è stato inviato con successo, quanto segue è il dettaglio dell\'ordine',
    'order_success'           => 'Il tuo ordine è stato inviato con successo',
    'not_oneself'             => 'le operazioni non personali possono essere ignorate. ',
    'customer_name'           => 'Gentile utente :name, ciao! ',
    'sincerely'               => 'CiZhi',
    'team'                    => 'squadra',
    'order_update_status'     => 'Lo stato del tuo ordine :number è aggiornato',
    'welcome_register'        => 'benvenuto alla registrazione',
    'new_register'            => 'Registrazione nuovo utente',
    'customer_name_line'      => 'Nome utente',
    'register_end'            => 'Completa la registrazione, fai clic sul pulsante in basso per tornare al centro commerciale. ',
    'btn_buy_now'             => 'acquista ora',
    'retrieve_password_title' => 'recupera password',
    'retrieve_password_text'  => 'Stai recuperando la tua password, fai clic sul pulsante in basso per completare l\'operazione. ',
    'retrieve_password_btn'   => 'Fai clic qui per verificare l\'email',
    'rma_success'             => 'Richiesta di assistenza post-vendita inviata con successo',
    'rma_success_admin'       => 'C\'è un nuovo ordine di assistenza post-vendita',
    'admin_name'              => 'Gentile amministratore, ciao! ',
    'rma_product'             => 'Informazioni sul prodotto',
    'new_order'               => 'Nuovo ordine',
    'order_update_admin'      => 'Lo stato dell\'ordine :number è stato aggiornato',

    // Messaggi di errore del trasporto email SendCloud
    'sendcloud_invalid_message_type'         => 'Il messaggio deve essere un\'istanza di Symfony\Component\Mime\Email',
    'sendcloud_send_failed'                  => 'Invio email SendCloud fallito',
    'sendcloud_from_address_empty'           => 'L\'indirizzo del mittente SendCloud non può essere vuoto',
    'sendcloud_from_address_invalid'         => 'Il formato dell\'indirizzo del mittente SendCloud non è valido: :address',
    'sendcloud_example_domain_not_supported' => 'SendCloud non supporta indirizzi di dominio di esempio: :address. Configura un indirizzo email del mittente reale nelle impostazioni del backend.',
    'sendcloud_to_address_empty'             => 'L\'indirizzo del destinatario SendCloud non può essere vuoto',
    'sendcloud_to_address_invalid'           => 'Il formato dell\'indirizzo del destinatario SendCloud non è valido: :address',
    'sendcloud_api_call_failed'              => 'Chiamata API SendCloud fallita',
    'sendcloud_api_error'                    => 'Errore SendCloud [:status_code]: :message',
    'sendcloud_send_success'                 => 'Email SendCloud inviata con successo',
];
