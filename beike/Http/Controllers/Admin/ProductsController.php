<?php

namespace Beike\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Beike\Http\Resources\Admin\ProductResource;
use Beike\Models\Product;
use Beike\Services\ProductService;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with('description')
            ->withCount('skus');
        if ($request->trashed) {
            $query->onlyTrashed();
        }

        $products = $query->paginate();

        $data = [
            'products' => ProductResource::collection($products),
        ];

        return view('beike::admin.pages.products.index', $data);
    }

    public function create(Request $request)
    {
        return $this->form($request, new Product());
    }

    public function store(Request $request)
    {
        $product = (new ProductService)->create($request->all());

        $redirect = $request->_redirect ?? route('admin.products.index');
        return redirect($redirect)->with('success', 'product created');
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, Product $product)
    {
        return $this->form($request, $product);
    }

    public function update(Request $request, Product $product)
    {
        $product = (new ProductService)->update($product, $request->all());

        return redirect()->route('admin.products.index')->with('success', 'product updated');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        return ['success' => true];
    }

    public function restore(Request $request)
    {
        $productId = $request->id ?? 0;
        Product::withTrashed()->find($productId)->restore();

        return ['success' => true];
    }

    public function form(Request $request, Product $product)
    {
        $_redirect = $request->header('referer', admin_route('products.index'));

        if ($product->id) {
            $descriptions = $product->descriptions()->keyBy('locale');
        }

        $data = [
            'product' => $product,
            'descriptions' => $descriptions ?? [],
            '_redirect' => $_redirect,
        ];

        return view('beike::admin.pages.products.form.form', $data);
    }
}
