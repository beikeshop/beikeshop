<?php
/**
 * LanguageRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-07-05 16:38:18
 * @modified   2022-07-05 16:38:18
 */

namespace Beike\Repositories;

use Beike\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LanguageRepo
{
    /**
     * 创建一个language记录
     * @param $data
     * @return Builder|Model
     */
    public static function create($data)
    {
        $languageData = [
            'name'       => $data['name']   ?? '',
            'code'       => $data['code']   ?? '',
            'locale'     => $data['locale'] ?? '',
            'image'      => $data['image']  ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status'     => (bool) ($data['status'] ?? 1),
        ];

        $result = Language::query()->create($languageData);
        self::clearCache();

        return $result;
    }

    /**
     * @param $id
     * @param $data
     * @return Builder|Builder[]|Collection|Model
     * @throws \Exception
     */
    public static function update($id, $data)
    {
        $item = Language::query()->find($id);
        if (! $item) {
            throw new \Exception("语言id {$id} 不存在");
        }
        $languageData = [
            'name'       => $data['name']   ?? '',
            'code'       => $data['code']   ?? '',
            'locale'     => $data['locale'] ?? '',
            'image'      => $data['image']  ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status'     => (bool) ($data['status'] ?? 1),
        ];
        $item->update($languageData);
        self::clearCache();
        return $item;
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id)
    {
        return Language::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $item = Language::query()->find($id);
        if ($item) {
            $item->delete();
            self::clearCache();
        }
    }

    /**
     * 获取所有语言
     * @return Builder[]|Collection
     */
    public static function all()
    {
        if (Cache::has('language_all')) {
            return Cache::get('language_all');
        }

        $data = Language::query()->get();
        Cache::forever('language_all', $data);

        return $data;
    }

    /**
     * 获取所有启用的语言
     *
     * @return Builder[]|Collection
     */
    public static function enabled(): mixed
    {
        if (Cache::has('language_cache')) {
            return Cache::get('language_cache');
        }

        $data = Language::query()->where('status', true)->orderBy('sort_order', 'asc')->get();
        Cache::forever('language_cache', $data);

        return $data;
    }

    /**
     *  clear cache
     *
     * @return void
     */
    public static function clearCache(): void
    {
        if (Cache::has('language_cache')) {
            Cache::forget('language_cache');
        }

        if (Cache::has('language_all')) {
            Cache::forget('language_all');
        }
        self::all();
        self::enabled();
    }
}
