<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Models\Product;
use Illuminate\Http\Request;
use Beike\Repositories\ProductRepo;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Admin\Services\ProductService;
use Beike\Admin\Repositories\TaxClassRepo;
use Beike\Admin\Http\Resources\ProductResource;

class ProductController extends Controller
{
    protected string $defaultRoute = 'products.index';

    public function index(Request $request)
    {
        $product = ProductRepo::list($request->all());
        // if ($request->expectsJson()) {
            // return ProductResource::collection($products);
        // }
        // dd($product->getItems());
        // dd(ProductResource::collection($productitem));
        $data = [
            'categories' => CategoryRepo::flatten(locale()),
            'product' => $product,
        ];

        if ($request->expectsJson()) {
            return json_success('成功', $product);
        }

        return view('admin::pages.products.index', $data);
    }

    public function trashed(Request $request)
    {
        $requestData = $request->all();
        $requestData['trashed'] = true;
        if ($request->expectsJson()) {
            $products = ProductRepo::list($requestData);
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
            $product->load('brand');
        }

        $data = [
            'product' => $product,
            'descriptions' => $descriptions ?? [],
            'category_ids' => $categoryIds ?? [],
            'languages' => LanguageRepo::all(),
            'tax_classes' => TaxClassRepo::getList(),
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

    public function name(int $id)
    {
        $name = ProductRepo::getName($id);

        return json_success('获取成功', $name);
    }


    /**
     * 根据产品ID批量获取产品名称
     *
     * @param Request $request
     * @return array
     */
    public function getNames(Request $request): array
    {
        $productIds = explode(',', $request->get('product_ids'));
        $name = ProductRepo::getNames($productIds);

        return json_success('获取成功', $name);
    }


    public function autocomplete(Request $request)
    {
        $products = ProductRepo::autocomplete($request->get('name') ?? '');

        return json_success('获取成功！', $products);
    }
}
