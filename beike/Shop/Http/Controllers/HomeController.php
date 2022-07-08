<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Repositories\ProductRepo;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'category_products' => ProductRepo::getProductsByCategories([100002, 100003, 100004, 100005]),
            'renders' => ['dr-slide_show']
        ];

        return view('home', $data);
    }
}
