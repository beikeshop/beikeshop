<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\ProductRequest;
use Beike\Admin\Http\Resources\ProductAttributeResource;
use Beike\Admin\Http\Resources\ProductResource;
use Beike\Admin\Repositories\TaxClassRepo;
use Beike\Admin\Services\ProductService;
use Beike\Models\Product;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\ProductRepo;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected string $defaultRoute = 'products.index';

    public function index(Request $request)
    {
        $requestData = $request->all();
        $productList = ProductRepo::list($requestData);
        $products    = ProductResource::collection($productList)->resource;

        $data = [
            'categories' => CategoryRepo::flatten(locale()),
            'products'   => $products,
            'type'       => 'products',
        ];

        $data = hook_filter('admin.product.index.data', $data);

        if ($request->expectsJson()) {
            return $products;
        }

        return view('admin::pages.products.index', $data);
    }

    public function trashed(Request $request)
    {
        $requestData            = $request->all();
        $requestData['trashed'] = true;
        $productList            = ProductRepo::list($requestData);
        $products               = ProductResource::collection($productList)->resource;

        $data = [
            'categories' => CategoryRepo::flatten(locale()),
            'products'   => $products,
            'type'       => 'trashed',
        ];

        $data = hook_filter('admin.product.trashed.data', $data);

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
            $requestData = $request->all();
            $product     = (new ProductService)->create($requestData);

            $data = [
                'request_data' => $requestData,
                'product'      => $product,
            ];

            hook_action('admin.product.store.after', $data);

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
            $requestData = $request->all();
            $product     = (new ProductService)->update($product, $requestData);

            $data = [
                'request_data' => $requestData,
                'product'      => $product,
            ];
            hook_action('admin.product.update.after', $data);

            return redirect()->to($this->getRedirect())->with('success', trans('common.updated_success'));
        } catch (\Exception $e) {
            return redirect(admin_route('products.edit', $product))->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete();
        hook_action('admin.product.destroy.after', $product);

        return json_success(trans('common.deleted_success'));
    }

    public function restore(Request $request)
    {
        $productId = $request->id ?? 0;
        Product::withTrashed()->find($productId)->restore();

        hook_action('admin.product.restore.after', $productId);

        return ['success' => true];
    }

    protected function form(Request $request, Product $product)
    {
        if ($product->id) {
            $descriptions = $product->descriptions->keyBy('locale');
            $categoryIds  = $product->categories->pluck('id')->toArray();
            $product->load('brand', 'attributes');
        }

        $product = hook_filter('admin.product.form.product', $product);
        $taxClasses = TaxClassRepo::getList();
        array_unshift($taxClasses, ['title' => trans('admin/builder.text_no'), 'id' => 0]);

        $data = [
            'product'            => $product,
            'descriptions'       => $descriptions ?? [],
            'category_ids'       => $categoryIds  ?? [],
            'product_attributes' => ProductAttributeResource::collection($product->attributes),
            'relations'          => ProductResource::collection($product->relations)->resource,
            'languages'          => LanguageRepo::all(),
            'tax_classes'        => $taxClasses,
            'source'             => [
                'categories' => CategoryRepo::flatten(locale()),
            ],
            '_redirect'          => $this->getRedirect(),
        ];

        $data = hook_filter('admin.product.form.data', $data);

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
        $name       = ProductRepo::getNames($productIds);

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
        $productIds = $request->get('ids');
        ProductRepo::DeleteByIds($productIds);

        hook_action('admin.product.destroy_by_ids.after', $productIds);

        return json_success(trans('common.deleted_success'), []);
    }

    public function trashedClear()
    {
        ProductRepo::forceDeleteTrashed();
    }
}
