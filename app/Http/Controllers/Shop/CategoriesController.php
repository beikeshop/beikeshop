<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
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
