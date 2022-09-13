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
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
            'description'
        ])->orderByDesc('updated_at');

        return $builder->paginate();
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
            'position' => $data['position'] ?? 0,
            'active' => $data['active'] ?? true,
        ]);
        $page->saveOrFail();

        $page->descriptions()->delete();
        $page->descriptions()->createMany($data['descriptions']);
        $page->load(['descriptions']);
        return $page;
    }

    public static function deleteById($id)
    {
        $page = Page::query()->findOrFail($id);
        $page->descriptions()->delete();
        $page->delete();
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
                'id' => $page->id,
                'name' => $page->description->title,
                'status' => $page->active
            ];
        }
        return $results;
    }
}
