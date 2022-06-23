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

class ProductRepo
{
    /**
     * 通过多个产品分类获取产品列表
     *
     * @param $categoryIds
     * @return array
     */
    public static function getProductsByCategories($categoryIds): array
    {
        $products = self::getProductsByCategory($categoryIds);
        $items = collect($products)->groupBy('category_id')->jsonSerialize();
        return $items;
    }


    /**
     * 通过单个产品分类获取产品列表
     *
     * @param $categoryId
     * @return array
     */
    public static function getProductsByCategory($categoryId): array
    {
        if (is_int($categoryId)) {
            $categoryId[] = $categoryId;
        }
        $products = Product::query()
            ->select(['products.*', 'pc.category_id'])
            ->join('product_categories as pc', 'products.id', '=', 'pc.product_id')
            ->join('categories as c', 'pc.category_id', '=', 'c.id')
            ->whereIn('c.id', $categoryId)
            ->get();

        $items = ProductList::collection($products)->jsonSerialize();
        return $items;
    }
}
