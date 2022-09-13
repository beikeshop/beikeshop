<?php
/**
 * Stripe 字段
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-29 21:16:23
 * @modified   2022-06-29 21:16:23
 */

return [
    [
        'name' => 'publishable_key',
        'label_key' => 'common.publishable_key',
        'type' => 'string',
        'required' => true,
        'rules' => 'required|size:32',
        'description' => '公钥(Publishable key)',
    ],
    [
        'name' => 'secret_key',
        'label' => '密钥',
        'type' => 'string',
        'required' => true,
        'rules' => 'required|size:32',
        'description' => '密钥(Secret key)',
    ],
    [
        'name' => 'test_mode',
        'label' => '测试模式',
        'type' => 'select',
        'options' => [
            ['value' => '1', 'label' => '开启'],
            ['value' => '0', 'label' => '关闭']
        ],
        'required' => true,
        'description' => '如开启测试模式请填写测试公钥和密钥, 关闭测试模式则填写正式公钥和密钥',
    ]
];
