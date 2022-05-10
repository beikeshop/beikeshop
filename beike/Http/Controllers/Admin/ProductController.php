<?php

namespace Beike\Http\Controllers\Admin;

use Beike\Http\Resources\Admin\ProductResource;
use Beike\Models\Product;
use Beike\Repositories\CategoryRepo;
use Beike\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected string $defaultRoute = 'products.index';

    public function index(Request $request)
    {
        $query = Product::query()
            ->with('description')
            ->withCount('skus');

        // 回收站
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
        }

        $data = [
            'product' => $product,
            'descriptions' => $descriptions ?? [],
            'categories' => CategoryRepo::flatten(locale()),
            '_redirect' => $this->getRedirect(),
        ];

        return view('beike::admin.pages.products.form.form', $data);
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
