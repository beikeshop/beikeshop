<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Product;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductDetail;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 商品详情页
     * @param Request $request
     * @param Product $product
     * @return mixed
     */
    public function show(Request $request, Product $product)
    {
        $relationIds = $product->relations->pluck('id')->toArray();
        $product     = ProductRepo::getProductDetail($product);
        ProductRepo::viewAdd($product);

        $has_video = false;

        if (data_get($product, 'video') && !str_contains($product['video'], '<iframe'))
        {
            $has_video = true;
        }
        $data        = [
            'product'   => (new ProductDetail($product))->jsonSerialize(),
            'relations' => ProductRepo::getProductsByIds($relationIds)->jsonSerialize(),
            'has_video' => $has_video,
            'iconfont' => '<i class="iconfont">&#xe628;</i>'
        ];

        $data = hook_filter('product.show.data', $data);

        return view('product/product', $data);
    }

    /**
     * 通过关键字搜索商品
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $keyword  = $request->get('keyword');
        $attr     = $request->get('attr');
        $price    = $request->get('price');
        $products = ProductRepo::getBuilder(['keyword' => $keyword, 'attr' => $attr])
            ->where('active', true)
            ->paginate(perPage())
            ->withQueryString();

        $data = [
            'products' => $products,
            'items'    => ProductSimple::collection($products)->jsonSerialize(),
        ];

        $data = hook_filter('product.search.data', $data);

        return view('search', $data);
    }
}
