<?php
/**
 * DesignService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-14 20:57:37
 * @modified   2022-07-14 20:57:37
 */

namespace Beike\Services;

use Beike\Repositories\ProductRepo;
use Illuminate\Support\Str;

class DesignService
{
    public static function handleRequestModules($modulesData): array
    {
        $modulesData = $modulesData['modules'];
        if (empty($modulesData)) {
            return [];
        }

        foreach ($modulesData as $index => $moduleData) {
            $moduleId = $moduleData['module_id'] ?? '';
            if (empty($moduleId)) {
                $moduleData['module_id'] = Str::random(16);
            }
            $modulesData[$index] = $moduleData;
        }
        return ['modules' => $modulesData];
    }


    /**
     * @throws \Exception
     */
    public static function handleModuleContent($moduleCode, $content)
    {
        if ($moduleCode == 'slideshow') {
            return self::handleSlideShow($content);
        } elseif (in_array($moduleCode, ['image401', 'image100'])) {
            return self::handleImage401($content);
        } elseif ($moduleCode == 'brand') {
            return self::handleBrand($content);
        } elseif ($moduleCode == 'tab_product') {
            return self::handleTabProducts($content);
        }
        return $content;
    }


    /**
     * 处理 SlideShow 模块
     *
     * @param $content
     * @return array
     * @throws \Exception
     */
    private static function handleSlideShow($content): array
    {
        $images = $content['images'];
        if (empty($images)) {
            return $content;
        }

        $content['images'] = self::handleImages($images);
        return $content;
    }

    /**
     * 处理 brand 模块
     *
     * @param $content
     * @return array
     * @throws \Exception
     */
    private static function handleBrand($content): array
    {
        $brands = $content['brands'];


        $content['brands'] = [];
        $content['title'] = $content['title'][current_language_code()];
        return $content;
    }


    /**
     * 处理 SlideShow 模块
     *
     * @param $content
     * @return array
     * @throws \Exception
     */
    private static function handleImage401($content): array
    {
        $images = $content['images'];
        if (empty($images)) {
            return $content;
        }

        $content['images'] = self::handleImages($images);
        $content['full'] = $content['full'] ?? false;
        return $content;
    }


    /**
     * 处理选项卡商品列表模块
     *
     * @param $content
     * @return array
     */
    private static function handleTabProducts($content): array
    {
        $tabs = $content['tabs'] ?? [];
        if (empty($tabs)) {
            return [];
        }

        foreach ($tabs as $index => $tab) {
            $tabs[$index]['title'] = $tab['title'][current_language_code()];
            $productsIds = $tab['products'];
            if ($productsIds) {
                $tabs[$index]['products'] = ProductRepo::getProductsByIds($productsIds);
            }
        }
        $content['tabs'] = $tabs;
        $content['title'] = $content['title'][current_language_code()];
        return $content;
    }


    /**
     * 处理图片以及链接
     * @throws \Exception
     */
    private static function handleImages($images): array
    {
        if (empty($images)) {
            return [];
        }

        foreach ($images as $index => $image) {
            $imagePath = 'catalog/' . ($image['image'][current_language_code()] ?? '');
            $images[$index]['image'] = image_origin($imagePath);

            $link = $image['link'];
            if (empty($link)) {
                continue;
            }

            $type = $link['type'] ?? '';
            $value = (int)$link['value'] ?? 0;
            if ($type && $value) {
                $images[$index]['link']['link'] = self::handleLink($type, $value);
            }
        }

        return $images;
    }


    /**
     * 处理链接
     *
     * @param $type
     * @param $value
     * @return string
     */
    private static function handleLink($type, $value): string
    {
        if ($type == 'product') {
            return shop_route('products.show', ['product' => $value]);
        }
        if ($type == 'category') {
            return shop_route('categories.show', ['category' => $value]);
        }
        return '';
    }
}
