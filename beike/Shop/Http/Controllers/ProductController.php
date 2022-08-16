<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Product;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Http\Request;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductDetail;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * 产品详情页
     * @param Request $request
     * @param Product $product
     * @return mixed
     */
    public function show(Request $request, Product $product)
    {
        $product = ProductRepo::getProductDetail($product);
        $data = [
            'product' => (new ProductDetail($product))->jsonSerialize(),
        ];
        return view('product', $data);
    }


    /**
     * 通过关键字搜索产品
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $products = ProductRepo::getBuilder(['name' => $keyword])->where('active', true)->paginate();

        $data = [
            'products' => $products,
            'items' => ProductSimple::collection($products)->jsonSerialize(),
        ];

        return view('search', $data);
    }
}
