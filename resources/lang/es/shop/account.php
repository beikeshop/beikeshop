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
    'index' => 'centro personal',
    'revise_info' => 'Modificar información',
    'collect' => 'recoger',
    'coupon' => 'cupón',
    'my_order' => 'Mi pedido',
    'orders' => 'todas las órdenes',
    'pending_payment' => 'Pago pendiente',
    'pending_send' => 'para ser entregado',
    'pending_receipt' => 'recibo pendiente',
    'after_sales' => 'Después de las ventas',
    'no_order' => '¡Todavía no tienes un pedido!',
    'to_buy' => 'encargar',
    'order_number' => 'Número de orden',
    'order_time' => 'tiempo de la orden',
    'state' => 'estado',
    'amount' => 'Monto',
    'check_details' => 'revisa los detalles',
    'all' => 'común',
    'items' => 'Elementos',
    'verify_code_expired' => 'Su código de verificación ha caducado (10 minutos), recupérelo',
    'verify_code_error' => 'Tu código de verificación es incorrecto',
    'account_not_exist' => 'La cuenta no existe',

    'edit' => [
        'index' => 'Modificar información personal',
        'modify_avatar' => 'Modificar avatar',
        'suggest' => 'Sube una imagen JPG o PNG. Se recomienda 300 x 300.',
        'name' => 'nombre',
        'email' => 'Correo',
        'crop' => 'cultivo',
        'password_edit_success' => 'Restablecimiento de contraseña completo',
        'origin_password_fail' => 'La contraseña original es incorrecta',
    ],

    'wishlist' => [
        'index' => 'mi colección',
        'product' => 'producto',
        'price' => 'precio',
        'check_details' => 'revisa los detalles',
    ],

    'order' => [
        'index' => 'Mi pedido',
        'completed' => 'Recibo confirmado',
        'cancelled' => 'orden cancelada',
        'order_details' => 'detalles del pedido',
        'amount' => 'Monto',
        'state' => 'estado',
        'order_number' => 'Número de orden',
        'check' => 'Controlar',

        'order_info' => [
            'index' => 'detalles del pedido',
            'order_details' => 'detalles del pedido',
            'to_pay' => 'pagar',
            'cancel' => 'cancelar orden',
            'confirm_receipt' => 'confirmar la recepción de mercancías',
            'order_number' => 'Número de orden',
            'order_date' => 'fecha de orden',
            'state' => 'estado',
            'order_amount' => 'Total de la orden',
            'order_items' => 'encargar artículos',
            'apply_after_sales' => 'Solicitar posventa',
            'order_total' => 'orden total',
            'logistics_status' => 'Estado logístico',
            'order_status' => 'Estado del pedido',
            'remark' => 'Observación',
            'update_time' => '更新时间',
        ],

        'order_success' => [
            'order_success' => '¡Enhorabuena, el pedido se ha generado correctamente!',
            'order_number' => 'Número de orden',
            'amounts_payable' => 'cantidades a pagar ',
            'payment_method' => 'método de pago ',
            'view_order' => 'Ver detalles de la orden ',
            'pay_now' => 'paga inmediatamente ',
            'kind_tips' => 'Recordatorio: su pedido se ha generado correctamente, complete el pago lo antes posible ~ ',
            'also' => 'Tú también puedes',
            'continue_purchase' => 'seguir comprando',
            'contact_customer_service' => 'Si tiene alguna pregunta durante el proceso de pedido, puede comunicarse con nuestro personal de servicio al cliente en cualquier momento',
            'emaill' => 'Correo',
            'service_hotline' => 'Línea directa de servicio',
        ],

    ],

    'addresses' => [
        'index' => 'mi dirección',
        'add_address' => 'agregar nueva dirección',
        'default_address' => 'dirección predeterminada',
        'delete' => 'Eliminar',
        'edit' => 'editar',
        'enter_name' => 'Por favor escriba su nombre',
        'enter_phone' => 'Por favor escriba su número de teléfono',
        'enter_address' => 'Por favor ingrese la dirección detallada 1',
        'select_province' => 'Por favor seleccione provincia',
        'enter_city' => 'Por favor complete la ciudad',
        'confirm_delete' => '¿Está seguro de que desea eliminar la dirección?',
        'hint' => '提示',
        'check_form' => 'Por favor, compruebe que el formulario se ha rellenado correctamente.',
    ],

    'rma' => [
        'index' => 'mi posventa',
        'commodity' => 'producto',
        'quantity' => 'cantidad',
        'service_type' => 'Tipo de servicio',
        'return_reason' => 'razón para regresar',
        'creation_time' => 'tiempo de creación',
        'check' => 'Controlar',

        'rma_info' => [
            'index' => 'Detalles de posventa',
        ],

        'rma_form' => [
            'index' => 'Enviar información posventa',
            'service_type' => 'Tipo de servicio',
            'return_quantity' => 'Cantidad de devolución',
            'unpacked' => 'desempaquetado',
            'return_reason' => 'razón para regresar',
            'remark' => 'Observación',
        ]
    ]
];
