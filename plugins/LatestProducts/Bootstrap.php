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

        // $this->modifyAdminProductEdit();
        // $this->modifySetting();
        // $this->handlePaidOrder();
    }

    /**
     * 在前台网页头部添加二级菜单链接
     */
    private function addLatestProducts()
    {
        add_hook_filter('menu.content', function ($data) {
            $data[] = [
                'name' => trans('LatestProducts::header.latest_products'),
                'link' => shop_route('latest_products'),
            ];

            return $data;
        }, 0);
    }

    /**
     * 修改前台全局 header 模板演示
     */
    private function modifyHeader()
    {
        add_hook_blade('header.top.currency', function ($callback, $output, $data) {
            return '货币前' . $output;
        });

        add_hook_blade('header.top.language', function ($callback, $output, $data) {
            return $output . '语言后';
        });

        add_hook_blade('header.top.telephone', function ($callback, $output, $data) {
            return '电话前' . $output;
        });

        add_hook_blade('header.menu.logo', function ($callback, $output, $data) {
            return $output . 'Logo后';
        });

        add_hook_blade('header.menu.icon', function ($callback, $output, $data) {
            $view = view('LatestProducts::shop.header_icon')->render();

            return $output . $view;
        });
    }

    /**
     * 修改产品详情页演示
     * 1. 通过数据 hook 修改产品详情页产品名称
     * 2. 通过模板 hook 在产品详情页名称上面添加 Hot 标签
     * 3. 通过模板 hook 在产品详情页品牌下面添加信息
     * 4. 通过模板 hook 在产品详情页立即购买后添加按钮
     */
    private function modifyProductDetail()
    {
        // 通过数据 hook 修改产品详情页产品名称
        add_hook_filter('product.show.data', function ($product) {
            $product['product']['name'] = '[疯狂热销]' . $product['product']['name'];

            return $product;
        });

        // 通过模板 hook 在产品详情页名称上面添加 Hot 标签
        add_hook_blade('product.detail.name', function ($callback, $output, $data) {
            $badge = '<span class="badge" style="background-color: #FF4D00; color: #ffffff; border-color: #FF4D00">Hot</span>';

            return $badge . $output;
        });

        // 通过模板 hook 在产品详情页品牌下面添加信息
        add_hook_blade('product.detail.brand', function ($callback, $output, $data) {
            return $output . '<div class="d-flex"><span class="title text-muted">Brand 2:</span>品牌 2</div>';
        });

        // 通过模板 hook 在产品详情页立即购买后添加按钮
        add_hook_blade('product.detail.buy.after', function ($callback, $output, $data) {
            $view = '<button class="btn btn-dark ms-3 fw-bold"><i class="bi bi-bag-fill me-1"></i>新增按钮</button>';

            return $output . $view;
        });
    }

    /**
     * 后台产品编辑页添加自定义字段演示
     */
    private function modifyAdminProductEdit()
    {
        add_hook_blade('admin.product.edit.extra', function ($callback, $output, $data) {
            $view = view('LatestProducts::admin.product.edit_extra_field', $data)->render();

            return $output . $view;
        }, 1);
    }

    /**
     * 系统设置添加新 tab
     */
    private function modifySetting()
    {
        add_hook_blade('admin.setting.nav.after', function ($callback, $output, $data) {
            return view('LatestProducts::admin.setting.nav')->render();
        });

        add_hook_blade('admin.setting.after', function ($callback, $output, $data) {
            return view('LatestProducts::admin.setting.tab')->render();
        });
    }

    /**
     * 修改订单状态机流程演示
     */
    private function handlePaidOrder() {
        add_hook_filter('service.state_machine.machines', function ($data) {
            $data['machines']['unpaid']['paid'][] = function (){
                // 这里写订单由 unpaid 变为 paid 执行的逻辑
            };
            return $data;
        }, 0);
    }
}
