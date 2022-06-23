<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Illuminate\Http\Request;
use Beike\Shop\Repositories\CategoryRepo;
use Beike\Shop\Repositories\ProductRepo;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return CategoryRepo::list();
    }

    public function show(Request $request, Category $category)
    {
        $products = ProductRepo::getProductsBuilder($category)->paginate();

        $data = [
            'category' => $category,
            'products' => $products,
        ];

        return view('category', $data);
    }
}
