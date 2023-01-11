<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return CategoryRepo::list();
    }

    public function show(Request $request, Category $category)
    {
        $filterData = $request->only('attr', 'price', 'sort', 'order', 'per_page');
        $products   = ProductRepo::getProductsByCategory($category->id, $filterData);
        $category->load('description');
        $filterData = array_merge($filterData, ['category_id' => $category->id, 'active' => 1]);

        $data       = [
            'all_categories' => CategoryRepo::getTwoLevelCategories(),
            'category'        => $category,
            'filter_data'     => ['attr' => ProductRepo::getFilterAttribute($filterData), 'price' => ProductRepo::getFilterPrice($filterData)],
            'products_format' => ProductSimple::collection($products)->jsonSerialize(),
            'products'        => $products,
            'per_pages'       => CategoryRepo::getPerPages(),
        ];

        return view('category', $data);
    }
}
