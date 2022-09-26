<?php
/**
 * bootstrap.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-20 15:35:59
 * @modified   2022-07-20 15:35:59
 */

namespace Plugin\HeaderMenu;

class Bootstrap
{
    public function boot()
    {
        add_filter('header.categories', function ($data) {
            $data[] = [
                'name' => '插件链接',
                'url' => shop_route('home.index'),
                'children' => [
                    [
                        "name" => "最新商品",
                        "url" => plugin_route('latest_products'),
                    ], [
                        "name" => "百度",
                        "url" => "https://www.baidu.com",
                    ]
                ],
            ];
            return $data;
        });
    }
}
