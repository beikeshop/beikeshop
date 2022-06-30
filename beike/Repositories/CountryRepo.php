<?php
/**
 * CountryRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-30 15:22:05
 * @modified   2022-06-30 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\Country;

class CountryRepo
{
    /**
     * 创建一个country记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $id = Country::query()->insertGetId($data);
        return self::find($id);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int
     */
    public static function update($id, $data)
    {
        $country = Country::query()->find($id);
        if (!$country) {
            throw new \Exception("国家id {$id} 不存在");
        }
        $country->update($data);
        return $country;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return Country::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $country = Country::query()->find($id);
        if ($country) {
            $country->delete();
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list($data)
    {
        $builder = Country::query();

        if (isset($data['name'])) {
            $builder->where('countries.name', 'like', "%{$data['name']}%");
        }
        if (isset($data['code'])) {
            $builder->where('countries.code', 'like', "%{$data['email']}%");
        }
        if (isset($data['status'])) {
            $builder->where('countries.status', $data['status']);
        }

        return $builder->paginate(20)->withQueryString();
    }

    public static function all()
    {
        return Country::query()->select('id', 'name')->get();
    }
}
