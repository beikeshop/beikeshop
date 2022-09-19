<?php
/**
 * account.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-04 10:59:15
 * @modified   2022-08-04 10:59:15
 */

return [
     'index' => 'persönliches Zentrum',
     'revise_info' => 'Informationen überarbeiten',
     'collect' => 'Sammlung',
     'coupon' => 'Gutschein',
     'my_order' => 'Meine Bestellung',
     'orders' => 'alle Bestellungen',
     'pending_payment' => 'zu zahlen',
     'pending_send' => 'zu versenden',
     'pending_receipt' => 'zu empfangen',
     'after_sales' => 'Kundendienst',
     'no_order' => 'Du hast noch keine Bestellung! ',
     'to_buy' => 'Zur Bestellung gehen',
     'order_number' => 'Bestellnummer',
     'order_time' => 'Bestellzeit',
     'state' => 'Staat',
     'amount' => 'Betrag',
     'check_details' => 'Details prüfen',
     'all' => 'Gesamt',
     'items' => 'Artikel',
     'verify_code_expired' => 'Ihr Bestätigungscode ist abgelaufen (10 Minuten), bitte fordern Sie ihn erneut an',
     'verify_code_error' => 'Ihr Bestätigungscode ist falsch',
     'account_not_exist' => 'Konto existiert nicht',

    'edit' => [
         'index' => 'persönliche Daten ändern',
         'modify_avatar' => 'Avatar ändern',
         'suggest' => 'JPG- oder PNG-Bild hochladen. 300 x 300 wird empfohlen. ',
         'name' => 'Name',
         'emil' => 'Postfach',
         'crop' => 'Ernte',
         'password_edit_success' => 'Passwort erfolgreich geändert',
         'origin_password_fail' => 'ursprünglicher Passwortfehler',
    ],

    'wishlist' => [
         'index' => 'Meine Favoriten',
         'Produkt' => 'Produkt',
         'Prce' => 'Preis',
         'check_details' => 'Details prüfen',
    ],

    'order' => [
         'index' => 'Meine Bestellung',
         'completed' => 'bestätigter Eingang',
         'cancelled' => 'Bestellung storniert',
         'order_details' => 'Bestelldetails',
         'amount' => 'Betrag',
         'state' => 'Staat',
         'order_number' => 'Bestellnummer',
         'check' => 'prüfen',

        'order_info' => [
             'index' => 'Bestelldetails',
             'order_details' => 'Bestelldetails',
             'to_pay' => 'bezahlen',
             'cancel' => 'Bestellung stornieren',
             'confirm_receipt' => 'Empfang bestätigen',
             'order_number' => 'Bestellnummer',
             'order_date' => 'Bestelldatum',
             'state' => 'Staat',
             'order_amount' => 'Bestellbetrag',
             'order_items' => 'Artikel bestellen',
             'apply_after_sales' => 'Nach dem Verkauf bewerben',
             'order_total' => 'Gesamtbestellung',
             'logistics_status' => 'Logistikstatus',
             'order_status' => 'Bestellstatus',
             'remark' => 'Bemerkung',
             'update_time' => 'Aktualisierungszeit',
        ],

        'order_success' => [
             'order_success' => 'Herzlichen Glückwunsch, die Bestellung wurde erfolgreich generiert! ',
             'order_number' => 'Bestellnummer',
             'amounts_payable' => 'Zahlungsbetrag',
             'payment_method' => 'Zahlungsmethode',
             'view_order' => 'Bestelldetails anzeigen',
             'pay_now' => 'jetzt bezahlen',
             'kind_tips' => 'Herzliche Erinnerung: Ihre Bestellung wurde erfolgreich generiert, bitte schließen Sie die Zahlung so schnell wie möglich ab~',
             'also' => 'Du kannst auch',
             'continue_purchase' => 'mit dem Kauf fortfahren',
             'contact_customer_service' => 'Bei Fragen während des Bestellvorgangs können Sie sich jederzeit an unseren Kundenservice wenden',
             'email' => 'Postfach',
             'service_hotline' => 'Service-Hotline',
        ],

    ],

    'addresses' => [
         'index' => 'meine Adresse',
         'add_address' => 'Neue Adresse hinzufügen',
         'default_address' => 'Standardadresse',
         'delete' => 'löschen',
         'edit' => 'bearbeiten',
         'enter_name' => 'Bitte geben Sie Ihren Namen ein',
         'enter_phone' => 'Bitte geben Sie die Telefonnummer ein',
         'enter_address' => 'Bitte genaue Adresse 1 eingeben',
         'select_province' => 'Bitte wählen Sie eine Provinz aus',
         'enter_city' => 'Bitte Stadt eingeben',
         'confirm_delete' => 'Sind Sie sicher, dass Sie die Adresse löschen möchten? ',
         'hint' => 'hinweis',
         'check_form' => 'Bitte überprüfen Sie, ob das Formular korrekt ausgefüllt ist',
    ],

    'rma' => [
         'index' => 'Mein Kundendienst',
         'commodity' => 'Ware',
         'quantity' => 'Menge',
         'service_type' => 'Diensttyp',
         'return_reason' => 'Rücksendegrund',
         'creation_time' => 'Erstellungszeit',
         'check' => 'prüfen',

        'rma_info' => [
            'index' => 'After-Sales-Details',
        ],

        'rma_form' => [
             'index' => 'Kundendienstinformationen übermitteln',
             'service_type' => 'Diensttyp',
             'return_quantity' => 'Rückgabemenge',
             'unpacked' => 'ausgepackt',
             'return_reason' => 'Rücksendegrund',
             'remark' => 'Bemerkung',
         ]
    ]
];
