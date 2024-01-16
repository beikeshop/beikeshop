<?php

/**
 * FooterRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-11 18:16:06
 * @modified   2022-08-11 18:16:06
 */

namespace Beike\Repositories;

class MenuRepo
{
    /**
     * 处理页头编辑器数据
     *
     * @return array|mixed
     * @throws \Exception
     */
    public static function handleMenuData($menuSetting = [])
    {
        if (empty($menuSetting)) {
            $menuSetting = system_setting('base.menu_setting');
        }

        $locale = locale();
        $menus  = $menuSetting['menus'];

        foreach ($menus as $index => $menu) {
            $menu['new_window']    = $menu['link']['new_window']        ?? false;
            $menu['link']          = handle_link($menu['link'])['link'] ?? '';
            $menu['name']          = $menu['name'][$locale]             ?? '';
            $menu['badge']['name'] = $menu['badge']['name'][$locale]    ?? '';

            if ($menu['childrenGroup']) {
                $menu['children_group'] = self::handleChildrenGroup($menu['childrenGroup']);
            }
            $menus[$index] = $menu;
        }

        return $menus;
    }

    /**
     * 处理头部 menu 子菜单数据
     *
     * @param $childrenGroups
     * @return mixed
     * @throws \Exception
     */
    private static function handleChildrenGroup($childrenGroups)
    {
        $locale = locale();
        foreach ($childrenGroups as $groupIndex => $childrenGroup) {
            $childrenGroup['name'] = $childrenGroup['name'][$locale] ?? '';
            if ($childrenGroup['type'] == 'image') {
                $childrenGroup['image']['image'] = image_origin($childrenGroup['image']['image'][$locale] ?? '');
                $childrenGroup['image']['link']  = type_route($childrenGroup['image']['link']['type'], $childrenGroup['image']['link']['value']);
            } elseif ($childrenGroup['children']) {
                foreach ($childrenGroup['children'] as $childrenIndex => $children) {
                    $children['link']                          = handle_link($children['link']);
                    $childrenGroup['children'][$childrenIndex] = $children;
                }
            }
            $childrenGroups[$groupIndex] = $childrenGroup;
        }

        return $childrenGroups;
    }
}
