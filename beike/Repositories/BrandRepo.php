<?php
/**
 * BrandRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
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
        $brandData = [
            'name'       => $data['name']  ?? '',
            'first'      => $data['first'] ?? '',
            'logo'       => $data['logo']  ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status'     => (bool) ($data['status'] ?? 1),
        ];

        return Brand::query()->create($brandData);
    }

    /**
     * @param $brand
     * @param $data
     * @return Builder|Builder[]|Collection|Model|mixed
     * @throws Exception
     */
    public static function update($brand, $data)
    {
        if (! $brand instanceof Brand) {
            $brand = Brand::query()->find($brand);
        }
        if (! $brand) {
            throw new Exception("品牌id $brand 不存在");
        }

        $brandData = [
            'name'       => $data['name']  ?? '',
            'first'      => $data['first'] ?? '',
            'logo'       => $data['logo']  ?? '',
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'status'     => (bool) ($data['status'] ?? 1),
        ];
        $brand->update($brandData);

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
     * @param $brand
     * @return void
     */
    public static function delete($brand)
    {
        if (! $brand instanceof Brand) {
            $brand = Brand::query()->find((int) $brand);
        }

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

        return $builder->paginate(perPage())->withQueryString();
    }

    /**
     * 获取商品品牌筛选builder
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
        ksort($results);

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
                'id'   => $brand->id,
                'name' => $brand->name ?? '',
            ];
        })->toArray();
    }

    /**
     * 通过商品ID获取商品列表
     * @return array|Builder[]|Collection
     */
    public static function getListByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        $builder = Brand::query()->whereIn('id', $ids);
        $ids     = implode(',', $ids);
        $builder->orderByRaw("FIELD(id, {$ids})");

        return $builder->get();
    }

    /**
     * 通过品牌ID获取品牌名称
     * @param $brand
     * @return mixed|string
     */
    public static function getName($brand)
    {
        $id         = is_int($brand) ? $brand : $brand->id;
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

        $items  = [];
        $brands = self::getBuilder()->select(['id', 'name'])->get();
        foreach ($brands as $brand) {
            $items[$brand->id] = [
                'id'   => $brand->id,
                'name' => $brand->name ?? '',
            ];
        }

        return self::$allBrandsWithName = $items;
    }
}
