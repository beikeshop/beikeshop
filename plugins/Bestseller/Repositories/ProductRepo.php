<?php
/**
 * ProductRepo.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-03-08 11:56:17
 * @modified   2023-03-08 11:56:17
 */

namespace Plugin\Bestseller\Repositories;

use Beike\Shop\Http\Resources\ProductSimple;

class ProductRepo
{
    /**
     * Get best seller
     *
     * @param $limit
     * @return array
     */
    public static function getBestSellerProducts($limit): array
    {
        $products = \Beike\Repositories\ProductRepo::getBuilder([
            'active' => 1,
            'sort'   => 'products.sales',
            'order'  => 'desc',
        ])
            ->whereHas('masterSku')
            ->limit($limit)->get();

        return ProductSimple::collection($products)->jsonSerialize();
    }
}
