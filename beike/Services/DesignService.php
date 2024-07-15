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

use Beike\Admin\Repositories\PageRepo;
use Beike\Repositories\BrandRepo;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\BrandDetail;
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

            $viewPath = $moduleData['view_path'] ?? '';
            if ($viewPath == 'design.') {
                $moduleData['view_path'] = '';
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
        $productCodes           = ['product', 'category', 'latest'];
        $content['module_code'] = $moduleCode;
        if ($moduleCode == 'slideshow') {
            return self::handleSlideShow($content);
        } elseif (in_array($moduleCode, ['image401', 'image402', 'image100', 'image200', 'image300', 'image301'])) {
            return self::handleImage401($content);
        } elseif ($moduleCode == 'brand') {
            return self::handleBrand($content);
        } elseif ($moduleCode == 'tab_product') {
            return self::handleTabProducts($content);
        } elseif (in_array($moduleCode, $productCodes)) {
            return self::handleProducts($content);
        } elseif ($moduleCode == 'icons') {
            return self::handleIcons($content);
        } elseif ($moduleCode == 'rich_text') {
            return self::handleRichText($content);
        } elseif ($moduleCode == 'page') {
            return self::handlePage($content);
        }

        return hook_filter('service.design.module.content', $content);
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
        $brands   = BrandDetail::collection(BrandRepo::getListByIds($brandIds))->jsonSerialize();

        $content['brands'] = $brands;
        $content['title']  = $content['title'][locale()] ?? '';

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
        $content['full']   = $content['full'] ?? false;

        return $content;
    }

    /**
     * 处理 icons 模块
     *
     * @param $content
     * @return array
     * @throws \Exception
     */
    private static function handleIcons($content): array
    {
        $content['title'] = $content['title'][locale()] ?? '';

        if (empty($content['images'])) {
            return $content;
        }

        $images = [];
        foreach ($content['images'] as $image) {
            $images[] = [
                'image'    => image_origin($image['image'] ?? ''),
                'text'     => $image['text'][locale()]         ?? '',
                'sub_text' => $image['sub_text'][locale()]     ?? '',
                'link'     => $image['link'],
                'url'      => self::handleLink($image['link']['type'] ?? '', $image['link']['value'] ?? ''),
            ];
        }

        $content['images'] = $images;

        return $content;
    }

    /**
     * 处理 rich_text 模块
     *
     * @param $content
     * @return array
     * @throws \Exception
     */
    private static function handleRichText($content): array
    {
        $content['data'] = $content['text'][locale()] ?? '';

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
            $productsIds           = $tab['products'];
            if ($productsIds) {
                $tabs[$index]['products'] = ProductRepo::getProductsByIds($productsIds)->jsonSerialize();
            }
        }
        $content['tabs']  = $tabs;
        $content['title'] = $content['title'][locale()] ?? '';

        return $content;
    }

    /**
     * 处理文章模块
     *
     * @param $content
     * @return array
     */
    private static function handlePage($content): array
    {
        $content['title'] = $content['title'][locale()] ?? '';
        $items            = PageRepo::getPagesByIds($content['items'])->jsonSerialize();
        $items            = hook_filter('service.design.module.page.handle', $items);
        $content['items'] = $items;

        return $content;
    }

    /**
     * 处理商品模块
     *
     * @param $content
     * @return array
     * @throws \Exception
     */
    private static function handleProducts($content): array
    {
        $content['products'] = ProductRepo::getProductsByIds($content['products'])->jsonSerialize();
        $content['title']    = $content['title'][locale()] ?? '';

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
            $imagePath               = is_array($image['image']) ? $image['image'][locale()] ?? '' : $image['image'] ?? '';
            $images[$index]['image'] = image_origin($imagePath);

            $link = $image['link'];
            if (empty($link)) {
                continue;
            }

            $type                           = $link['type'] ?? '';
            $value                          = $link['type'] == 'custom' ? $link['value'] : ((int) $link['value'] ?? 0);
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
