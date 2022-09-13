<?php
/**
 * MenusController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-07-21 10:00:25
 * @modified   2022-07-21 10:00:25
 */

namespace Plugin\LatestProducts\Controllers;

use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Resources\ProductSimple;

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
        $products = ProductRepo::getBuilder(['active' => 1])
            ->whereHas('master_sku')
            ->with('inCurrentWishlist')
            ->orderByDesc('updated_at')
            ->paginate(40);

        $data = [
            'products' => $products,
            'items' => ProductSimple::collection($products)->jsonSerialize(),
        ];
        return view("LatestProducts::latest_products", $data);
    }
}
