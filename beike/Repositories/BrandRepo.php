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
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Model;

class BrandRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return Builder|Model
     */
    public static function create($data)
    {
        return Brand::query()->create($data);
    }

    /**
     * @param $brand
     * @param $data
     * @return Builder|Builder[]|Collection|Model|mixed
     * @throws Exception
     */
    public static function update($brand, $data)
    {
        if (!$brand instanceof Brand) {
            $brand = Brand::query()->find($brand);
        }
        if (!$brand) {
            throw new Exception("品牌id $brand 不存在");
        }
        $brand->update($data);
        return $brand;
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
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
     * @return LengthAwarePaginator
     */
    public static function list($data): LengthAwarePaginator
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
        $builder->orderByDesc('created_at');

        return $builder->paginate(10)->withQueryString();
    }

    public static function listGroupByFirst(): array
    {
        $brands = Brand::query()->where('status', true)->get();

        $results = [];
        foreach ($brands as $brand) {
            $results[$brand->first][] = (new BrandDetail($brand))->jsonSerialize();
        }

        return $results;
    }

    public static function autocomplete($name, $onlyActive = 1)
    {
        $builder = Brand::query()
            ->where('name', 'like', "$name%")
            ->select('id', 'name', 'status');
        if ($onlyActive) {
            $builder->where('status', 1);
        }

        return $builder->limit(10)->get();
    }

    /**
     * 获取商品名称
     * @param $id
     * @return HigherOrderBuilderProxy|mixed|string
     */
    public static function getName($id)
    {
        $brand = Brand::query()->find($id);

        if ($brand) {
            return $brand->name;
        }
        return '';
    }


    /**
     * @param $ids
     * @return array
     */
    public static function getNames($ids): array
    {
        $brands = self::getListByIds($ids);
        return $brands->map(function ($brand) {
            return [
                'id' => $brand->id,
                'name' => $brand->name ?? ''
            ];
        })->toArray();
    }

    /**
     * 通过产品ID获取产品列表
     * @return array|Builder[]|Collection
     */
    public static function getListByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }
        $brands = Brand::query()
            ->whereIn('id', $ids)
            ->get();
        return $brands;
    }
}
