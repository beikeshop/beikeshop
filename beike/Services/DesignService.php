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

use Beike\Models\Product;
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
        foreach ($content['images'] as $index => $image) {
            $imagePath = 'catalog' . ($image['image'][current_language_code()] ?? '');
            $content['images'][$index]['image'] = image_origin($imagePath);

            $link = $image['link'];
            if (empty($link)) {
                continue;
            }
            $type = $link['type'] ?? '';
            $value = (int)$link['value'] ?? 0;
            if ($type && $value) {
                $content['images'][$index]['link']['link'] = self::handleLink($type, $value);
            }
        }
        return $content;
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
