<?php

/**
 * PageRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-15 12:37:25
 * @modified   2022-08-15 12:37:25
 */

namespace Beike\Repositories;

use Beike\Models\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PageRepo
{
    private static $allPagesWithName;

    /**
     * @param array $filters
     * @return Builder
     */
    public static function getBuilder(array $filters = []): Builder
    {
        $builder = Page::query()->with('description');
        if (isset($filters['is_active'])) {
            $builder->where('active', (bool) $filters['is_active']);
        }

        if (isset($filters['page_category_id'])) {
            $pageCategoryId = (int) $filters['page_category_id'];
            $builder->where('page_category_id', $pageCategoryId);
        }

        $builder->orderByDesc('id');

        return $builder;
    }

    /**
     * 获取所有启用的文章列表
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public static function getActivePages(array $filters = []): LengthAwarePaginator
    {
        $filters['is_active']        = 1;
        $filters['page_category_id'] = 0;

        $builder                = self::getBuilder($filters);

        return $builder->paginate(perPage());
    }

    /**
     * 获取启用的非单页
     *
     * @return LengthAwarePaginator
     */
    public static function getCategoryPages(): LengthAwarePaginator
    {
        $filters['is_active'] = 1;

        $builder              = self::getBuilder($filters)->where('page_category_id', '>', 0);

        return $builder->paginate(perPage());
    }

    /**
     * 通过品牌ID获取单页名称
     * @param $id
     * @return mixed|string
     */
    public static function getName($id)
    {
        $categories = self::getAllPagesWithName();

        return $categories[$id]['name'] ?? '';
    }

    /**
     * 获取所有单页ID和名称列表
     * @return array|null
     */
    public static function getAllPagesWithName(): ?array
    {
        if (self::$allPagesWithName !== null) {
            return self::$allPagesWithName;
        }

        $items = [];
        // 先获取筛选后的 page_id（复用 getBuilder 的过滤逻辑）
        $pageIds = self::getBuilder()->select('pages.id')->pluck('id');

        if ($pageIds->isNotEmpty()) {
            // 只取 title 字段，避免 loading content 等大字段导致内存溢出
            $titles = DB::table('page_descriptions')
                ->whereIn('page_id', $pageIds)
                ->where('locale', locale())
                ->select(['page_id', 'title'])
                ->get();

            foreach ($titles as $row) {
                $items[$row->page_id] = [
                    'id'   => $row->page_id,
                    'name' => $row->title ?? '',
                ];
            }
        }

        return self::$allPagesWithName = $items;
    }
}
