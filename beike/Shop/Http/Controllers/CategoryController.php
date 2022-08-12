<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Illuminate\Http\Request;
use Beike\Repositories\ProductRepo;
use Beike\Repositories\CategoryRepo;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return CategoryRepo::list();
    }

    public function show(Request $request, Category $category)
    {
        $products = ProductRepo::getProductsByCategory($category->id);

        $data = [
            'category' => $category,
            'products' => $products->jsonSerialize(),
        ];

        return view('category', $data);
    }
}
