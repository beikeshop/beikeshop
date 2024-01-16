<?php
/**
 * rma.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-22 19:27:35
 * @modified   2022-08-22 19:27:35
 */

return [
    'order_id'         => 'Ordine',
    'order_product_id' => 'articolo dell\'ordine',
    'customer_id'      => 'cliente',
    'quantity'         => 'quantità',
    'opened'           => 'disimballato',
    'rma_reason_id'    => 'un motivo per ritornare',
    'type'             => 'Tipologia di servizio post vendita',

    'status_pending'   => 'Essere processato',
    'status_rejected'  => 'respinto',
    'status_approved'  => 'Approvato (da restituire a cura del cliente)',
    'status_shipped'   => 'Spedito (articolo di ritorno)',
    'status_completed' => 'completato',
    'type_return'      => 'restituire la merce',
    'type_exchange'    => 'scambio',
    'type_repair'      => 'riparazione',
    'type_reissue'     => 'prodotto di ristampa',
    'type_refund'      => 'Solo rimborso',
];
