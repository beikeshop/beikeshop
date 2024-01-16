<?php
/**
 * columns.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-13 16:08:41
 * @modified   2023-03-13 16:08:41
 */

return [
    [
        'name'        => 'api_type',
        'label_key'   => 'common.api_type',
        'type'        => 'select',
        'options'     => [
            ['value' => 'own', 'label_key' => 'common.own'],
            ['value' => 'beikeshop', 'label_key' => 'common.beikeshop'],
        ],
        'required'    => true,
        'description' => '如果选择 BeikeShop 平台, 则 API Key 可以留空',
    ],
    [
        'name'        => 'api_key',
        'label'       => 'API Key',
        'type'        => 'string',
        'required'    => false,
        'description' => '<a target="_blank" href="https://platform.openai.com/account/api-keys">获取 API Key</a>',
    ],
];
