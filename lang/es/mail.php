<?php

/**
 * Lang.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-09-09 09:09:09
 * @modified   2023-09-08 07:19:30
 */

return [
    'order_success'           => 'pedido enviado con éxito',
    'order_update'            => 'actualización del estado del pedido',
    'order_success_info'      => 'Su pedido se ha enviado con éxito, los siguientes son los detalles del pedido',
    'order_success'           => 'Su pedido se ha enviado con éxito',
    'not_oneself'             => 'las operaciones no personales pueden ignorarse. ',
    'customer_name'           => 'Estimado :name usuario, ¡hola! ',
    'sincerely'               => 'CiZhi',
    'team'                    => 'El equipo',
    'order_update_status'     => 'El estado de su pedido :number está actualizado',
    'welcome_register'        => 'bienvenido a registrarse',
    'new_register'            => 'Registro de nuevo usuario',
    'customer_name_line'      => 'nombre de usuario',
    'register_end'            => 'Complete el registro, haga clic en el botón de abajo para volver al centro comercial. ',
    'btn_buy_now'             => 'comprar ahora',
    'retrieve_password_title' => 'recuperar contraseña',
    'retrieve_password_text'  => 'Está recuperando su contraseña, haga clic en el botón de abajo para completar la operación. ',
    'retrieve_password_btn'   => 'Haga clic aquí para verificar el correo electrónico',
    'rma_success'             => 'Solicitud de servicio postventa enviada con éxito',
    'rma_success_admin'       => 'Hay un nuevo pedido de servicio postventa',
    'admin_name'              => 'Estimado administrador, ¡hola! ',
    'rma_product'             => 'Información del producto',
    'new_order'               => 'Nuevo pedido',
    'order_update_admin'      => 'El estado del pedido :number ha sido actualizado',

    // Mensajes de error de transporte de correo SendCloud
    'sendcloud_invalid_message_type'         => 'El mensaje debe ser una instancia de Symfony\Component\Mime\Email',
    'sendcloud_send_failed'                  => 'Error al enviar correo de SendCloud',
    'sendcloud_from_address_empty'           => 'La dirección del remitente de SendCloud no puede estar vacía',
    'sendcloud_from_address_invalid'         => 'El formato de la dirección del remitente de SendCloud es inválido: :address',
    'sendcloud_example_domain_not_supported' => 'SendCloud no admite direcciones de dominio de ejemplo: :address. Configure una dirección de correo electrónico del remitente real en la configuración del backend.',
    'sendcloud_to_address_empty'             => 'La dirección del destinatario de SendCloud no puede estar vacía',
    'sendcloud_to_address_invalid'           => 'El formato de la dirección del destinatario de SendCloud es inválido: :address',
    'sendcloud_api_call_failed'              => 'Error en la llamada a la API de SendCloud',
    'sendcloud_api_error'                    => 'Error de SendCloud [:status_code]: :message',
    'sendcloud_send_success'                 => 'Correo de SendCloud enviado exitosamente',
];
