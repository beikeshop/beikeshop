<?php
/**
 * BrandRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-27 20:42:05
 * @modified   2022-07-27 20:42:05
 */

namespace Beike\Repositories;

use Beike\Models\Brand;
use Beike\Shop\Http\Resources\BrandDetail;

class BrandRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $brand = Brand::query()->create($data);
        return $brand;
    }

    /**
     * @param $brand
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     * @throws \Exception
     */
    public static function update($brand, $data)
    {
        if (!$brand instanceof Brand) {
            $brand = Brand::query()->find($brand);
        }
        if (!$brand) {
            throw new \Exception("品牌id {$brand} 不存在");
        }
        $brand->update($data);
        return $brand;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return Brand::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $brand = Brand::query()->find($id);
        if ($brand) {
            $brand->delete();
        }
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list($data)
    {
        $builder = Brand::query();

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
        $brands = Brand::query()->where('status', true)->get();

        $results = [];
        foreach ($brands as $brand) {
            $results[$brand->first][] = (new BrandDetail($brand))->jsonSerialize();
        }

        return $results;
    }
}
