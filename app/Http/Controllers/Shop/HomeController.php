<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use TorMorten\Eventy\Facades\Events as Eventy;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'price' => 1,
            'status' => true,
            'seller_id' => 100,
            'message' => '首页第一句话 XXX',
        ];

        $data = Eventy::filter('home.data', $data);
        return view('home', $data);
    }
}
