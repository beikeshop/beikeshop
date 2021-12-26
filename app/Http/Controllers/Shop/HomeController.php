<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Plugin\Guangda\Seller\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'price' => 1,
            'status' => true,
            'seller_id' => 100,
        ];

        $product = new Product($data);

        $payments = [
            '\Plugin\Guangda\WeChat\WeChat',
            '\Plugin\Guangda\Alipay\Alipay',
        ];

        $data['payments'] = [];
        foreach ($payments as $payment) {
            $data['payments'][] = (new $payment)->handle();
        }

        return view('home', $data);
    }
}
