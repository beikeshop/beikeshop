<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Category;
use Plugin\Guangda\Seller\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('description')->where('active', 1)->get();

        $data = [
            'categories' => $categories,
        ];

        return view('home', $data);
    }
}
