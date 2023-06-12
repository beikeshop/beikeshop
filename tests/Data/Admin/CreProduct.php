<?php

namespace Tests\Data\Admin;

class CreProduct
{
public const Puoduct_Info = [
    'ch_name'      => '测试商品', //中文名称
    'en_name'      => '测试商品', //英文名称
    'sku'          => '123', //sku
    'price'        => '500', //价格
    'origin_price' => '50', //原价
    'cost_price'   => '5', //成本价
    'quantity'     => '3', //数量
];

public const Alter = [
    'ch_name'      => '修改测试商品', //中文名称
    'en_name'      => '修改测试商品', //英文名称
    'sku'          => '456', //sku
    'price'        => '5000', //价格
    'origin_price' => '500', //原价
    'cost_price'   => '50', //成本价
    'quantity'     => '30', //数量
    'low_quantity' => '5', //少量商品 ，测试库存不足
];
}
