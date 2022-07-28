<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Product;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductDetail;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(Request $request)
    {
        $addresses = AddressRepo::listByCustomer(current_customer());
        $data = [
            'countries' => CountryRepo::all(),
            'addresses' => AddressResource::collection($addresses),
        ];

        return view('account/address', $data);
    }

    public function show(Request $request, Product $product)
    {
        $product = ProductRepo::getProductDetail($product);

        $data = [
            'product' => (new ProductDetail($product))->jsonSerialize(),
        ];

        return view('product', $data);
    }
}
