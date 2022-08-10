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
        add_filter('header.categories', function ($data) {
            $data[] = [
                'name' => trans('LatestProducts::header.latest_products'),
                "url" => plugin_route('latest_products'),
            ];
            return $data;
        });
    }
}
