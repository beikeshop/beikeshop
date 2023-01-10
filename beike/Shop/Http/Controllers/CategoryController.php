<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\ProductRepo;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return CategoryRepo::list();
    }

    public function show(Request $request, Category $category)
    {
        $products = ProductRepo::getProductsByCategory($category->id);

        $category->load('description');

        $data = [
            'category'        => $category,
            'products_format' => $products->jsonSerialize(),
            'products'        => $products,
        ];

        return view('category', $data);
    }
}
