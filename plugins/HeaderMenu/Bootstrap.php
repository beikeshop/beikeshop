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
        $this->addHeaderMenu();
    }


    /**
     * 在网页头部添加二级菜单链接
     */
    private function addHeaderMenu()
    {
        add_filter('header.categories', function ($data) {
            $data[] = [
                'name' => trans('HeaderMenu::header.plugin_link'),
                'url' => shop_route('home.index'),
                'children' => [
                    [
                        "name" => trans('HeaderMenu::header.latest_products'),
                        "url" => plugin_route('latest_products'),
                    ], [
                        "name" => trans('HeaderMenu::header.baidu'),
                        "url" => "https://www.baidu.com",
                    ]
                ],
            ];
            return $data;
        });
    }
}
