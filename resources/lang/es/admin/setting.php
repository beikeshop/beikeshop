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
    'index'                  => 'configuración del sistema',
    'settings_index'         => 'Ver configuración del sistema',
    'settings_update'        => 'Modificar la configuración del sistema',
    'design_index'           => 'Editor de inicio',
    'design_footer_index'    => 'editor de pie de página',
    'design_menu_index'      => 'Editor de navegación',
    'product_per_page'       => 'El número de productos mostrados en cada página',

    'basic_settings'         => 'configuraciones básicas',
    'store_settings'         => 'configuración de la tienda',
    'picture_settings'       => 'configuraciones de imagen',
    'use_queue'              => 'si usar la cola',
    'mail_settings'          => 'configuraciones de correo',
    'mail_engine'            => 'motor de correo',
    'smtp_host'              => 'anfitrión',
    'smtp_username'          => 'usuario',
    'smtp_encryption'        => 'método de cifrado',
    'smtp_encryption_info'   => 'SSL o TLS',
    'smtp_password'          => 'contraseña',
    'smtp_password_info'     => 'Establecer contraseña SMTP. Para Gmail, consulte: https://security.google.com/settings/security/apppasswords',
    'smtp_port'              => 'puerto',
    'smtp_timeout'           => 'tiempo de espera',
    'sendmail_path'          => 'ruta de ejecución',
    'mailgun_domain'         => 'nombre de dominio',
    'mailgun_secret'         => 'Clave',
    'mailgun_endpoint'       => 'puerto',
    'mail_log'               => 'Descripción: ¡El motor de registro generalmente se usa con fines de prueba! El correo electrónico no se enviará realmente a la dirección del destinatario, y el contenido del correo electrónico se guardará en `/storage/logs/laravel.log`\' en forma de registro',

    'guest_checkout'         => 'pago de visitante',

    'theme_default'          => 'tema predeterminado',
    'theme_black'            => 'tema negro',
    'shipping_address'       => 'dirección de envío',
    'payment_address'        => 'Dirección de Envio',
    'meta_title'             => 'Meta título',
    'meta_description'       => 'Meta descripción',
    'meta_keywords'          => 'Metapalabra clave',
    'telephone'              => 'Teléfono de contacto',
    'email'                  => 'Correo',
    'default_address'        => 'dirección predeterminada',
    'default_country_set'    => 'configuración de país predeterminada',
    'default_zone_set'       => 'configuración de provincia predeterminada',
    'default_language'       => 'idioma predeterminado',
    'default_currency'       => 'moneda predeterminada',
    'default_customer_group' => 'grupo de clientes predeterminado',
    'admin_name'             => 'directorio de fondo',
    'admin_name_info'        => 'Directorio de fondo de administración, el predeterminado es admin',
    'enable_tax'             => 'Habilitar impuestos',
    'enable_tax_info'        => 'si habilitar el cálculo de impuestos',
    'tax_address'            => 'dirección fiscal',
    'tax_address_info'       => 'Según qué dirección calcular el impuesto',
    'logo'                   => 'Logotipo del sitio web',
    'favicon'                => 'favicon',
    'favicon_info'           => 'El pequeño icono que se muestra en la pestaña del navegador debe estar en formato PNG y el tamaño es: 32*32',
    'placeholder_image'      => 'imagen de marcador de posición',
    'placeholder_image_info' => 'La imagen del marcador de posición se muestra cuando no hay imagen o no se encuentra la imagen, tamaño recomendado: 500*500',
    'head_code'              => 'insertar código',
    'head_code_info'         => 'El código en el cuadro de entrada se insertará en el encabezado de la página principal, que se puede usar para contar el código o agregar complementos especiales, etc.',
];
