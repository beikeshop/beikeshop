<?php

namespace App\Http\Controllers;

class HomeController
{
    public function index()
    {
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
