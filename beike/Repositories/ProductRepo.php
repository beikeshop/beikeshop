<?php
/**
 * ProductRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-23 11:19:23
 * @modified   2022-06-23 11:19:23
 */

namespace Beike\Repositories;

use Beike\Models\Product;
use Beike\Models\ProductCategory;
use Beike\Models\ProductDescription;
use Beike\Models\ProductSku;
use Illuminate\Database\Eloquent\Builder;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductRepo
{
    private static $allProductsWithName;


    /**
     * 获取商品详情
     */
    public static function getProductDetail($product)
    {
        if (is_int($product)) {
            $product = Product::query()->findOrFail($product);
        }
        $product->load('description', 'skus', 'master_sku');
        return $product;
    }


    /**
     * 通过单个或多个商品分类获取商品列表
     *
     * @param $categoryId
     * @return AnonymousResourceCollection
     */
    public static function getProductsByCategory($categoryId): AnonymousResourceCollection
    {
        $builder = self::getBuilder(['category_id' => $categoryId, 'active' => 1]);
        $products = $builder->with('inCurrentWishlist')->get();
        return ProductSimple::collection($products);
    }


    /**
     * 通过商品ID获取商品列表
     * @param $productIds
     * @return AnonymousResourceCollection
     */
    public static function getProductsByIds($productIds): AnonymousResourceCollection
    {
        $builder = self::getBuilder(['product_ids' => $productIds])->whereHas('master_sku');
        $products = $builder->with('inCurrentWishlist')->get();
        return ProductSimple::collection($products);
    }


    /**
     * 获取商品筛选对象
     *
     * @param array $data
     * @return Builder
     */
    public static function getBuilder(array $data = []): Builder
    {
        $builder = Product::query()->with('description', 'skus', 'master_sku');

        if (isset($data['category_id'])) {
            $builder->whereHas('categories', function ($query) use ($data) {
                if (is_array($data['category_id'])) {
                    $query->whereIn('category_id', $data['category_id']);
                } else {
                    $query->where('category_id', $data['category_id']);
                }
            });
        }

        if (isset($data['product_ids'])) {
            $builder->whereIn('id', $data['product_ids']);
        }

        if (isset($data['sku']) || isset($data['model'])) {
            $builder->whereHas('skus', function ($query) use ($data) {
                if (isset($data['sku'])) {
                    $query->where('sku', 'like', "%{$data['sku']}%");
                }
                if (isset($data['model'])) {
                    $query->where('model', 'like', "%{$data['model']}%");
                }

            });
        }

        if (isset($data['name'])) {
            $builder->whereHas('description', function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%");
            });
        }

        if (isset($data['active'])) {
            $builder->where('active', (int)$data['active']);
        }

        // 回收站
        if (isset($data['trashed']) && $data['trashed']) {
            $builder->onlyTrashed();
        }

        $sort = $data['sort'] ?? 'updated_at';
        $order = $data['order'] ?? 'desc';
        $builder->orderBy($sort, $order);

        return $builder;
    }


    public static function list($data = [])
    {
        return self::getBuilder($data)->paginate($data['per_page'] ?? 20);
    }

    public static function autocomplete($name)
    {
        $products = Product::query()->with('description')
            ->whereHas('description', function ($query) use ($name) {
                $query->where('name', 'like', "{$name}%");
            })->limit(10)->get();
        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'id' => $product->id,
                'name' => $product->description->name,
                'status' => $product->active,
                'image' => $product->image,
            ];
        }
        return $results;
    }

    /**
     * 获取商品ID获取单个商品名称
     *
     * @param $id
     * @return HigherOrderBuilderProxy|mixed|string
     */
    public static function getNameById($id)
    {
        $product = Product::query()->find($id);
        if ($product) {
            return $product->description->name;
        }
        return '';
    }


    /**
     * 通过商品ID获取商品名称
     * @param $id
     * @return mixed|string
     */
    public static function getName($id)
    {
        $categories = self::getAllProductsWithName();
        return $categories[$id]['name'] ?? '';
    }


    /**
     * 获取所有商品ID和名称列表
     *
     * @return array|null
     */
    public static function getAllProductsWithName(): ?array
    {
        if (self::$allProductsWithName !== null) {
            return self::$allProductsWithName;
        }

        $items = [];
        $products = self::getBuilder()->select('id')->get();
        foreach ($products as $product) {
            $items[$product->id] = [
                'id' => $product->id,
                'name' => $product->description->name ?? '',
            ];
        }
        return self::$allProductsWithName = $items;
    }


    /**
     * @param $productIds
     * @return array
     */
    public static function getNames($productIds): array
    {
        $products = self::getListByProductIds($productIds);
        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->description->name ?? ''
            ];
        })->toArray();
    }


    /**
     * 通过商品ID获取商品列表
     * @return array|Builder[]|Collection
     */
    public static function getListByProductIds($productIds)
    {
        if (empty($productIds)) {
            return [];
        }
        $products = Product::query()
            ->with(['description'])
            ->whereIn('id', $productIds)
            ->get();
        return $products;
    }
    public static function DeleteByIds($ids)
    {
        Product::query()->whereIn('id', $ids)->delete();
    }

    public static function updateStatusByIds($ids, $status)
    {
        Product::query()->whereIn('id', $ids)->update(['active' => $status]);
    }

    public static function forceDeleteTrashed()
    {
        Product::onlyTrashed()->forceDelete();
    }
}
