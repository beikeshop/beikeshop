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
    'mail_logs' => 'Registros de correo',
    'mail_log'  => 'Registro de correo',
    'list'      => 'Lista de registros de correo',
    'detail'    => 'Detalles del correo',

    // Fields
    'to_email'      => 'Email del destinatario',
    'to_name'       => 'Nombre del destinatario',
    'from_email'    => 'Email del remitente',
    'from_name'     => 'Nombre del remitente',
    'subject'       => 'Asunto',
    'content'       => 'Contenido',
    'mail_type'     => 'Tipo de correo',
    'transport'     => 'Transporte',
    'status'        => 'Estado',
    'error_message' => 'Mensaje de error',
    'sent_at'       => 'Enviado en',
    'created_at'    => 'Creado en',
    'updated_at'    => 'Actualizado en',

    // Status
    'status_pending' => 'Pendiente',
    'status_sent'    => 'Enviado',
    'status_failed'  => 'Fallido',

    // Mail Types
    'type_customer_registration' => 'Registro de cliente',
    'type_customer_forgotten'    => 'Contraseña olvidada',
    'type_order_new'             => 'Nuevo pedido',
    'type_order_update'          => 'Actualización de pedido',
    'type_rma_new'               => 'Solicitud de devolución',
    'type_admin_forgotten'       => 'Admin contraseña olvidada',
    'type_other'                 => 'Otro',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'Registro',
    'transport_array'     => 'Array',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => 'Desconocido',

    // Actions
    'view_detail'  => 'Ver detalles',
    'resend'       => 'Reenviar',
    'delete'       => 'Eliminar',
    'batch_delete' => 'Eliminación en lote',
    'cleanup'      => 'Limpiar registros antiguos',
    'statistics'   => 'Estadísticas',
    'back_to_list' => 'Volver a la lista',

    // Filters
    'filter_status'     => 'Estado',
    'filter_mail_type'  => 'Tipo de correo',
    'filter_transport'  => 'Transporte',
    'filter_recipient'  => 'Destinatario',
    'filter_start_date' => 'Fecha de inicio',
    'filter_end_date'   => 'Fecha de fin',
    'filter_submit'     => 'Filtrar',
    'filter_reset'      => 'Restablecer',
    'all_status'        => 'Todos los estados',
    'all_types'         => 'Todos los tipos',
    'all_transports'    => 'Todos los transportes',

    // Statistics
    'total_count'   => 'Total',
    'sent_count'    => 'Enviado',
    'failed_count'  => 'Fallido',
    'pending_count' => 'Pendiente',
    'recent_count'  => 'Reciente',

    // Messages
    'no_records'             => 'No se encontraron registros de correo',
    'delete_confirm'         => '¿Está seguro de que desea eliminar este registro?',
    'batch_delete_confirm'   => '¿Está seguro de que desea eliminar los registros seleccionados?',
    'resend_confirm'         => '¿Está seguro de que desea reenviar este correo?',
    'cleanup_confirm'        => 'Ingrese el número de días a conservar (se eliminarán los registros más antiguos):',
    'cleanup_title'          => 'Limpiar registros antiguos',
    'delete_success'         => 'Eliminado exitosamente',
    'batch_delete_success'   => 'Eliminación en lote exitosa',
    'resend_success'         => 'Reenviado exitosamente',
    'cleanup_success'        => 'Limpieza exitosa',
    'operation_failed'       => 'Operación fallida',
    'delete_failed'          => 'Eliminación fallida',
    'resend_failed'          => 'Reenvío fallido',
    'cleanup_failed'         => 'Limpieza fallida',
    'please_select_records'  => 'Por favor seleccione registros para operar',
    'resend_not_available'   => 'Este correo ha sido enviado exitosamente, no necesita reenvío',
    'resend_not_implemented' => 'Función de reenvío no implementada aún, por favor contacte al desarrollador',
    'statistics_developing'  => 'Función de estadísticas en desarrollo...',
    'total_records'          => 'Total :count registros',
    'recipient_placeholder'  => 'Dirección de correo electrónico',

    // Controller messages
    'select_records_to_delete'       => 'Por favor seleccione registros para eliminar',
    'delete_success_message'         => 'Eliminado exitosamente',
    'delete_failed_message'          => 'Eliminación fallida: :error',
    'select_records_to_operate'      => 'Por favor seleccione registros para operar',
    'batch_delete_success_message'   => 'Eliminación en lote exitosa',
    'batch_mark_sent_success'        => 'Marcado en lote como enviado exitoso',
    'batch_mark_failed_success'      => 'Marcado en lote como fallido exitoso',
    'unsupported_operation'          => 'Operación no soportada',
    'operation_failed_message'       => 'Operación fallida: :error',
    'days_must_greater_than_zero'    => 'Los días deben ser mayores que 0',
    'cleanup_success_message'        => 'Se limpiaron exitosamente :count registros',
    'cleanup_failed_message'         => 'Limpieza fallida: :error',
    'get_statistics_success'         => 'Obtener estadísticas exitoso',
    'get_statistics_failed'          => 'Obtener estadísticas fallido: :error',
    'mail_already_sent'              => 'Este correo ya ha sido enviado exitosamente, no necesita reenvío',
    'resend_not_implemented_message' => 'Función de reenvío no implementada aún, por favor contacte al desarrollador',
    'resend_failed_message'          => 'Reenvío fallido: :error',

    // Model translations
    'unknown_status'    => 'Estado desconocido',
    'unknown_type'      => 'Tipo desconocido',
    'unknown_transport' => 'Transporte desconocido',

    // Detail Page
    'basic_info'        => 'Información básica',
    'mail_content'      => 'Contenido del correo',
    'attachments'       => 'Archivos adjuntos',
    'headers'           => 'Encabezados del correo',
    'operation_history' => 'Historial de operaciones',
    'view_headers'      => 'Ver encabezados detallados',
    'mail_created'      => 'Correo creado',
    'mail_sent'         => 'Correo enviado',
    'mail_failed'       => 'Envío fallido',
    'unknown_file'      => 'Archivo desconocido',
    'system_default'    => 'Sistema por defecto',
    'not_sent'          => 'No enviado',

    // Permissions
    'permission_index'  => 'Ver registros de correo',
    'permission_show'   => 'Ver detalles del correo',
    'permission_delete' => 'Eliminar registros de correo',
    'permission_update' => 'Actualizar registros de correo',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'Ver registros de correo',
    'mail_logs_show'   => 'Ver detalles del correo',
    'mail_logs_delete' => 'Eliminar registros de correo',
    'mail_logs_update' => 'Actualizar registros de correo',

    // View translations
    'confirm_resend' => '¿Está seguro de que desea reenviar este correo?',
    'confirm_delete' => '¿Está seguro de que desea eliminar este registro?',
    'text_hint'      => 'Pista',
];
