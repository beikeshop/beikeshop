<?php
/**
 * Beike Config
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-06-06 09:09:09
 * @modified   2022-09-13 22:32:41
 */

return [
    'api_url'          => env('BEIKE_API', 'https://api.beikeshop.com'),
    'official_website' => env('BEIKE_OFFICIAL_WEBSITE', 'https://beikeshop.com'),
    'version'          => '1.6.0.18',
    'build'            => '20250701',
    'website_key'      => 'OR5DOG3vnhM5A9iFz4WePCyjiDmglEbeFK8xiypjxvM=',

    'admin_name'      => env('ADMIN_NAME'),
    'force_url_https' => env('APP_FORCE_HTTPS', false),

    // 允许的 Host 白名单，用于安全获取用户访问的域名, 留空则不限制
    'allowed_hosts' => array_filter(explode(',', env('ALLOWED_HOSTS', ''))),

    // HTTP 客户端请求时是否验证 SSL 证书，生产环境建议开启
    'http_verify_ssl' => env('HTTP_VERIFY_SSL', true),

    // HTTP 客户端默认超时时间（秒），0 表示无限等待
    'http_timeout' => env('HTTP_TIMEOUT', 0),
];
