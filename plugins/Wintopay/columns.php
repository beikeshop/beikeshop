<?php
/**
 * Wintopay 字段
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2024-05-13 21:16:23
 * @modified   2024-05-13 21:16:23
 */

return [
    [
        'name'        => 'merchant_id',
        'label_key'   => 'common.merchant_id',
        'type'        => 'string',
        'required'    => true,
        'rules'       => 'required',
    ],
    [
        'name'        => 'md5_key',
        'label_key'   => 'common.md5_key',
        'type'        => 'string',
        'required'    => true,
        'rules'       => 'required',
    ],
    [
        'name'        => 'api_key',
        'label_key'   => 'common.api_key',
        'type'        => 'string',
        'required'    => true,
        'rules'       => 'required|min:32',
    ],
    [
        'name'        => 'api',
        'label_key'   => 'common.api',
        'type'        => 'select',
        'options'     => [
            ['value' => 'test', 'label_key' => 'common.api_test'],
            ['value' => 'live', 'label_key' => 'common.api_live'],
        ],
        'required'    => true,
    ],
    [
        'name'        => 'payment_type',
        'label_key'   => 'common.payment_type',
        'type'        => 'checkbox',
        'options'     => [
            ['value' => 'card', 'label_key' => 'common.card'],
            ['value' => 'giropay', 'label_key' => 'common.giropay'],
            ['value' => 'bancontact', 'label_key' => 'common.bancontact'],
            ['value' => 'konbini', 'label_key' => 'common.konbini'],
            ['value' => 'grabpay', 'label_key' => 'common.grabpay'],
            ['value' => 'alfamart', 'label_key' => 'common.alfamart'],
            ['value' => 'safety_pay', 'label_key' => 'common.safety_pay'],
            ['value' => 'bancomat_pay', 'label_key' => 'common.bancomat_pay'],
        ],
        'required'    => true,
    ],
    [
        'name'        => 'card_type',
        'label_key'   => 'common.card_type',
        'type'        => 'checkbox',
        'options'     => [
            ['value' => 'visa', 'label_key' => 'common.card_type_visa'],
            ['value' => 'mastercard', 'label_key' => 'common.card_type_mastercard'],
            ['value' => 'ae', 'label_key' => 'common.card_type_ae'],
            ['value' => 'dc', 'label_key' => 'common.card_type_dc'],
            ['value' => 'jcb', 'label_key' => 'common.card_type_jcb'],
            ['value' => 'DClub', 'label_key' => 'common.card_type_dclub'],
        ],
        'required'    => true,
    ],
    [
        'name'        => 'log',
        'label'       => '日志',
        'type'        => 'select',
        'options'     => [
            ['value' => '1', 'label_key' => 'common.enable'],
            ['value' => '0', 'label_key' => 'common.disable'],
        ],
        'required'    => true,
    ],
];
