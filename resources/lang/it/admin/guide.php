<?php

/**
 * order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    // Intestazione
    'heading_title' => 'Guida per principianti',

    //Tab
    'tab_basic'            => 'Impostazioni di base',
    'tab_lingual'          => 'Più lingue e valute',
    'tab_product'          => 'Crea prodotto',
    'tab_theme'            => 'Decorazioni del negozio',
    'tab_payment_shipping' => 'Pagamento e logistica',
    'tab_mail'             => 'E-mail di configurazione',

    //Testo
    'text_extension'  => 'Estensione',
    'text_success'    => 'Successo: la guida per principianti è stata modificata! ',
    'text_edit'       => 'Guida alla modifica per i principianti',
    'text_view'       => 'Mostra dettagli...',
    'text_greeting'   => 'Congratulazioni, il tuo sito web ha installato con successo BeikeShop! ',
    'text_greeting_1' => 'Ti guideremo ad effettuare alcune configurazioni personalizzate di base sul sistema per aiutarti a comprendere le funzioni del sistema BeikeShop e iniziare a usarlo rapidamente! ',
    'text_basic_1'    => 'Per prima cosa, puoi configurare le seguenti informazioni importanti nelle impostazioni di sistema:',
    'text_lingual_1'  => 'Il sistema BeikeShop supporta più lingue e valute. Prima di iniziare a creare il tuo primo prodotto, puoi selezionare la lingua e la valuta predefinite alla reception del centro commerciale! ',
    'text_lingual_2'  => 'Se hai bisogno di utilizzare solo una lingua e una valuta, puoi eliminare le altre lingue e valute. Evita il fastidio di inserire informazioni in più lingue durante la creazione dei prodotti. ',
    'text_product_1'  => 'Durante l\'installazione del sistema, alcuni dati del prodotto predefinito verranno automaticamente importati per uso dimostrativo. Puoi provare prima a <a href="' . admin_route('products.create') . '">Crea prodotti</a>! ',
    'text_product_2'  => 'BeikeShop fornisce potenti funzionalità di gestione dei prodotti! Inclusi: <a href="' . admin_route('categories.index') . '">Classificazione del prodotto</a>, <a href="' . admin_route('brands.index') . '">Gestione del marchio</a>, prodotti con specifiche multiple, <a href="' . admin_route('multi_filter.index') . '">Filtro avanzato</a>, <a href="' . admin_route('attributes.index') . '">Attributi del prodotto</a> e altre funzioni. ',
    'text_theme_1'    => 'Il sistema ha una serie di modelli di temi predefiniti installati per impostazione predefinita. Se il tema predefinito non soddisfa le tue esigenze, puoi anche utilizzare <a href="' . admin_route('marketing.index', [' type' => ' theme']) . '">Plugin Market</a> per acquistare altri temi di template. ',
    'text_theme_2'    => 'Inoltre, la home page del modello del tema front-end è presentata dal modulo attraverso il layout. Potrebbe essere necessario regolare alcune impostazioni del modulo attraverso il layout. ',
    'text_theme_3'    => 'Se acquisti l\'APP, forniamo anche una funzione specifica per il <a href="' . admin_route('design_app_home.index') . '">design della home page dell\'APP</a>. ',
    'text_payment_1'  => 'BeikeShop fornisce canali di pagamento esteri comunemente utilizzati, come PayPal, Stripe, ecc. Prima di effettuare ufficialmente gli ordini, è necessario abilitare e configurare il metodo di pagamento corrispondente. ',
    'text_payment_2'  => 'Nota: alcune applicazioni dell\'interfaccia di pagamento richiedono più tempo per essere revisionate, si prega di richiederle in anticipo. I metodi di pagamento utilizzati in Cina potrebbero richiedere la registrazione del nome di dominio del sito web. ',
    'text_payment_3'  => 'Inoltre, devi anche impostare il metodo di consegna logistica che i clienti potranno scegliere. Il sistema fornisce gratuitamente un plug-in per una tariffa di spedizione fissa. ',
    'text_payment_4'  => 'Puoi anche andare su BeikeShop<a href="' . admin_route('marketing.index') . '">"Plug-in Market"</a> per scoprire e scaricare altri metodi di pagamento e logistica metodi! ',
    'text_mail_1'     => 'Le notifiche via email possono tenere informati i tuoi clienti sullo stato dell\'ordine e possono anche registrarsi e recuperare le password via email. È possibile configurare SMTP in base alle reali esigenze aziendali e per inviare e-mail vengono utilizzati motori di posta elettronica come SendCloud. ',
    'text_mail_2'     => 'Promemoria: l\'invio frequente di e-mail può far sì che le tue e-mail vengano contrassegnate come spam. Ti consigliamo di utilizzare SendCloud (servizio a pagamento) per inviare e-mail. ',

    // Pulsante
    'button_setting_general' => 'Impostazioni di base del sito web',
    'button_setting_store'   => 'Nome sito web',
    'button_setting_logo'    => 'Cambia logo',
    'button_setting_option'  => 'Impostazione opzioni',
    'button_setting'         => 'Tutte le impostazioni di sistema',
    'button_lingual'         => 'Gestione della lingua',
    'button_currency'        => 'Gestione valuta',
    'button_product'         => 'Visualizza prodotto',
    'button_product_create'  => 'Crea prodotto',
    'button_theme_pc'        => 'Impostazioni modello',
    'button_theme_h5'        => 'Impostazioni tema mobile',
    'button_theme'           => 'Tutti i temi',
    'button_layout'          => 'Gestione layout',
    'button_payment'         => 'Metodo di pagamento',
    'button_shipping'        => 'Metodo di spedizione',
    'button_mail'            => 'Impostazioni posta',
    'button_sms'             => 'Configurazione SMS',
    'button_hide'            => 'Non mostrare più',
];
