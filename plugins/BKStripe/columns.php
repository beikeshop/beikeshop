<?php
/**
 * Stripe 字段
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
        'required' => true,
    ],
    [
        'name' => 'api_secret',
        'label' => 'API Secret',
        'type' => 'string',
        'required' => true,
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
        'options' => [
            ['value' => 'china', 'label' => 'China'],
            ['value' => 'usa', 'label' => 'USA']
        ],
        'required' => true,
    ]
];
