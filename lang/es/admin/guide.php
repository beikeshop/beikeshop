<?php

/**
 * order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    // Heading
    'heading_title'                => 'Guía para principiantes',

    // Tab
    'tab_basic'                    => 'Configuración básica',
    'tab_language'                 => 'Idiomas y monedas múltiples',
    'tab_product'                  => 'Crear producto',
    'tab_theme'                    => 'Decoración de la tienda',
    'tab_payment_shipping'         => 'Pagos y logística',
    'tab_mail'                     => 'Configurar correo',

    // Text
    'text_extension'               => 'Extensión',
    'text_success'                 => '¡Éxito: La guía para principiantes ha sido modificada!',
    'text_edit'                    => 'Editar guía para principiantes',
    'text_view'                    => 'Mostrar detalles...',
    'text_greeting'                => '¡Felicidades, su sitio web ha instalado BeikeShop correctamente!',
    'text_greeting_1'              => 'Le guiaremos para hacer algunas configuraciones personalizadas básicas en el sistema, para ayudarlo a comprender las funciones del sistema BeikeShop y empezar a usarlo rápidamente!',
    'text_basic_1'                 => 'Primero, puede configurar la siguiente información importante en la configuración del sistema:',
    'text_language_1'              => 'El sistema BeikeShop admite múltiples idiomas y monedas. Antes de empezar a crear su primer producto, puede seleccionar el idioma y la moneda predeterminados en la parte frontal del centro comercial!',
    'text_language_2'              => 'Si solo necesita usar un idioma y una moneda, puede eliminar los demás idiomas y monedas. Evite la molestia de introducir información en varios idiomas al crear productos.',
    'text_product_1'               => 'Durante la instalación del sistema, se importarán automáticamente algunos datos de productos predeterminados para su demostración. ¡Puede probar primero <a href="' . admin_route('products.create') . '">Crear productos</a>!',
    'text_product_2'               => '¡BeikeShop ofrece potentes capacidades de gestión de productos! Incluyendo: <a href="' . admin_route('categories.index') . '">Clasificación de productos</a>, <a href="' . admin_route('brands.index') . '">Gestión de marcas</a>, productos de múltiples especificaciones, <a href="' . admin_route('multi_filter.index') . '">Filtro avanzado</a>, <a href="' . admin_route('attributes.index') . '">Atributos de producto</a> y otras funciones.',
    'text_theme_1'                 => 'El sistema tiene instalado un conjunto de plantillas de tema predeterminadas. Si el tema predeterminado no cumple sus necesidades, también puede comprar otras plantillas de tema en <a href="' . admin_route('marketing.index', ['type' => 'theme']) . '">Plugin Market</a>.',
    'text_theme_2'                 => 'Además, la página de inicio de la plantilla de tema frontal es presentada por el módulo a través del diseño. Es posible que necesite ajustar algunas configuraciones del módulo a través del diseño.',
    'text_theme_3'                 => 'Si compra la APP, también proporcionamos una función específicamente para <a href="' . admin_route('design_app_home.index') . '">Diseño de página de inicio de APP</a>.',
    'text_payment_1'               => 'BeikeShop ofrece canales de pago comunes en el extranjero, como PayPal, Stripe, etc. Antes de abrir oficialmente los pedidos, debe habilitar y configurar el método de pago correspondiente.',
    'text_payment_2'               => 'Nota: Algunas solicitudes de interfaz de pago tardan más en revisarse, por favor solicítelas con anticipación. Los métodos de pago utilizados en China pueden requerir el registro del nombre de dominio del sitio web.',
    'text_payment_3'               => 'Además, también debe establecer el método de entrega de logística para que los clientes puedan elegir. El sistema ofrece un complemento de gastos de envío fijos de forma gratuita.',
    'text_payment_4'               => '¡También puede ir a BeikeShop<a href="' . admin_route('marketing.index') . '">"Plugin Market"</a> para conocer y descargar más métodos de pago y logística!',
    'text_mail_1'                  => 'Las notificaciones por correo electrónico pueden mantener informados a sus clientes sobre el estado del pedido, y también pueden registrarse y recuperar contraseñas por correo electrónico.',
    'text_mail_2'                  => 'Puede configurar SMTP según las necesidades empresariales reales, y se utilizan motores de correo electrónico como Sendmail para enviar correos electrónicos.',

    // Button
    'button_setting_general'       => 'Configuración básica del sitio web',
    'button_setting_store'         => 'Nombre del sitio web',
    'button_setting_logo'          => 'Cambiar Logo',
    'button_setting_option'        => 'Configuración de opciones',
    'button_setting'               => 'Todas las configuraciones del sistema',
    'button_language'              => 'Gestión de idiomas',
    'button_currency'              => 'Gestión de monedas',
    'button_product'               => 'Ver producto',
    'button_product_create'        => 'Crear producto',
    'button_theme_pc'              => 'Configuración de plantilla',
    'button_theme_h5'              => 'Configuración de tema móvil',
    'button_theme'                 => 'Todos los temas',
    'button_layout'                => 'Gestión de diseño',
    'button_payment'               => 'Método de pago',
    'button_shipping'              => 'Método de envío',
    'button_mail'                  => 'Configuración de correo',
    'button_sms'                   => 'Configuración de SMS',
    'button_hide'                  => 'No volver a mostrar',

    // Error
    'error_permission'             => 'Error: ¡No tiene permiso para modificar la guía para principiantes!',
];
