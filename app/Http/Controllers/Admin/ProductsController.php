<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with('description')
            ->withCount('skus')
            ->paginate();

        $data = [
            'products' => $products,
        ];

        return view('admin.pages.products.index', $data);
    }

    public function create()
    {
        return view('admin.pages.products.form.form');
    }

    public function store(Request $request)
    {
        $product = (new ProductService)->create($request->all());

        return redirect()->route('admin.products.index')->with('success', 'product created');
    }

    public function show($id)
    {
        //
    }

    public function edit(Product $product)
    {
        $product->loadMissing('descriptions');

        $data = [
            'product' => $product,
        ];
        return view('admin.pages.products.form.form', $data);
    }

    public function update(Request $request, Product $product)
    {
        $product = (new ProductService)->update($product, $request->all());

        return redirect()->route('admin.products.index')->with('success', 'product updated');
    }

    public function destroy($id)
    {
        //
    }
}
