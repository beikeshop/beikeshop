<?php
/**
 * CurrencyRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 15:22:05
 * @modified   2022-06-30 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRepo
{
    private static $enabledCurrencies;

    /**
     * 创建一个currency记录
     * @param $data
     * @return mixed
     */
    public static function create($data)
    {
        return Currency::query()->create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function update($id, $data)
    {
        $item = Currency::query()->find($id);
        if (! $item) {
            throw new \Exception("货币id {$id} 不存在");
        }
        $item->update($data);

        return $item;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        return Currency::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $item = Currency::query()->find($id);
        if ($item) {
            $item->delete();
        }
    }

    /**
     * 获取所有货币列表
     *
     * @return Builder[]|Collection
     */
    public static function all()
    {
        return Currency::query()->get();
    }

    /**
     * 查找所有已开启货币
     *
     * @return Builder[]|Collection
     */
    public static function listEnabled()
    {
        if (self::$enabledCurrencies !== null) {
            return self::$enabledCurrencies;
        }

        return self::$enabledCurrencies = Currency::query()->where('status', true)->get();
    }

    /**
     * 根据code查找货币
     * @return Builder[]|Collection
     */
    public static function findByCode($code)
    {
        return self::listEnabled()->where('code', $code)->firstOrFail();
    }
}
