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
    'index' => 'centro personale',
    'revise_info' => 'Modifica informazioni',
    'collect' => 'raccogliere',
    'coupon' => 'buono',
    'my_order' => '我Il mio ordine的订单',
    'orders' => 'tutti gli ordini',
    'pending_payment' => 'In attesa di Pagamento',
    'pending_send' => 'da consegnare',
    'pending_receipt' => 'in attesa di ricezione',
    'after_sales' => 'Dopo le vendite',
    'no_order' => 'Non hai ancora un ordine!',
    'to_buy' => 'Vai per effettuare un ordine',
    'order_number' => 'numero d\'ordine',
    'order_time' => 'tempo dell\'ordine',
    'state' => 'stato',
    'amount' => 'importo',
    'check_details' => 'controlla i dettagli',
    'all' => 'Totale',
    'items' => 'articoli',
    'verify_code_expired' => 'Il tuo codice di verifica è scaduto (10 minuti), per favore recuperalo',
    'verify_code_error' => 'Il tuo codice di verifica è sbagliato',
    'account_not_exist' => 'l\'account non esiste',

    'edit' => [
        'index' => 'modifica informazioni personali',
        'modify_avatar' => 'modifica avatar',
        'suggest' => 'Carica immagine JPG o PNG. Si consiglia 300 x 300. x 300。',
        'name' => 'nome',
        'email' => 'cassetta postale',
        'crop' => 'ritaglia',
        'password_edit_success' => 'Password modificata con successo',
        'origin_password_fail' => 'errore password originale',
    ],

    'wishlist' => [
        'index' => 'I miei preferiti',
        'product' => 'prodotto',
        'price' => 'prezzo',
        'check_details' => 'controlla i dettagli',
    ],

    'order' => [
        'index' => 'Il mio ordine',
        'completed' => 'ricevuta confermata',
        'cancelled' => 'Ordine annullato',
        'order_details' => 'dettagli dell\'ordine',
        'amount' => 'importo',
        'state' => 'stato',
        'order_number' => 'numero d\'ordine',
        'check' => 'verifica',

        'order_info' => [
            'index' => 'dettagli dell\'ordine',
            'order_details' => 'dettagli dell\'ordine',
            'to_pay' => 'to_pay',
            'cancel' => 'Annulla ordine',
            'confirm_receipt' => 'conferma ricevuta',
            'order_number' => 'numero d\'ordine',
            'order_date' => 'data dell\'ordine',
            'state' => 'stato',
            'order_amount' => 'Ammontare dell\'ordine',
            'order_items' => 'ordinare gli articoli',
            'apply_after_sales' => 'Richiedi il post-vendita',
            'order_total' => 'ordine totale',
            'logistics_status' => 'Stato logistico',
            'order_status' => 'Lo stato dell\'ordine',
            'remark' => 'Nota',
            'update_time' => 'tempo di aggiornamento',
        ],

        'order_success' => [
            'order_success' => 'Congratulazioni, l\'ordine è stato generato correttamente!',
            'order_number' => 'numero d\'ordine',
            'amounts_payable' => 'Importi da pagare ',
            'payment_method' => 'metodo di pagamento ',
            'view_order' => 'Visualizza i dettagli dell\'ordine ',
            'pay_now' => 'paga subito ',
            'kind_tips' => 'Promemoria: il tuo ordine è stato generato correttamente, completa il pagamento il prima possibile~ ',
            'also' => 'Puoi anche',
            'continue_purchase' => 'continuare ad acquistare',
            'contact_customer_service' => 'In caso di domande durante il processo di ordinazione, puoi contattare il nostro servizio clienti in qualsiasi momento',
            'emaill' => 'Posta',
            'service_hotline' => 'Linea diretta di servizio',
        ],

    ],

    'addresses' => [
        'index' => 'il mio indirizzo',
        'add_address' => 'Aggiungi un nuovo indirizzo',
        'default_address' => 'Indirizzo predefinito',
        'delete' => 'Elimina',
        'edit' => 'modificare',
        'enter_name' => 'Per favore digita il tuo nome',
        'enter_phone' => 'Per favore digita il tuo numero di telefono',
        'enter_address' => 'Si prega di inserire l\'indirizzo dettagliato 1',
        'select_province' => 'Si prega di selezionare la provincia',
        'enter_city' => 'Si prega di compilare la città',
        'confirm_delete' => 'Sei sicuro di voler eliminare l\'indirizzo?',
        'hint' => 'suggerimento',
        'check_form' => 'Si prega di verificare che il modulo sia compilato correttamente',
    ],

    'rma' => [
        'index' => 'il mio post vendita',
        'commodity' => 'merce',
        'quantity' => 'quantità',
        'service_type' => 'Tipo di servizio',
        'return_reason' => 'un motivo per ritornare',
        'creation_time' => 'tempo di creazione',
        'check' => 'Dai un\'occhiata',

        'rma_info' => [
            'index' => 'Dettagli post vendita',
        ],

        'rma_form' => [
            'index' => 'Invia informazioni post-vendita',
            'service_type' => 'Tipo di servizio',
            'return_quantity' => 'Quantità di reso',
            'unpacked' => 'disimballato',
            'return_reason' => 'un motivo per ritornare',
            'remark' => 'Nota',
        ]
    ]
];
