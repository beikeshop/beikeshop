<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Beike\Shop\Repositories\CategoryRepo;
use Beike\Shop\Repositories\ProductRepo;
use Plugin\Guangda\Seller\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'categories' => CategoryRepo::getTwoLevelCategories(),
            'category_products' => ProductRepo::getProductsByCategories([100002, 100003, 100004, 100005]),
        ];

        return view('home', $data);
    }
}
