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
     * 处理页尾编辑器数据
     *
     * @return array|mixed
     */
    public static function handleMenuData($MenuSetting = [])
    {
        if (empty($MenuSetting)) {
            $MenuSetting = system_setting('base.menu_setting');
        }

        $locale = locale();

        $menus = $MenuSetting['menus'];

        foreach ($menus as $index => $menu) {
            $menus[$index]['link'] = self::handleLink($menu['link']);
            $menus[$index]['name'] = $menu['name'][$locale] ?? '';
            $menus[$index]['badge']['name'] = $menu['badge']['name'][$locale] ?? '';

            if ($menu['childrenGroup']) {
                foreach ($menu['childrenGroup'] as $group_index => $childrenGroup) {
                    if ($childrenGroup['type'] == 'image') {
                        $menus[$index]['childrenGroup'][$group_index]['image']['image'] = image_origin($childrenGroup['image']['image'][$locale]);
                        $menus[$index]['childrenGroup'][$group_index]['image']['link'] = type_route($childrenGroup['image']['link']['type'], $childrenGroup['image']['link']['value']);
                        continue;
                    }

                    // 判断 $childrenGroup['children'] 是否为空，如果为空，则删除该分组
                    if (empty($childrenGroup['children'])) {
                        unset($menus[$index]['childrenGroup'][$group_index]);
                    } else {
                        $menus[$index]['childrenGroup'][$group_index]['name'] = $childrenGroup['name'][$locale];
                        if ($childrenGroup['children']) {
                            foreach ($childrenGroup['children'] as $children_index => $children) {
                                $menus[$index]['childrenGroup'][$group_index]['children'][$children_index]['link'] = self::handleLink($children['link']);
                            }
                        }
                    }
                }
            }
        }

        return $menus;
    }

    /**
     * 处理链接
     *
     * @param $type
     * @param $value
     * @return string
     */
    private static function handleLink($link): array
    {
        $locale = locale();

        if ($link['type'] == 'custom') {
            $link['link'] = $link['value'];
            $link['text'] = $link['text'][$locale];
        } elseif ($link['type'] == 'static') {
            $link['link'] = shop_route($link['value']);
            $link['text'] = $link['text'][$locale] ?: trans('shop/' . $link['value']);
        } elseif ($link['type'] == 'page') {
            $pageId = $link['value'];
            $page = Page::query()->find($pageId);
            if ($page) {
                $link['link'] = type_route('page', $link['value']);
                $link['text'] = $link['text'][$locale] ?: $page->description->title;
            }
        } elseif ($link['type'] == 'category') {
            $category = Category::query()->find($link['value']);
            if ($category) {
                $link['link'] = type_route('category', $link['value']);
                $link['text'] = $link['text'][$locale] ?: CategoryRepo::getName($link['value']);
            }
        } elseif ($link['type'] == 'product') {
            $link['link'] = type_route('product', $link['value']);
            $link['text'] = $link['text'][$locale] ?: ProductRepo::getName($link['value']);
        } elseif ($link['type'] == 'brand') {
            $link['link'] = type_route('brand', $link['value']);
            $link['text'] = $link['text'][$locale] ?: BrandRepo::getName($link['value']);
        } else {
            $link['link'] = '';
            $link['text'] = $link['text'][$locale];
        }

        return $link;
    }
}
