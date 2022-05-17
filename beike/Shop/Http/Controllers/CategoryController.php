<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Beike\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category)
    {
        $products = Product::query()
            ->with('description')
            ->latest()
            ->paginate();

        $data = [
            'category' => $category,
            'products' => $products,
        ];

        return view('category', $data);
    }
}
