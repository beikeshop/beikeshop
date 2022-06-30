<?php
/**
 * Flat Shipping 字段
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-29 21:16:23
 * @modified   2022-06-29 21:16:23
 */

return [
    [
        'name' => 'type',
        'label' => '方式',
        'type' => 'select',
        'options' => [
            ['value' => 'fixed', 'label' => '固定运费'],
            ['value' => 'percent', 'label' => '百分比']
        ],
        'required' => true,
    ],
    [
        'name' => 'value',
        'label' => '运费值',
        'type' => 'string',
        'required' => true,
    ],
];
