<?php
/**
 * MenusController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-07-21 10:00:25
 * @modified   2022-07-21 10:00:25
 */

namespace Plugin\LatestProducts\Controllers;

use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductList;
use Beike\Shop\Http\Controllers\Controller;

class MenusController extends Controller
{
    public function getRoutes(): array
    {
        return [
            'method' => __METHOD__,
            'route_list' => []
        ];
    }


    public function latestProducts()
    {
        $products = ProductRepo::getBuilder()->whereHas('master_sku')->with('inCurrentWishlist')->orderByDesc('updated_at')->paginate(40);
        $data = [
            'products' => $products,
            'items' => ProductList::collection($products)->jsonSerialize(),
        ];
        return view("LatestProducts::latest_products", $data);
    }
}
