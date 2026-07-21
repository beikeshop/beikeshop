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
    'mail_logs' => 'Журналы почты',
    'mail_log'  => 'Журнал почты',
    'list'      => 'Список журналов почты',
    'detail'    => 'Детали почты',

    // Fields
    'to_email'      => 'Email получателя',
    'to_name'       => 'Имя получателя',
    'from_email'    => 'Email отправителя',
    'from_name'     => 'Имя отправителя',
    'subject'       => 'Тема',
    'content'       => 'Содержание',
    'mail_type'     => 'Тип почты',
    'transport'     => 'Транспорт',
    'status'        => 'Статус',
    'error_message' => 'Сообщение об ошибке',
    'sent_at'       => 'Отправлено в',
    'created_at'    => 'Создано в',
    'updated_at'    => 'Обновлено в',

    // Status
    'status_pending' => 'Ожидание',
    'status_sent'    => 'Отправлено',
    'status_failed'  => 'Не удалось',

    // Mail Types
    'type_customer_registration' => 'Регистрация клиента',
    'type_customer_forgotten'    => 'Забытый пароль',
    'type_order_new'             => 'Новый заказ',
    'type_order_update'          => 'Обновление заказа',
    'type_rma_new'               => 'Запрос на возврат',
    'type_admin_forgotten'       => 'Админ забытый пароль',
    'type_other'                 => 'Другое',

    // Transport Methods
    'transport_smtp'      => 'SMTP',
    'transport_sendmail'  => 'Sendmail',
    'transport_mailgun'   => 'Mailgun',
    'transport_sendcloud' => 'SendCloud',
    'transport_log'       => 'Журнал',
    'transport_array'     => 'Массив',
    'transport_ses'       => 'Amazon SES',
    'transport_postmark'  => 'Postmark',
    'transport_unknown'   => 'Неизвестно',

    // Actions
    'view_detail'  => 'Просмотр деталей',
    'resend'       => 'Переслать',
    'delete'       => 'Удалить',
    'batch_delete' => 'Массовое удаление',
    'cleanup'      => 'Очистить старые записи',
    'statistics'   => 'Статистика',
    'back_to_list' => 'Вернуться к списку',

    // Filters
    'filter_status'     => 'Статус',
    'filter_mail_type'  => 'Тип почты',
    'filter_transport'  => 'Транспорт',
    'filter_recipient'  => 'Получатель',
    'filter_start_date' => 'Дата начала',
    'filter_end_date'   => 'Дата окончания',
    'filter_submit'     => 'Фильтр',
    'filter_reset'      => 'Сброс',
    'all_status'        => 'Все статусы',
    'all_types'         => 'Все типы',
    'all_transports'    => 'Все транспорты',

    // Statistics
    'total_count'   => 'Всего',
    'sent_count'    => 'Отправлено',
    'failed_count'  => 'Не удалось',
    'pending_count' => 'Ожидание',
    'recent_count'  => 'Недавние',

    // Messages
    'no_records'             => 'Записи почты не найдены',
    'delete_confirm'         => 'Вы уверены, что хотите удалить эту запись?',
    'batch_delete_confirm'   => 'Вы уверены, что хотите удалить выбранные записи?',
    'resend_confirm'         => 'Вы уверены, что хотите переслать это письмо?',
    'cleanup_confirm'        => 'Введите количество дней для сохранения (более старые записи будут удалены):',
    'cleanup_title'          => 'Очистить старые записи',
    'delete_success'         => 'Успешно удалено',
    'batch_delete_success'   => 'Массовое удаление успешно',
    'resend_success'         => 'Успешно переслано',
    'cleanup_success'        => 'Очистка успешна',
    'operation_failed'       => 'Операция не удалась',
    'delete_failed'          => 'Удаление не удалось',
    'resend_failed'          => 'Пересылка не удалась',
    'cleanup_failed'         => 'Очистка не удалась',
    'please_select_records'  => 'Пожалуйста, выберите записи для операции',
    'resend_not_available'   => 'Это письмо уже было успешно отправлено, пересылка не нужна',
    'resend_not_implemented' => 'Функция пересылки еще не реализована, обратитесь к разработчику',
    'statistics_developing'  => 'Функция статистики в разработке...',
    'total_records'          => 'Всего :count записей',
    'recipient_placeholder'  => 'Email адрес',

    // Controller messages
    'select_records_to_delete'       => 'Пожалуйста, выберите записи для удаления',
    'delete_success_message'         => 'Успешно удалено',
    'delete_failed_message'          => 'Удаление не удалось: :error',
    'select_records_to_operate'      => 'Пожалуйста, выберите записи для операции',
    'batch_delete_success_message'   => 'Массовое удаление успешно',
    'batch_mark_sent_success'        => 'Массовая отметка как отправленное успешна',
    'batch_mark_failed_success'      => 'Массовая отметка как неудачное успешна',
    'unsupported_operation'          => 'Неподдерживаемая операция',
    'operation_failed_message'       => 'Операция не удалась: :error',
    'days_must_greater_than_zero'    => 'Дни должны быть больше 0',
    'cleanup_success_message'        => 'Успешно очищено :count записей',
    'cleanup_failed_message'         => 'Очистка не удалась: :error',
    'get_statistics_success'         => 'Получение статистики успешно',
    'get_statistics_failed'          => 'Получение статистики не удалось: :error',
    'mail_already_sent'              => 'Это письмо уже было успешно отправлено, пересылка не нужна',
    'resend_not_implemented_message' => 'Функция пересылки еще не реализована, обратитесь к разработчику',
    'resend_failed_message'          => 'Пересылка не удалась: :error',

    // Model translations
    'unknown_status'    => 'Неизвестный статус',
    'unknown_type'      => 'Неизвестный тип',
    'unknown_transport' => 'Неизвестный транспорт',

    // Detail Page
    'basic_info'        => 'Основная информация',
    'mail_content'      => 'Содержание почты',
    'attachments'       => 'Вложения',
    'headers'           => 'Заголовки почты',
    'operation_history' => 'История операций',
    'view_headers'      => 'Просмотр подробных заголовков',
    'mail_created'      => 'Почта создана',
    'mail_sent'         => 'Почта отправлена',
    'mail_failed'       => 'Отправка не удалась',
    'unknown_file'      => 'Неизвестный файл',
    'system_default'    => 'Системный по умолчанию',
    'not_sent'          => 'Не отправлено',

    // Permissions
    'permission_index'  => 'Просмотр журналов почты',
    'permission_show'   => 'Просмотр деталей почты',
    'permission_delete' => 'Удаление журналов почты',
    'permission_update' => 'Обновление журналов почты',

    // Permission Names (for permission system)
    'mail_logs_index'  => 'Просмотр журналов почты',
    'mail_logs_show'   => 'Просмотр деталей почты',
    'mail_logs_delete' => 'Удаление журналов почты',
    'mail_logs_update' => 'Обновление журналов почты',

    // View translations
    'confirm_resend' => 'Вы уверены, что хотите переслать это письмо?',
    'confirm_delete' => 'Вы уверены, что хотите удалить эту запись?',
    'text_hint'      => 'Подсказка',
];
