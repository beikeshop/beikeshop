<?php
/**
 * LanguageRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-05 16:38:18
 * @modified   2022-07-05 16:38:18
 */

namespace Beike\Repositories;


use Beike\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class LanguageRepo
{
    /**
     * 创建一个language记录
     * @param $data
     * @return Builder|Model
     */
    public static function create($data)
    {
        return Language::query()->create($data);
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
        if (!$item) {
            throw new \Exception("语言id {$id} 不存在");
        }
        $item->update($data);
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
        return Language::query()->where('status', true)->get();
    }
}
