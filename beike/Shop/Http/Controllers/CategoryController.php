<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\FlattenCategoryRepo;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\CategoryDetail;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return redirect('/');
    }

    public function show(Request $request, Category $category)
    {
        if (! $category->active) {
            return redirect(shop_route('home.index'));
        }
        $filterData = $request->only('attr', 'price', 'sort', 'order', 'per_page');
        $products   = ProductRepo::getProductsByCategory($category->id, $filterData);
        $category->load('description');
        $filterData = array_merge($filterData, ['category_id' => $category->id, 'active' => 1]);

        $data       = [
            'all_categories'  => FlattenCategoryRepo::getCategoryList(),
            'category'        => $category,
            'children'        => CategoryDetail::collection($category->activeChildren)->jsonSerialize(),
            'filter_data'     => [
                'attr'  => ProductRepo::getFilterAttribute($filterData),
                'price' => ProductRepo::getFilterPrice($filterData),
            ],
            'products_format' => ProductSimple::collection($products)->jsonSerialize(),
            'products'        => $products,
            'per_pages'       => CategoryRepo::getPerPages(),
        ];

        $data = hook_filter('category.show.data', $data);

        return view('category', $data);
    }
}
