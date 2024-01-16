<?php

namespace Tests\Data\Admin;

class CreProductPage
{
    public const Product_Top = [
        'login_url'    => '/Admin/products/create', //
        'ch_name'      => 'descriptions[zh_cn][name]', //中文名称
        'en_name'      => 'descriptions[en][name]', //英文名称
        'sku'          => 'skus[0][sku]', //sku
        'price'        => 'skus[0][price]', //价格
        'origin_price' => 'skus[0][origin_price]', //原价
        'cost_price'   => 'skus[0][cost_price]', //成本价
        'quantity'     => 'skus[0][quantity]', //数量
        'Enable'       => '#active-1',
        'Disable'      => '#active-0',
        'save_btn'     => '#content > div.container-fluid.p-0 > div.page-bottom-btns > button.btn.w-min-100.btn-lg.btn-default.submit-form.ms-2', //保存
    ];

    public const Product_Assert = [
        'Disable_text' => '.text-danger', //商品禁用后显示的文本class
    ];
}
