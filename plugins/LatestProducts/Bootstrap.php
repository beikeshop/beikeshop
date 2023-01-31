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
    /**
     * 去除注释后可以观察网站头部以及产品详情页页面变化
     */
    public function boot()
    {
        $this->addLatestProducts();

        // $this->modifyHeader();
        // $this->modifyProductDetail();
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


    /**
     * 修改前台全局 header 模板
     */
    private function modifyHeader()
    {
        blade_hook('header.top.currency', function ($callback, $output, $data) {
            return '货币前' . $output;
        });

        blade_hook('header.top.language', function ($callback, $output, $data) {
            return $output . '语言后';
        });

        blade_hook('header.top.telephone', function ($callback, $output, $data) {
            return '电话前' . $output;
        });

        blade_hook('header.menu.logo', function ($callback, $output, $data) {
            return $output . 'Logo后';
        });

        blade_hook('header.menu.icon', function ($callback, $output, $data) {
            $view = view('LatestProducts::header_icon')->render();
            return $output . $view;
        });
    }


    /**
     * 修改产品详情页
     * 1. 品牌下面添加信息
     * 2. 立即购买后添加按钮
     */
    private function modifyProductDetail()
    {
        blade_hook('product.detail.brand', function ($callback, $output, $data) {
            return $output . '<div class="d-flex"><span class="title text-muted">Brand 2:</span>品牌 2</div>';
        });

        blade_hook('product.detail.buy.after', function ($callback, $output, $data) {
            $view = view('LatestProducts::product_button')->render();
            return $output . $view;
        });
    }
}
