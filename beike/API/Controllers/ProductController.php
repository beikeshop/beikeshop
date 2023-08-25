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
use Beike\Models\Brand;
use Beike\Models\Category;
use Beike\Models\Product;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductDetail;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Product list with filters
     *
     * @throws \Exception
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filterData = $request->only('keyword', 'attr', 'price', 'sort', 'order', 'per_page', 'category_id', 'brand_id');
        $products   = ProductRepo::getBuilder($filterData)->with('inCurrentWishlist')->paginate($filterData['per_page'] ?? perPage());

        $category = Category::query()->find($request->get('category_id'));
        $brand    = Brand::query()->find($request->get('brand_id'));

        return ProductSimple::collection($products)->additional([
            'category_name' => $category->description->name ?? '',
            'brand_name'    => $brand->name                 ?? '',
        ]);
    }

    /**
     * Product detail page
     *
     * @param Request $request
     * @param Product $product
     * @return ProductDetail
     */
    public function show(Request $request, Product $product): ProductDetail
    {
        return new ProductDetail($product);
    }
}
