<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'price' => 1,
            'status' => true,
            'seller_id' => 100,
        ];

        return view('home', $data);
    }
}
