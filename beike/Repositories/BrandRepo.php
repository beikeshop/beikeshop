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
    private static $allBrandsWithName;

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
     * @param $filters
     * @return LengthAwarePaginator
     */
    public static function list($filters): LengthAwarePaginator
    {
        $builder = self::getBuilder($filters);
        return $builder->paginate(10)->withQueryString();
    }


    /**
     * 获取产品品牌筛选builder
     * @param array $filters
     * @return Builder
     */
    public static function getBuilder(array $filters = []): Builder
    {
        $builder = Brand::query();
        if (isset($filters['name'])) {
            $builder->where('name', 'like', "%{$filters['name']}%");
        }
        if (isset($filters['first'])) {
            $builder->where('first', $filters['email']);
        }
        if (isset($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        $builder->orderByDesc('created_at');
        return $builder;
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
        return Brand::query()
            ->whereIn('id', $ids)
            ->get();
    }


    /**
     * 通过品牌ID获取品牌名称
     * @param $id
     * @return mixed|string
     */
    public static function getName($id)
    {
        $categories = self::getAllBrandsWithName();
        return $categories[$id]['name'] ?? '';
    }


    /**
     * 获取所有商品分类ID和名称列表
     * @return array|null
     */
    public static function getAllBrandsWithName(): ?array
    {
        if (self::$allBrandsWithName !== null) {
            return self::$allBrandsWithName;
        }

        $items = [];
        $brands = self::getBuilder()->select(['id', 'name'])->get();
        foreach ($brands as $brand) {
            $items[$brand->id] = [
                'id' => $brand->id,
                'name' => $brand->name ?? '',
            ];
        }
        return self::$allBrandsWithName = $items;
    }
}
