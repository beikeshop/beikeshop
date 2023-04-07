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

namespace Plugin\Bestseller;

use Plugin\Bestseller\Repositories\ProductRepo;

class Bootstrap
{
    public function boot()
    {
        /**
         * Add module for admin design.
         */
        add_hook_filter('admin.design.index.data', function ($data) {
            $data['editors'][] = 'editor-bestseller';

            return $data;
        });

        /**
         * Get module content for home page and preview.
         */
        add_hook_filter('service.design.module.content', function ($data) {
            $module = $data['module_code'] ?? '';

            if ($module == 'bestseller') {
                $data['title']    = $data['title'][locale()] ?? '';
                $data['products'] = ProductRepo::getBestSellerProducts($data['limit']);
            }

            return $data;
        });
    }
}
