<?php

namespace Beike\Http\Controllers\Shop;

use Beike\Models\Category;
use Beike\Models\Product;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category)
    {
        $products = Product::query()->paginate();

        $data = [
            'category' => $category,
            'products' => $products,
        ];

        return view('category', $data);
    }
}
