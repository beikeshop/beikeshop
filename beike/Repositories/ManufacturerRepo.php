<?php
/**
 * ManufacturerRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-27 20:42:05
 * @modified   2022-07-27 20:42:05
 */

namespace Beike\Repositories;

use Beike\Models\Manufacturer;

class ManufacturerRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $manufacturer = Manufacturer::query()->create($data);
        return $manufacturer;
    }

    /**
     * @param $manufacturer
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     * @throws \Exception
     */
    public static function update($manufacturer, $data)
    {
        if (!$manufacturer instanceof Manufacturer) {
            $manufacturer = Manufacturer::query()->find($manufacturer);
        }
        if (!$manufacturer) {
            throw new \Exception("品牌id {$manufacturer} 不存在");
        }
        $manufacturer->update($data);
        return $manufacturer;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return Manufacturer::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $manufacturer = Manufacturer::query()->find($id);
        if ($manufacturer) {
            $manufacturer->delete();
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list($data)
    {
        $builder = Manufacturer::query();

        if (isset($data['name'])) {
            $builder->where('name', 'like', "%{$data['name']}%");
        }
        if (isset($data['first'])) {
            $builder->where('first', $data['email']);
        }
        if (isset($data['status'])) {
            $builder->where('status', $data['status']);
        }

        return $builder->paginate(20)->withQueryString();
    }

    public static function listGroupByFirst()
    {
        $manufacturers = Manufacturer::query()->where('status', true)->get();

        $results = [];
        foreach ($manufacturers as $manufacturer) {
            $results[$manufacturer->first][] = $manufacturer;
        }

        return $results;
    }
}
