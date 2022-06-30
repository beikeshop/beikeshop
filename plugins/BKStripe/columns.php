<?php
/**
 * columns.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-29 21:16:23
 * @modified   2022-06-29 21:16:23
 */

return [
    [
        'name' => 'api_key',
        'label' => 'API Key',
        'type' => 'string',
    ],
    [
        'name' => 'api_secret',
        'label' => 'API Secret',
        'type' => 'string',
    ],
    [
        'name' => 'description',
        'label' => '描述',
        'type' => 'text',
    ],
    [
        'name' => 'country',
        'label' => '国家',
        'type' => 'select',
        'option' => ['China', 'USA']
    ],
    [
        'name' => 'active',
        'label' => '是否开启',
        'type' => 'bool',
    ]
];
