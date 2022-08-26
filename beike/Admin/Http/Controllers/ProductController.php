<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Models\Product;
use Illuminate\Http\Request;
use Beike\Repositories\ProductRepo;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Admin\Services\ProductService;
use Beike\Admin\Repositories\TaxClassRepo;
use Beike\Admin\Http\Requests\ProductRequest;
use Beike\Admin\Http\Resources\ProductResource;

class ProductController extends Controller
{
    protected string $defaultRoute = 'products.index';

    public function index(Request $request)
    {
        $requestData = $request->all();
        $productList = ProductRepo::list($requestData);
        $products = ProductResource::collection($productList)->resource;

        $data = [
            'categories' => CategoryRepo::flatten(locale()),
            'products' => $products,
            'type' => 'products',
        ];

        if ($request->expectsJson()) {
            return $products;
        }

        return view('admin::pages.products.index', $data);
    }

    public function trashed(Request $request)
    {
        $requestData = $request->all();
        $requestData['trashed'] = true;
        $productList = ProductRepo::list($requestData);
        $products = ProductResource::collection($productList)->resource;

        $data = [
            'categories' => CategoryRepo::flatten(locale()),
            'products' => $products,
            'type' => 'trashed',
        ];

        if ($request->expectsJson()) {
            return $products;
        }

        return view('admin::pages.products.index', $data);
    }

    public function create(Request $request)
    {
        return $this->form($request, new Product());
    }

    public function store(ProductRequest $request)
    {
        try {
            (new ProductService)->create($request->all());
            return redirect()->to(admin_route('products.index'))
                ->with('success', trans('common.created_success'));
        } catch (\Exception $e) {
            return redirect(admin_route('products.create'))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, Product $product)
    {
        return $this->form($request, $product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            (new ProductService)->update($product, $request->all());
            return redirect()->to($this->getRedirect())->with('success', trans('common.updated_success'));
        } catch (\Exception $e) {
            return redirect(admin_route('product.edit', $product))->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();
        return json_success(trans('common.deleted_success'));
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

    public function name(int $id)
    {
        $name = ProductRepo::getName($id);

        return json_success(trans('common.get_success'), $name);
    }


    /**
     * 根据商品ID批量获取商品名称
     *
     * @param Request $request
     * @return array
     */
    public function getNames(Request $request): array
    {
        $productIds = explode(',', $request->get('product_ids'));
        $name = ProductRepo::getNames($productIds);

        return json_success(trans('common.get_success'), $name);
    }


    public function autocomplete(Request $request)
    {
        $products = ProductRepo::autocomplete($request->get('name') ?? '');

        return json_success(trans('common.get_success'), $products);
    }

    public function updateStatus(Request $request)
    {
        ProductRepo::updateStatusByIds($request->get('ids'), $request->get('status'));

        return json_success(trans('common.updated_success'), []);
    }


    public function destroyByIds(Request $request)
    {
        ProductRepo::DeleteByIds($request->get('ids'));

        return json_success(trans('common.deleted_success'), []);
    }

    public function trashedClear()
    {
        ProductRepo::forceDeleteTrashed();
    }
}
