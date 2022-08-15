<?php
/**
 * CurrencyRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-30 15:22:05
 * @modified   2022-06-30 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\Currency;

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
        if (!$item) {
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

    public static function all()
    {
        return Currency::query()->get();
    }

    public static function listEnabled()
    {
        if (self::$enabledCurrencies !== null) {
            return self::$enabledCurrencies;
        }
        return self::$enabledCurrencies = Currency::query()->where('status', true)->get();
    }
}
