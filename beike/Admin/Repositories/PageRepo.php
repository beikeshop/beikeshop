<?php
/**
 * PageRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-26 21:08:07
 * @modified   2022-07-26 21:08:07
 */

namespace Beike\Admin\Repositories;

use Beike\Models\Page;
use Beike\Shop\Http\Resources\PageSimple;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class PageRepo
{
    /**
     * 获取列表页数据
     *
     * @return LengthAwarePaginator
     */
    public static function getList(): LengthAwarePaginator
    {
        $builder = Page::query()->with([
            'description',
        ])->orderByDesc('updated_at');

        return $builder->paginate(perPage());
    }

    public static function findByPageId($pageId)
    {
        $page = Page::query()->findOrFail($pageId);
        $page->load(['descriptions']);

        return $page;
    }

    public static function getDescriptionsByLocale($pageId)
    {
        $page = self::findByPageId($pageId);

        return $page->descriptions->keyBy('locale')->toArray();
    }

    public static function createOrUpdate($data)
    {
        try {
            DB::beginTransaction();
            $region = self::pushPage($data);
            DB::commit();

            return $region;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public static function pushPage($data)
    {
        $id = $data['id'] ?? 0;
        if ($id) {
            $page = Page::query()->findOrFail($id);
        } else {
            $page = new Page();
        }
        $page->fill([
            'page_category_id' => (int) ($data['page_category_id'] ?? 0),
            'image'            => $data['image'] ?? '',
            'position'         => (int) ($data['position'] ?? 0),
            'active'           => (bool) ($data['active'] ?? true),
            'author'           => $data['author'] ?? '',
            'views'            => (int) ($data['views'] ?? 0),
        ]);

        $page->saveOrFail();

        $page->descriptions()->delete();
        $page->descriptions()->createMany($data['descriptions']);

        $products = $data['products'] ?? [];
        $page->pageProducts()->delete();

        if ($products) {
            $items = [];
            foreach ($products as $item) {
                $items[] = [
                    'product_id' => $item,
                ];
            }
            $page->pageProducts()->createMany($items);
        }

        $page->load(['descriptions', 'pageProducts']);

        return $page;
    }

    public static function deleteById($id)
    {
        $page = Page::query()->findOrFail($id);
        $page->descriptions()->delete();
        $page->delete();
    }

    public static function getNames($pageIds)
    {
        $pages = Page::query()->with('description')
            ->whereIn('id', $pageIds)
            ->get();

        $names = [];
        foreach ($pages as $page) {
            $names[] = [
                'id'   => $page->id,
                'name' => $page->description->title,
            ];
        }

        return $names;
    }

    /**
     * 根据文章 IDs 获取文章
     * @param $pageIds
     * @return AnonymousResourceCollection
     */
    public static function getPagesByIds($pageIds): AnonymousResourceCollection
    {
        $pages = Page::query()
            ->with('description')
            ->whereIn('id', $pageIds)
            ->where('active', true)
            ->get();

        return PageSimple::collection($pages);
    }

    /**
     * 页面内容自动完成
     *
     * @param $name
     * @return array
     */
    public static function autocomplete($name): array
    {
        $pages = Page::query()->with('description')
            ->whereHas('description', function ($query) use ($name) {
                $query->where('title', 'like', "{$name}%");
            })->limit(10)->get();
        $results = [];
        foreach ($pages as $page) {
            $results[] = [
                'id'     => $page->id,
                'name'   => $page->description->title,
                'status' => $page->active,
            ];
        }

        return $results;
    }
}
