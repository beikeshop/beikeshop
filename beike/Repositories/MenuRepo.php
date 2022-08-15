<?php

/**
 * FooterRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-11 18:16:06
 * @modified   2022-08-11 18:16:06
 */

namespace Beike\Repositories;

use Beike\Models\Page;
use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\ProductRepo;
use Beike\Repositories\BrandRepo;


class MenuRepo
{
    /**
     * 处理页头编辑器数据
     *
     * @return array|mixed
     * @throws \Exception
     */
    public static function handleMenuData($MenuSetting = [])
    {
        if (empty($MenuSetting)) {
            $MenuSetting = system_setting('base.menu_setting');
        }

        $locale = locale();

        $menus = $MenuSetting['menus'];

        foreach ($menus as $index => $menu) {
            $menus[$index]['link'] = handle_link($menu['link']);
            $menus[$index]['name'] = $menu['name'][$locale] ?? '';
            $menus[$index]['badge']['name'] = $menu['badge']['name'][$locale] ?? '';

            if ($menu['childrenGroup']) {
                foreach ($menu['childrenGroup'] as $group_index => $childrenGroup) {
                    $menus[$index]['childrenGroup'][$group_index]['name'] = $childrenGroup['name'][$locale];

                    if ($childrenGroup['type'] == 'image') {
                        $menus[$index]['childrenGroup'][$group_index]['image']['image'] = image_origin($childrenGroup['image']['image'][$locale]);
                        $menus[$index]['childrenGroup'][$group_index]['image']['link'] = type_route($childrenGroup['image']['link']['type'], $childrenGroup['image']['link']['value']);
                        continue;
                    }

                    // 判断 $childrenGroup['children'] 是否为空，如果为空，则删除该分组
                    if (empty($childrenGroup['children'])) {
                        unset($menus[$index]['childrenGroup'][$group_index]);
                    } else {
                        if ($childrenGroup['children']) {
                            foreach ($childrenGroup['children'] as $children_index => $children) {
                                $menus[$index]['childrenGroup'][$group_index]['children'][$children_index]['link'] = handle_link($children['link']);
                            }
                        }
                    }
                }
            }
        }

        return $menus;
    }
}
