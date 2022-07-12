<?php
/**
 * ProductRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-23 11:19:23
 * @modified   2022-06-23 11:19:23
 */

namespace Beike\Repositories;

use Beike\Models\Product;
use Beike\Shop\Http\Resources\ProductList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductRepo
{
    /**
     * 获取产品详情
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
     * 通过单个或多个产品分类获取产品列表
     *
     * @param $categoryId
     * @return AnonymousResourceCollection
     */
    public static function getProductsByCategory($categoryId): AnonymousResourceCollection
    {
        $builder = self::getBuilder(['category_id' => $categoryId]);
        $products = $builder->get();
        $items = ProductList::collection($products);
        return $items;
    }


    public static function getBuilder($data = []) :Builder
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
        if (isset($data['model'])) {
            $builder->whereHas('skus', function ($query) use ($data) {
                $query->where('sku', 'like', "%{$data['sku']}%");
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

        return $builder;
    }

    public static function list($data)
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
                'image' => $product->image,
            ];
        }
        return $results;
    }
}
