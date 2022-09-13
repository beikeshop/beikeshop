<?php
/**
 * bootstrap.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-20 15:35:59
 * @modified   2022-07-20 15:35:59
 */

namespace Plugin\LatestProducts;

class Bootstrap
{
    public function boot()
    {
        $this->addLatestProducts();
    }

    /**
     * 在前台网页头部添加二级菜单链接
     */
    private function addLatestProducts()
    {
        add_filter('menu.content', function ($data) {
            $data[] = [
                'name' => trans('LatestProducts::header.latest_products'),
                "link" => plugin_route('latest_products'),
            ];
            return $data;
        });
    }
}
