<?php

/**
 * base.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-04 15:29:49
 * @modified   2022-08-04 15:29:49
 */

return [
    'page_title'                => 'Уведомления APP',
    'api_url'                   => 'URL API Push',
    'push_title'                => 'Push-уведомление',
    'title'                     => 'Заголовок',
    'content'                   => 'Содержание',
    'link'                      => 'Ссылка',
    'link_tip'                  => 'Здесь выбирается страница, на которую будет перенаправлен пользователь после клика по всплывающему уведомлению. Если ничего не выбрано, откроется только приложение.',
    'push_clientid'             => 'Push-ID',
    'push_clientid_tip'         => 'Push-ID используется только для тестирования. Если не заполнено, уведомление будет отправлено всем пользователям. Для просмотра Push-ID в приложении найдите "bk_debug".',
    'api_url_err'               => 'Пожалуйста, сначала настройте URL API сервиса Push',
    'order_status_auto_push'    => 'Автоматическая отправка при обновлении статуса доставки',
    'order_status_update_title' => 'Обновление статуса заказа',
    'order_status_update_info'  => 'Статус заказа был обновлён: ',
    'push_tip_1'                => 'Инструкция по получению URL API сервиса Push: <a href="https://docs.beikeshop.com/config/app_push.html" target="_blank">https://docs.beikeshop.com/config/app_push.html</a>',
];
