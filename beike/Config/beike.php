<?php
/**
 * Beike Config
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-06 09:09:09
 * @modified   2022-09-13 22:32:41
 */

return [
    'api_url'         => env('BEIKE_API_URL', 'https://beikeshop.com'),
    'version'         => '1.5.5.7',
    'build'           => '20240509',

    'admin_name'      => env('ADMIN_NAME'),
    'force_url_https' => env('APP_FORCE_HTTPS', false),
];
