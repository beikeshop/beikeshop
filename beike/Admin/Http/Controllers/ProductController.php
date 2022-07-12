<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\ProductResource;
use Beike\Admin\Repositories\CategoryRepo;
use Beike\Models\Product;
use Beike\Models\ProductDescription;
use Beike\Models\ProductSku;
use Beike\Admin\Services\ProductService;
use Beike\Repositories\ProductRepo;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected string $defaultRoute = 'products.index';

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            $products = ProductRepo::list($request->all());

            return ProductResource::collection($products);
        }

        $data = [
            'categories' => CategoryRepo::flatten(locale()),
        ];
        return view('admin::pages.products.index', $data);
    }

    public function create(Request $request)
    {
        return $this->form($request, new Product());
    }

    public function store(Request $request)
    {
        return $this->save($request, new Product());
    }

    public function edit(Request $request, Product $product)
    {
        return $this->form($request, $product);
    }

    public function update(Request $request, Product $product)
    {
        return $this->save($request, $product);
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

    protected function form(Request $request, Product $product)
    {
        if ($product->id) {
            $descriptions = $product->descriptions->keyBy('locale');
            $categoryIds = $product->categories->pluck('id')->toArray();
        }

        $data = [
            'product' => $product,
            'descriptions' => $descriptions ?? [],
            'category_ids' => $categoryIds ?? [],
            'source' => [
                'categories' => CategoryRepo::flatten(locale()),
            ],
            '_redirect' => $this->getRedirect(),
        ];

        return view('admin::pages.products.form.form', $data);
    }

    protected function save(Request $request, Product $product)
    {
        if ($product->id) {
            $product = (new ProductService)->update($product, $request->all());
        } else {
            $product = (new ProductService)->create($request->all());
        }

        return redirect($this->getRedirect())->with('success', 'product created');
    }
}
