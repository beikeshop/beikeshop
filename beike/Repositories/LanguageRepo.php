<?php
/**
 * LanguageRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-05 16:38:18
 * @modified   2022-07-05 16:38:18
 */

namespace Beike\Repositories;

use Beike\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

        return Language::query()->create($languageData);
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
        }
    }

    /**
     * 获取所有语言
     * @return Builder[]|Collection
     */
    public static function all()
    {
        return Language::query()->get();
    }

    /**
     * 获取所有启用的语言
     * @return Builder[]|Collection
     */
    public static function enabled()
    {
        return Language::query()->where('status', true)->orderBy('sort_order', 'asc')->get();
    }
}
