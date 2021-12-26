<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function show(Request $request, Product $product)
    {
        $product->load('skus');
        dd($product);
    }
}
