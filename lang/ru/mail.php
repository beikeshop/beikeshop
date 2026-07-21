<?php

/**
 * Lang.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2023-09-09 09:09:09
 * @modified   2023-09-08 07:19:48
 */

return [
    'order_success'           => 'заказ успешно отправлен',
    'order_update'            => 'обновление статуса заказа',
    'order_success_info'      => 'Ваш заказ успешно отправлен, ниже приведены детали заказа',
    'order_success'           => 'Ваш заказ был успешно отправлен',
    'not_oneself'             => 'неличные операции можно игнорировать. ',
    'customer_name'           => 'Уважаемый пользователь :name, привет! ',
    'sincerely'               => 'Искрен ваш',
    'team'                    => 'команд',
    'order_update_status'     => 'Статус вашего заказа :number обновлен',
    'welcome_register'        => 'добро пожаловать на регистрацию',
    'new_register'            => 'Регистрация нового пользователя',
    'customer_name_line'      => 'Имя пользователя',
    'register_end'            => 'Завершите регистрацию, нажмите кнопку ниже, чтобы вернуться в торговый центр. ',
    'btn_buy_now'             => 'купить сейчас',
    'retrieve_password_title' => 'получить пароль',
    'retrieve_password_text'  => 'Вы восстанавливаете свой пароль, нажмите кнопку ниже, чтобы завершить операцию. ',
    'retrieve_password_btn'   => 'Нажмите здесь, чтобы подтвердить адрес электронной почты',
    'rma_success'             => 'Заявка на послепродажное обслуживание успешно отправлена',
    'rma_success_admin'       => 'Появился новый заказ на послепродажное обслуживание',
    'admin_name'              => 'Уважаемый администратор, здравствуйте',
    'rma_product'             => 'Информация о продукте',
    'new_order'               => 'Новый заказ',
    'order_update_admin'      => 'Статус заказа :number был обновлен',

    // Сообщения об ошибках транспорта почты SendCloud
    'sendcloud_invalid_message_type'         => 'Сообщение должно быть экземпляром Symfony\Component\Mime\Email',
    'sendcloud_send_failed'                  => 'Не удалось отправить почту SendCloud',
    'sendcloud_from_address_empty'           => 'Адрес отправителя SendCloud не может быть пустым',
    'sendcloud_from_address_invalid'         => 'Неверный формат адреса отправителя SendCloud: :address',
    'sendcloud_example_domain_not_supported' => 'SendCloud не поддерживает адреса примеров доменов: :address. Пожалуйста, настройте реальный адрес электронной почты отправителя в настройках бэкенда.',
    'sendcloud_to_address_empty'             => 'Адрес получателя SendCloud не может быть пустым',
    'sendcloud_to_address_invalid'           => 'Неверный формат адреса получателя SendCloud: :address',
    'sendcloud_api_call_failed'              => 'Не удалось выполнить вызов API SendCloud',
    'sendcloud_api_error'                    => 'Ошибка SendCloud [:status_code]: :message',
    'sendcloud_send_success'                 => 'Почта SendCloud успешно отправлена',
];
