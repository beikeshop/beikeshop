<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
