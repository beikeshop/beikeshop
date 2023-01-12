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

use Beike\Models\Attribute;
use Beike\Models\AttributeValue;
use Beike\Models\Product;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Database\Eloquent\Builder;
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
        $product->load('description', 'skus', 'masterSku', 'brand', 'relations');

        return $product;
    }

    /**
     * 通过单个或多个商品分类获取商品列表
     *
     * @param $categoryId
     * @return
     */
    public static function getProductsByCategory($categoryId, $filterData)
    {
        $builder  = self::getBuilder(array_merge(['category_id' => $categoryId, 'active' => 1], $filterData));
        $products = $builder->with('inCurrentWishlist')->paginate($filterData['per_page'] ?? perPage());

        return $products;
    }

    /**
     * 通过商品ID获取商品列表
     * @param $productIds
     * @return AnonymousResourceCollection
     */
    public static function getProductsByIds($productIds): AnonymousResourceCollection
    {
        if (! $productIds) {
            return ProductSimple::collection(new Collection());
        }
        $builder  = self::getBuilder(['product_ids' => $productIds])->whereHas('masterSku');
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
        $builder = Product::query()->with('description', 'skus', 'masterSku', 'attributes');

        $builder->leftJoin('product_descriptions as pd', function ($build) {
            $build->whereColumn('pd.product_id', 'products.id')
                ->where('locale', locale());
        });
        $builder->leftJoin('product_skus', function ($build) {
            $build->whereColumn('product_skus.product_id', 'products.id')
                ->where('is_default', 1);
        });
        $builder->select(['products.*', 'pd.name', 'pd.content', 'pd.meta_title', 'pd.meta_description', 'pd.meta_keywords', 'pd.name', 'product_skus.price']);

        if (isset($data['category_id'])) {
            $builder->whereHas('categories', function ($query) use ($data) {
                if (is_array($data['category_id'])) {
                    $query->whereIn('category_id', $data['category_id']);
                } else {
                    $query->where('category_id', $data['category_id']);
                }
            });
        }

        $productIds = $data['product_ids'] ?? [];
        if ($productIds) {
            $builder->whereIn('products.id', $productIds);
            $productIds = implode(',', $productIds);
            $builder->orderByRaw("FIELD(products.id, {$productIds})");
        }

        // attr 格式:attr=10:10/13|11:34/23|3:4
        if (isset($data['attr']) && $data['attr']) {
            $attributes = self::parseFilterParamsAttr($data['attr']);
            foreach ($attributes as $attribute) {
                $builder->whereHas('attributes', function ($query) use ($attribute) {
                    $query->where('attribute_id', $attribute['attr'])
                        ->whereIn('attribute_value_id', $attribute['value']);
                });
            }
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

        if (isset($data['price']) && $data['price']) {
            $builder->whereHas('skus', function ($query) use ($data) {
                // price 格式:price=30-100
                $prices = explode('-', $data['price']);
                if (! $prices[1]) {
                    $query->where('price', '>', $prices[0] ?: 0)->where('is_default', 1);
                } else {
                    $query->whereBetween('price', [$prices[0] ?? 0, $prices[1]])->where('is_default', 1);
                }
            });
        }

        if (isset($data['name'])) {
            $builder->where('pd.name', 'like', "%{$data['name']}%");
        }

        $keyword = $data['keyword'] ?? '';
        if ($keyword) {
            $builder->where(function (Builder $query) use ($keyword) {
                $query->whereHas('skus', function (Builder $query) use ($keyword) {
                    $query->where('sku', 'like', "%{$keyword}%")
                        ->orWhere('model', 'like', "%{$keyword}%");
                })->orWhere('pd.name', 'like', "%{$keyword}%");
            });
        }

        if (isset($data['active'])) {
            $builder->where('active', (int) $data['active']);
        }

        // 回收站
        if (isset($data['trashed']) && $data['trashed']) {
            $builder->onlyTrashed();
        }

        $sort  = $data['sort']  ?? 'products.position';
        $order = $data['order'] ?? 'desc';
        $builder->orderBy($sort, $order);

        return $builder;
    }

    public static function parseFilterParamsAttr($attr)
    {
        $attributes = explode('|', $attr);
        $attributes = array_map(function ($item) {
            $itemArr = explode(':', $item);
            if (count($itemArr) != 2) {
                throw new \Exception('Params attr has an error format!');
            }

            return [
                'attr'  => $itemArr[0],
                'value' => explode('/', $itemArr[1]),
            ];
        }, $attributes);

        return $attributes;
    }

    public static function getFilterAttribute($data)
    {
        $builder = self::getBuilder($data)->with(['attributes.attribute.description', 'attributes.attribute_value.description'])->leftJoin('product_attributes as pa', 'pa.product_id', 'products.id')
            ->whereNotNull('pa.attribute_id')
            ->select(['pa.attribute_id', 'pa.attribute_value_id'])
            ->distinct()
            ->reorder('pa.attribute_id');
        $productAttributes = $builder->get()->toArray();

        $attributeMap      = array_column(Attribute::query()->with('description')->orderBy('sort_order')->get()->toArray(), null, 'id');
        $attributeValueMap = array_column(AttributeValue::query()->with('description')->get()->toArray(), null, 'id');

        $attributes    = isset($data['attr']) ? self::parseFilterParamsAttr($data['attr']) : [];
        $attributeMaps = array_column($attributes, 'value', 'attr');
        $results       = [];
        foreach ($productAttributes as $item) {
            if (! isset($attributeMap[$item['attribute_id']]) || ! isset($attributeValueMap[$item['attribute_value_id']])) {
                continue;
            }
            $attribute      = $attributeMap[$item['attribute_id']];
            $attributeValue = $attributeValueMap[$item['attribute_value_id']];
            if (! isset($results[$item['attribute_id']])) {
                $results[$item['attribute_id']] = [
                    'id'   => $attribute['id'],
                    'name' => $attribute['description']['name'],
                ];
            }
            if (! isset($results[$item['attribute_id']]['values'][$item['attribute_value_id']])) {
                $results[$item['attribute_id']]['values'][$item['attribute_value_id']] = [
                    'id'       => $attributeValue['id'],
                    'name'     => $attributeValue['description']['name'],
                    'selected' => in_array($attributeValue['id'], $attributeMaps[$attribute['id']] ?? []),
                ];
            }
        }

        $results = array_map(function($item) {
            $item['values'] = array_values($item['values']);
            return $item;
        }, $results);

        return array_values($results);
    }

    public static function getFilterPrice($data)
    {
        $selectPrice = $data['price'] ?? '-';
        unset($data['price']);
        $builder = self::getBuilder($data)->leftJoin('product_skus as ps', 'products.id', 'ps.product_id')
            ->where('ps.is_default', 1);
        $min = $builder->min('ps.price');
        $max = $builder->max('ps.price');

        $priceArr = explode('-', $selectPrice);
        $selectMin = $priceArr[0];
        $selectMax = $priceArr[1];

        return [
            'min' => $min,
            'max' => $max,
            'select_min' =>  ($selectMin && $selectMin > $min) ? $selectMin : $min,
            'select_max' => ($selectMax && $selectMax < $max) ? $selectMax: $max,
        ];
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
                'id'     => $product->id,
                'name'   => $product->description->name,
                'status' => $product->active,
                'image'  => $product->image,
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
        return self::getNameById($id);
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

        $items    = [];
        $products = self::getBuilder()->select('id')->get();
        foreach ($products as $product) {
            $items[$product->id] = [
                'id'   => $product->id,
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
                'id'   => $product->id,
                'name' => $product->description->name ?? '',
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
