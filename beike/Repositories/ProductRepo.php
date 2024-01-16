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
use Beike\Models\ProductAttribute;
use Beike\Models\ProductCategory;
use Beike\Models\ProductDescription;
use Beike\Models\ProductRelation;
use Beike\Models\ProductSku;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

        hook_filter('repo.product.get_detail', $product);

        return $product;
    }

    /**
     * 通过单个或多个商品分类获取商品列表
     *
     * @param $categoryId
     * @param $filterData
     * @return LengthAwarePaginator
     * @throws \Exception
     */
    public static function getProductsByCategory($categoryId, $filterData)
    {
        $builder = static::getBuilder(array_merge(['category_id' => $categoryId, 'active' => 1], $filterData));

        return $builder->with('inCurrentWishlist')
            ->paginate($filterData['per_page'] ?? perPage())
            ->withQueryString();
    }

    /**
     * 通过商品ID获取商品列表
     * @param $productIds
     * @return AnonymousResourceCollection
     * @throws \Exception
     */
    public static function getProductsByIds($productIds): AnonymousResourceCollection
    {
        if (! $productIds) {
            return ProductSimple::collection(new Collection());
        }
        $builder  = static::getBuilder(['product_ids' => $productIds])->whereHas('masterSku');
        $products = $builder->with('inCurrentWishlist')->get();

        return ProductSimple::collection($products);
    }

    /**
     * 获取商品筛选对象
     *
     * @param array $filters
     * @return Builder
     * @throws \Exception
     */
    public static function getBuilder(array $filters = []): Builder
    {
        $builder = Product::query()->with(['description', 'skus', 'masterSku', 'attributes', 'brand']);

        $builder->leftJoin('product_descriptions as pd', function ($build) {
            $build->whereColumn('pd.product_id', 'products.id')
                ->where('locale', locale());
        });

        $builder->select(['products.*', 'pd.name', 'pd.content', 'pd.meta_title', 'pd.meta_description', 'pd.meta_keywords', 'pd.name']);

        if (isset($filters['category_id'])) {
            $builder->whereHas('categories', function ($query) use ($filters) {
                if (is_array($filters['category_id'])) {
                    $query->whereIn('category_id', $filters['category_id']);
                } else {
                    $query->where('category_id', $filters['category_id']);
                }
            });
        }

        $brandId = $filters['brand_id'] ?? 0;
        if ($brandId) {
            $builder->where('brand_id', $brandId);
        }

        $productIds = $filters['product_ids'] ?? [];
        if ($productIds) {
            $builder->whereIn('products.id', $productIds);
            $productIds = implode(',', $productIds);
            $builder->orderByRaw("FIELD(products.id, {$productIds})");
        }

        // attr 格式:attr=10:10,13|11:34,23|3:4
        if (isset($filters['attr']) && $filters['attr']) {
            $attributes = self::parseFilterParamsAttr($filters['attr']);
            foreach ($attributes as $attribute) {
                $builder->whereHas('attributes', function ($query) use ($attribute) {
                    $query->where('attribute_id', $attribute['attr'])
                        ->whereIn('attribute_value_id', $attribute['value']);
                });
            }
        }

        if (isset($filters['sku']) || isset($filters['model'])) {
            $builder->whereHas('skus', function ($query) use ($filters) {
                if (isset($filters['sku'])) {
                    $query->where('sku', 'like', "%{$filters['sku']}%");
                }
                if (isset($filters['model'])) {
                    $query->where('model', 'like', "%{$filters['model']}%");
                }
            });
        }

        if (isset($filters['price']) && $filters['price']) {
            $builder->whereHas('skus', function ($query) use ($filters) {
                // price 格式:price=30-100
                $prices = explode('-', $filters['price']);
                if (! $prices[1]) {
                    $query->where('price', '>', $prices[0] ?: 0)->where('is_default', 1);
                } else {
                    $query->whereBetween('price', [$prices[0] ?? 0, $prices[1]])->where('is_default', 1);
                }
            });
        }

        if (isset($filters['name'])) {
            $builder->where('pd.name', 'like', "%{$filters['name']}%");
        }

        $keyword = trim($filters['keyword'] ?? '');
        if ($keyword) {
            $keywords = explode(' ', $keyword);
            $keywords = array_unique($keywords);
            $keywords = array_diff($keywords, ['']);
            $builder->where(function (Builder $query) use ($keywords) {
                $query->whereHas('skus', function (Builder $query) use ($keywords) {
                    $keywordFirst = array_shift($keywords);
                    $query->where('sku', 'like', "%{$keywordFirst}%")
                        ->orWhere('model', 'like', "%{$keywordFirst}%");

                    foreach ($keywords as $keyword) {
                        $query->orWhere('sku', 'like', "%{$keyword}%")
                            ->orWhere('model', 'like', "%{$keyword}%");
                    }
                });
                foreach ($keywords as $keyword) {
                    $query->orWhere('pd.name', 'like', "%{$keyword}%");
                }
            });
        }

        if (isset($filters['created_start'])) {
            $builder->where('products.created_at', '>', $filters['created_start']);
        }

        if (isset($filters['created_end'])) {
            $builder->where('products.created_at', '<', $filters['created_end']);
        }

        if (isset($filters['active'])) {
            $builder->where('active', (int) $filters['active']);
        }

        // 回收站
        if (isset($filters['trashed']) && $filters['trashed']) {
            $builder->onlyTrashed();
        }

        if (is_admin()) {
            $sort  = $filters['sort']  ?? 'products.created_at';
            $order = $filters['order'] ?? 'desc';
        } else {
            $sort  = $filters['sort']  ?? 'products.position';
            $order = $filters['order'] ?? 'asc';
        }

        if ($sort == 'product_skus.price') {
            $builder->join('product_skus', function ($query) {
                $query->on('product_skus.product_id', '=', 'products.id')
                    ->where('is_default', true);
            });
        }

        if (in_array($sort, ['created_at', 'updated_at', 'sales', 'position'])) {
            $sort = 'products.' . $sort;
        }

        if (in_array($sort, ['products.created_at', 'products.updated_at', 'products.sales', 'pd.name', 'products.position', 'product_skus.price'])) {
            $builder->orderBy($sort, $order);
        }

        return hook_filter('repo.product.builder', $builder);
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
                'value' => explode(',', $itemArr[1]),
            ];
        }, $attributes);

        return $attributes;
    }

    public static function getFilterAttribute($data): array
    {
        $builder = static::getBuilder(array_diff_key($data, ['attr' => '', 'price' => '']))
            ->select(['pa.attribute_id', 'pa.attribute_value_id'])
            ->with(['attributes.attribute.description', 'attributes.attribute_value.description'])
            ->leftJoin('product_attributes as pa', 'pa.product_id', 'products.id')
            ->whereNotNull('pa.attribute_id')
            ->distinct()
            ->reorder('pa.attribute_id');

        if ($attributesIds = system_setting('base.multi_filter', [])['attribute'] ?? []) {
            $builder->whereIn('pa.attribute_id', $attributesIds);
        }

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

        $results = array_map(function ($item) {
            $item['values'] = array_values($item['values']);

            return $item;
        }, $results);

        return array_values($results);
    }

    public static function getFilterPrice($data)
    {
        $selectPrice = $data['price'] ?? '-';
        // unset($data['price']);
        $builder = static::getBuilder(['category_id' => $data['category_id']])->leftJoin('product_skus as ps', 'products.id', 'ps.product_id')
            ->where('ps.is_default', 1);
        $min = $builder->min('ps.price');
        $max = $builder->max('ps.price');

        $priceArr  = explode('-', $selectPrice);
        $selectMin = $priceArr[0];
        $selectMax = $priceArr[1];

        return [
            'min'        => $min,
            'max'        => $max,
            'select_min' => ($selectMin && $selectMin > $min) ? $selectMin : $min,
            'select_max' => ($selectMax && $selectMax < $max) ? $selectMax : $max,
        ];
    }

    public static function list($data = [])
    {
        return static::getBuilder($data)->paginate($data['per_page'] ?? 20);
    }

    public static function autocomplete($name)
    {
        $products = Product::query()->with('description')
            ->whereHas('description', function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            })->limit(10)->get();

        return \Beike\Admin\Http\Resources\ProductSimple::collection($products)->jsonSerialize();
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
        $products = static::getBuilder()->select('id')->get();
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
            ->orderByRaw('FIELD(id, ' . implode(',', $productIds) . ')')
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
        $products = Product::onlyTrashed();

        $productsIds = $products->pluck('id')->toArray();

        ProductRelation::query()->whereIn('product_id', $productsIds)->orWhere('relation_id', $productsIds)->delete();
        ProductAttribute::query()->whereIn('product_id', $productsIds)->delete();
        ProductCategory::query()->whereIn('product_id', $productsIds)->delete();
        ProductSku::query()->whereIn('product_id', $productsIds)->delete();
        ProductDescription::query()->whereIn('product_id', $productsIds)->delete();

        $products->forceDelete();
    }

    public static function viewAdd(Product $product)
    {
        $minutes = system_setting('base.product_view_minutes', 1);
        $count   = $product->views()->where('session_id', get_session_id())->where('created_at', '>', now()->subMinutes($minutes))->count();
        // 如果当前session_id对该商品$minutes分钟内有个访问记录，则不重复记录访问次数。
        if ($count) {
            return;
        }
        $product->views()->create([
            'customer_id' => current_customer()->id ?? 0,
            'ip'          => request()->getClientIp(),
            'session_id'  => get_session_id(),
        ]);
    }
}
