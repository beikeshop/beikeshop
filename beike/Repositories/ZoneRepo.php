<?php
/**
 * ZoneRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-30 15:22:05
 * @modified   2022-06-30 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\Zone;

class ZoneRepo
{
    /**
     * 创建一个zone记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $id = Zone::query()->insertGetId($data);
        return self::find($id);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int
     */
    public static function update($id, $data)
    {
        $zone = Zone::query()->find($id);
        if (!$zone) {
            throw new \Exception("省份/地区id {$id} 不存在");
        }
        $zone->update($data);
        return $zone;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return Zone::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $zone = Zone::query()->find($id);
        if ($zone) {
            $zone->delete();
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list($data)
    {
        $builder = Zone::query();

        if (isset($data['name'])) {
            $builder->where('zones.name', 'like', "%{$data['name']}%");
        }
        if (isset($data['code'])) {
            $builder->where('zones.code', 'like', "%{$data['email']}%");
        }
        if (isset($data['status'])) {
            $builder->where('zones.status', $data['status']);
        }

        return $builder->paginate(20)->withQueryString();
    }

    /**
     * 根据国家获取国家的省份
     * @param $country
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|\Illuminate\Support\HigherOrderCollectionProxy|mixed|void
     */
    public static function listByCountry($country)
    {
        if (gettype($country) != 'object') {
            $country = CountryRepo::find($country);
        }
        if ($country) {
            return $country->zones;
        }
    }
}
