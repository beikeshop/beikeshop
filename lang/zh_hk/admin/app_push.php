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
    'page_title'                => 'APP 訊息推送',
    'api_url'                   => '推送 API URL',
    'push_title'                => '訊息推送',
    'title'                     => '標題',
    'content'                   => '內容',
    'link'                      => '連結',
    'link_tip'                  => '此處選擇使用者點擊通知彈窗後要跳轉的頁面；若不選擇，則僅打開 APP。',
    'push_clientid'             => '推送 ID',
    'push_clientid_tip'         => '推送 ID 僅用於測試，若不填寫則會推送給所有使用者；在 APP 內搜尋 "bk_debug" 可查看推送 ID。',
    'api_url_err'               => '請先配置推送服務 API URL',
    'order_status_auto_push'    => '訂單發貨狀態更新自動推送',
    'order_status_update_title' => '訂單狀態更新',
    'order_status_update_info'  => '訂單狀態已更新為: ',
    'push_tip_1'                => '推送服務 API URL 申請教學：<a href="https://docs.beikeshop.com/config/app_push.html" target="_blank">https://docs.beikeshop.com/config/app_push.html</a>',
];
