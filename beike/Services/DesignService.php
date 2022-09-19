<?php
/**
 * DesignService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-14 20:57:37
 * @modified   2022-07-14 20:57:37
 */

namespace Beike\Services;

use Illuminate\Support\Str;
use Beike\Repositories\BrandRepo;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\BrandDetail;

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
        $brandIds = $content['brands'] ?? [];
        $brands = BrandDetail::collection(BrandRepo::getListByIds($brandIds))->jsonSerialize();

        $content['brands'] = $brands;
        $content['title'] = $content['title'][locale()] ?? '';
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
            $tabs[$index]['title'] = $tab['title'][locale()] ?? '';
            $productsIds = $tab['products'];
            if ($productsIds) {
                $tabs[$index]['products'] = ProductRepo::getProductsByIds($productsIds)->jsonSerialize();
            }
        }
        $content['tabs'] = $tabs;
        $content['title'] = $content['title'][locale()] ?? '';
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
            $imagePath = $image['image'][locale()] ?? '';
            $images[$index]['image'] = image_origin($imagePath);

            $link = $image['link'];
            if (empty($link)) {
                continue;
            }

            $type = $link['type'] ?? '';
            $value = (int)$link['value'] ?? 0;
            $images[$index]['link']['link'] = self::handleLink($type, $value);
        }

        return $images;
    }


    /**
     * 处理链接
     *
     * @param $type
     * @param $value
     * @return string
     * @throws \Exception
     */
    private static function handleLink($type, $value): string
    {
        return type_route($type, $value);
    }
}
