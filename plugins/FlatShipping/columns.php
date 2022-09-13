<?php
/**
 * Flat Shipping 字段
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-29 21:16:23
 * @modified   2022-06-29 21:16:23
 */

return [
    [
        'name' => 'type',
        'label_key' => 'common.flat_shipping',
        'type' => 'select',
        'options' => [
            ['value' => 'fixed', 'label_key' => 'common.flat_shipping'],
            ['value' => 'percent', 'label_key' => 'common.percentage']
        ],
        'required' => true,
    ],
    [
        'name' => 'value',
        'label_key' => 'common.shipping_value',
        'type' => 'string',
        'required' => true,
    ]
];
