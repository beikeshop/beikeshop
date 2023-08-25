<?php
/**
 * ProductController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-06-06 15:53:08
 * @modified   2023-06-06 15:53:08
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\Product;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductDetail;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filterData = $request->only('attr', 'price', 'sort', 'order', 'per_page', 'category_id');
        $products   = ProductRepo::getBuilder($filterData)->with('inCurrentWishlist')->paginate($filterData['per_page'] ?? perPage());

        return ProductSimple::collection($products);
    }

    public function show(Request $request, Product $product)
    {
        return new ProductDetail($product);
    }
}
