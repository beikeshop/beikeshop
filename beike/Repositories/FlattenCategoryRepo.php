<?php

/**
 * FlattenCategoryRepo.php
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2024-01-24 16:00:54
 * @modified   2024-01-24 16:00:54
 */

namespace Beike\Repositories;

use Beike\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FlattenCategoryRepo
{
    public static $categories = [];

    public static $children = [];

    private static $allCategories = null;

    private const CACHE_TTL = 86400;

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
        $cacheKey = self::cacheKey('tree', [
            (int) $parentId,
            locale(),
            (int) request('width', 300),
            (int) request('height', 300),
        ]);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($parentId) {
            return self::buildCategoryList((int) $parentId);
        });
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
        $categories = self::getAllCategories();
        foreach ($categories as $category) {
            if ((int) $category['parent_id'] !== (int) $categoryId) {
                continue;
            }

            $result[] = $category['id'];
            self::getAllSubCategories($category['id'], $result);
        }

        return $result;
    }

    /**
     * @param int $parentId
     * @return array
     * @throws \Exception
     */
    private static function buildCategoryList(int $parentId = 0): array
    {
        $categoryIds = self::getFlattenChildren($parentId);
        $categories  = self::getFlattenCategories($categoryIds);
        foreach ($categories as $index => $category) {
            $categoryId = $category['id'];
            $children   = self::buildCategoryList($categoryId);
            if ($children) {
                $categories[$index]['children'] = $children;
            }
        }

        return $categories;
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
            ->select(['categories.id', 'categories.image', 'categories.parent_id', 'categories.active']);

        $categories = $builder->get();
        $result     = [];
        foreach ($categories as $category) {
            $imagePath               = $category->image;
            $item['id']              = $category->id;
            $item['url']             = $category->url;
            $item['active']          = $category->active;
            $item['original_image']  = image_origin($imagePath);
            $item['image']           = image_resize($imagePath, $width, $height);
            $item['name']            = html_entity_decode($category->description->name ?? '');
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
        $categories = DB::table('categories')
            ->select(['id', 'parent_id'])
            ->orderBy('categories.position')
            ->orderBy('categories.parent_id')
            ->get();

        $result = [];
        foreach ($categories as $category) {
            $result[$category->parent_id][] = $category->id;
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
                if (! in_array($category['id'], $subCategoryIds)) {
                    $subCategoryIds[] = $category['id'];
                }
                $subCategoryIds = self::getAllSubCategoryIdsByCategoryId($category['id'], $subCategoryIds);
            }
        }

        return $subCategoryIds;
    }

    /**
     * 获取所有分类， 只包含id, parent_id
     * @return array
     */
    private static function getAllCategories(): array
    {
        if (! is_null(self::$allCategories)) {
            return self::$allCategories;
        }
        $allCategories = DB::table('categories')
            ->select(['id', 'parent_id'])
            ->get()
            ->map(function ($category) {
                return [
                    'id'        => (int) $category->id,
                    'parent_id' => (int) $category->parent_id,
                ];
            })
            ->toArray();
        self::$allCategories = $allCategories;

        return $allCategories;
    }

    private static function cacheKey(string $name, array $parts = []): string
    {
        $parts[] = CategoryRepo::cacheVersion();

        return 'category.flatten.' . $name . '.' . md5(json_encode($parts));
    }
}
