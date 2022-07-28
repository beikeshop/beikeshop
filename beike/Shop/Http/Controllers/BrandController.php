<?php

namespace Beike\Shop\Http\Controllers;

use Beike\Models\Product;
use Beike\Repositories\BrandRepo;
use Beike\Repositories\ProductRepo;
use Beike\Shop\Http\Resources\ProductDetail;
use Beike\Shop\Http\Resources\ProductList;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = BrandRepo::listGroupByFirst();
        $data = [
            'brands' => $brands,
        ];

        return view('brand/list', $data);
    }

    public function show(int $id)
    {
        $products = BrandRepo::find($id)->products()->paginate(20);

        $data = [
            'products' => ProductList::collection($products)->jsonSerialize(),
        ];

        return view('brand/info', $data);
    }
}
