<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductList;
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

        $data = [
            'category' => $category,
            'products' => $products,
        ];

        return view('category', $data);
    }

    public function autocomplete(Request $request)
    {
        $categories = CategoryRepo::autocomplete($request->get('name') ?? '');

        return json_success('获取成功！', $categories);
    }
}
