<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ProductSku;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function store(Request $request, ProductSku $sku)
    {
        $quantity = $request->quantity ?? 1;
        dd($sku);
    }
}
