<?php

namespace Beike\Http\Controllers\Shop;

use Beike\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function show(Request $request, Product $product)
    {
        $product->load('description', 'skus');

        $data = [
            'product' => $product,
        ];

        return view('product', $data);
    }
}
