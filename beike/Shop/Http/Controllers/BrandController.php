<?php

namespace Beike\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Beike\Repositories\BrandRepo;
use Beike\Shop\Http\Resources\ProductSimple;

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
        $brand = BrandRepo::find($id);
        $products = $brand->products()
            ->with([
                'master_sku',
                'description',
                'inCurrentWishlist'
            ])
            ->paginate(20);

        $data = [
            'brand' => $brand,
            'products' => ProductSimple::collection($products)->jsonSerialize(),
        ];

        return view('brand/info', $data);
    }

    public function autocomplete(Request $request)
    {
        $brands = BrandRepo::autocomplete($request->get('name') ?? '');

        return json_success(trans('common.get_success'), $brands);
    }
}
