<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Beike\Shop\Repositories\CategoryRepo;
use Plugin\Guangda\Seller\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'categories' => CategoryRepo::getTwoLevelCategories(),
        ];

        return view('home', $data);
    }
}
