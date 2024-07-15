<?php
/**
 * FlattenCategoryRepo.php
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2024-01-24 16:00:54
 * @modified   2024-01-24 16:00:54
 */

namespace Beike\Repositories;

use Beike\Models\Category;

class FlattenCategoryRepo
{
    public static $categories = [];

    public static $children = [];

    private static $allCategories = null;

    /**
     * 将顶级分类ID和所有的子分类ID组合成一个数组
     *
     * @param array $topCategoryIds
     * @param array $subCategoryIds
     * @return array
     */
    public static function generateAllCategoryIds(array $topCategoryIds, array $subCategoryIds): array
    {
        $result = [];
        foreach ($topCategoryIds as $topCategoryId) {
            $result[]          = $topCategoryId;
            $allSubCategoryIds = $subCategoryIds[$topCategoryId] ?? [];
            if (empty($allSubCategoryIds)) {
                continue;
            }
            $result = array_merge($result, $allSubCategoryIds);
        }

        return array_unique($result);
    }

    /**
     * 获取某些分类ID的所有子分类ID
     *
     * @param array $categoryIds
     * @return array
     */
    public static function getAllSubCategoryIdsByCategoryIds(array $categoryIds): array
    {
        if (! $categoryIds) {
            return [];
        }
        $results = [];
        foreach ($categoryIds as $categoryId) {
            $subCategoryIds       = self::getAllSubCategoryIdsByCategoryId($categoryId);
            $results[$categoryId] = $subCategoryIds;
        }

        return $results;
    }

    /**
     * @param $parentId
     * @return array
     * @throws \Exception
     */
    public static function getCategoryList($parentId = 0): array
    {
        $categoryIds = self::getFlattenChildren($parentId);
        $categories  = self::getFlattenCategories($categoryIds);
        foreach ($categories as $index => $category) {
            $categoryId                     = $category['id'];
            $children                       = self::getCategoryList($categoryId);
            if ($children) {
                $categories[$index]['children'] = $children;
            }
        }

        return $categories;
    }

    /**
     * 获取某些分类下的子分类
     * @param $categoryIds
     * @return array
     */
    public static function getCategoriesByParentIds($categoryIds): array
    {
        if (! $categoryIds) {
            return [];
        }
        $result = [];
        foreach ($categoryIds as $categoryId) {
            $result = array_merge(self::getAllSubCategories($categoryId), $result);
        }

        return $result;
    }

    private static function getAllSubCategories($categoryId, &$result = [])
    {
        $categories = Category::query()
            ->where('parent_id', $categoryId)
            ->get();
        foreach ($categories as $category) {
            $result[] = $category->category_id;
            self::getAllSubCategories($category->category_id, $result);
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private static function getAllFlattenCategories(): array
    {
        if (self::$categories) {
            return self::$categories;
        }
        $width   = request('width', 300);
        $height  = request('height', 300);
        $builder = Category::query()
            ->with(['description'])
            ->select(['categories.id', 'categories.image', 'categories.parent_id']);

        $categories = $builder->get();
        $result     = [];
        foreach ($categories as $category) {
            $imagePath               = $category->image;
            $item['id']              = $category->id;
            $item['url']             = $category->url;
            $item['original_image']  = image_origin($imagePath);
            $item['image']           = image_resize($imagePath, $width, $height);
            $item['name']            = html_entity_decode($category->description->name);
            $result[$category['id']] = $item;
        }
        self::$categories = $result;

        return $result;
    }

    /**
     * @param array $categoryIds
     * @return array
     * @throws \Exception
     */
    private static function getFlattenCategories(array $categoryIds = []): array
    {
        if (empty($categoryIds)) {
            return [];
        }
        $allCategories = self::getAllFlattenCategories();
        $result        = [];
        foreach ($categoryIds as $categoryId) {
            if (isset($allCategories[$categoryId])) {
                $result[] = $allCategories[$categoryId];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private static function getAllFlattenChildren(): array
    {
        if (self::$children) {
            return self::$children;
        }
        $builder = Category::query()
            ->select(['id', 'parent_id'])
            ->orderBy('categories.position')
            ->orderBy('categories.parent_id');
        $categories = $builder->get()->toArray();
        $result     = [];
        foreach ($categories as $category) {
            $result[$category['parent_id']][] = $category['id'];
        }
        self::$children = $result;

        return $result;
    }

    private static function getFlattenChildren($parentId = 0)
    {
        $allChildren = self::getAllFlattenChildren();

        return $allChildren[$parentId] ?? [];
    }

    /**
     * 获取某个分类ID的所有子分类ID
     *
     * @param int   $categoryId
     * @param array $subCategoryIds
     * @return array
     */
    private static function getAllSubCategoryIdsByCategoryId(int $categoryId, array &$subCategoryIds = []): array
    {
        $allCategories = self::getAllCategories();
        if (! $allCategories) {
            return $subCategoryIds;
        }
        foreach ($allCategories as $category) {
            if ($category['parent_id'] == $categoryId) {
                if (! in_array($category['category_id'], $subCategoryIds)) {
                    $subCategoryIds[] = $category['category_id'];
                }
                $subCategoryIds = self::getAllSubCategoryIdsByCategoryId($category['category_id'], $subCategoryIds);
            }
        }

        return $subCategoryIds;
    }

    /**
     * 获取所有分类， 只包含category_id, parent_id
     * @return array
     */
    private static function getAllCategories(): array
    {
        if (! is_null(self::$allCategories)) {
            return self::$allCategories;
        }
        $allCategories = Category::query()
            ->select(['category_id', 'parent_id'])
            ->get()
            ->toArray();
        self::$allCategories = $allCategories;

        return $allCategories;
    }
}
