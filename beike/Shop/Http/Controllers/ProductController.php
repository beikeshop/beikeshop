<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Product;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, Product $product)
    {
        $product = ProductRepo::getProductDetail($product);

        $data = [
            'product' => (new ProductDetail($product))->jsonSerialize(),
        ];
dd($data);
        return view('product', $data);
    }
}
