<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Product;
use Beike\Shop\Repositories\ProductRepo;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request, Product $product)
    {
        $product = ProductRepo::getProductDetail($product);

        $data = [
            'product' => $product,
        ];

        return view('product', $data);
    }
}
