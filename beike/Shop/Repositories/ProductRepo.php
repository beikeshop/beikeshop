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

namespace Beike\Shop\Repositories;

use Beike\Models\Product;
use Beike\Shop\Http\Resources\ProductList;
use Illuminate\Database\Eloquent\Builder;

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
     * 通过多个产品分类获取产品列表
     *
     * @param $categoryIds
     * @return array
     */
    public static function getProductsByCategories($categoryIds)
    {
        $products = self::getProductsByCategory($categoryIds);
        $items = $products->groupBy('category_id');
        return $items;
    }


    /**
     * 通过单个产品分类获取产品列表
     *
     * @param $categoryId
     * @return array
     */
    public static function getProductsByCategory($categoryId)
    {
        $builder = self::getProductsBuilder($categoryId);
        $products = $builder->get();
        $items = ProductList::collection($products);
        return $items;
    }


    /**
     * 获取 Builder
     * @param $categoryId
     * @return Builder
     */
    public static function getProductsBuilder($categoryId): Builder
    {
        if (is_int($categoryId)) {
            $categoryId[] = $categoryId;
        }
        $builder = Product::query()
            ->select(['products.*', 'pc.category_id'])
            ->with(['description'])
            ->join('product_categories as pc', 'products.id', '=', 'pc.product_id')
            ->join('categories as c', 'pc.category_id', '=', 'c.id')
            ->whereIn('c.id', $categoryId);
        return $builder;
    }
}
