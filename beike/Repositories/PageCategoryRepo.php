<?php
/**
 * PageCategoryRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-09 10:26:26
 * @modified   2023-02-09 10:26:26
 */

namespace Beike\Repositories;

use Beike\Models\PageCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PageCategoryRepo
{
    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public static function getList(array $filters = []): LengthAwarePaginator
    {
        $builder = self::getBuilder($filters);

        return $builder->paginate(perPage());
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public static function getActiveList(array $filters = []): mixed
    {
        $filters['is_active'] = 1;
        $limit                = (int) ($filters['limit'] ?? 0);
        $builder              = self::getBuilder($filters);
        if ($limit > 0) {
            return $builder->limit($limit)->get();
        }

        return $builder->paginate(perPage());
    }

    /**
     * @param array $filters
     * @return Builder
     */
    public static function getBuilder(array $filters = []): Builder
    {
        $builder = PageCategory::query()->with('description');

        $name = $filters['name'] ?? '';
        if ($name) {
            $builder->whereRelation('description', 'title', 'like', "%{$name}%");
        }

        if (isset($filters['is_active'])) {
            $builder->where('active', (bool) $filters['is_active']);
        }

        return $builder;
    }

    /**
     * 创建或更新文章分类
     *
     * @param $data
     * @return mixed
     * @throws \Exception|\Throwable
     */
    public static function createOrUpdate($data): mixed
    {
        try {
            DB::beginTransaction();
            $pageCategory = self::pushPageCategory($data);
            DB::commit();

            return $pageCategory;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Throwable
     */
    private static function pushPageCategory($data)
    {
        $id = $data['id'] ?? 0;
        if (empty($id)) {
            $pageCategory = new PageCategory($data);
        } else {
            $pageCategory = PageCategory::query()->find($id);
        }

        $pageCategory->fill([
            'parent_id' => (int) ($data['parent_id'] ?? 0),
            'position'  => (int) ($data['position'] ?? 0),
            'active'    => (bool) ($data['active'] ?? false),
        ]);
        $pageCategory->saveOrFail();

        $descriptions = $data['descriptions'] ?? [];
        if ($descriptions) {
            $pageCategory->descriptions()->delete();
            $pageCategory->descriptions()->createMany($data['descriptions']);
        }
        $pageCategory->load(['descriptions']);

        return $pageCategory;
    }

    /**
     * 删除文章分类
     */
    public static function deleteById($pageCategoryId)
    {
        $pageCategory = PageCategory::query()->findOrFail($pageCategoryId);
        $pageCategory->descriptions()->delete();
        $pageCategory->delete();
    }

    /**
     * 通过名字搜索文章分类
     *
     * @param $name
     * @return array
     */
    public static function autocomplete($name)
    {
        $pageCategories = self::getBuilder()
            ->whereHas('description', function ($query) use ($name) {
                $query->where('title', 'like', "%{$name}%");
            })->limit(10)->get();

        $results = [];
        foreach ($pageCategories as $item) {
            $results[] = [
                'id'     => $item->id,
                'name'   => $item->description->title,
                'status' => $item->active,
            ];
        }

        return $results;
    }

    /**
     * @param $pageCategory
     * @return string
     */
    public static function getName($pageCategory): string
    {
        if ($pageCategory instanceof PageCategory) {
            return $pageCategory->description->title ?? '';
        }

        $pageCategoryId = (int) $pageCategory;
        $pageCategory   = PageCategory::query()->whereHas('description', function ($query) use ($pageCategoryId) {
            $query->where('page_category_id', $pageCategoryId);
        })->first();

        return $pageCategory->description->title ?? '';
    }
}
