<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\ProductRequest;
use Beike\Admin\Http\Resources\ProductAttributeResource;
use Beike\Admin\Http\Resources\ProductResource;
use Beike\Admin\Http\Resources\ProductSimple;
use Beike\Admin\Repositories\TaxClassRepo;
use Beike\Admin\Services\ProductService;
use Beike\Libraries\Weight;
use Beike\Models\Product;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\FlattenCategoryRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\ProductRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected string $defaultRoute = 'products.index';

    public function index(Request $request)
    {
        $requestData    = $request->all();
        if (! isset($requestData['sort'])) {
            $requestData['sort']  = 'products.updated_at';
            $requestData['order'] = 'desc';
        }
        $productList    = ProductRepo::list($requestData);
        $products       = ProductResource::collection($productList);
        $productsFormat =  $products->jsonSerialize();

        session(['page' => $request->get('page', 1)]);

        $data = [
            'categories'      => CategoryRepo::flatten(locale()),
            'products_format' => $productsFormat,
            'products'        => $products,
            'type'            => 'products',
        ];

        $data = hook_filter('admin.product.index.data', $data);

        if ($request->expectsJson()) {
            return $productsFormat;
        }

        return view('admin::pages.products.index', $data);
    }

    public function trashed(Request $request)
    {
        $requestData            = $request->all();
        $requestData['trashed'] = true;
        $productList            = ProductRepo::list($requestData);
        $products               = ProductResource::collection($productList);
        $productsFormat         =  $products->jsonSerialize();

        $data = [
            'categories'      => CategoryRepo::flatten(locale()),
            'products_format' => $productsFormat,
            'products'        => $products,
            'type'            => 'trashed',
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
            DB::beginTransaction();
            $requestData = $request->all();
            $actionType  = $requestData['action_type'] ?? '';
            $product     = (new ProductService)->create($requestData);

            $data = [
                'request_data' => $requestData,
                'product'      => $product,
            ];

            hook_action('admin.product.store.after', $data);

            DB::commit();
            return redirect()->to($actionType == 'stay' ? admin_route('products.create') : admin_route('products.index'))->with('success', trans('common.created_success'));
        } catch (\Exception $e) {
            DB::rollBack();
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
            DB::beginTransaction();
            $requestData = $request->all();
            $actionType  = $requestData['action_type'] ?? '';
            $product     = (new ProductService)->update($product, $requestData);

            $data = [
                'request_data' => $requestData,
                'product'      => $product,
            ];
            hook_action('admin.product.update.after', $data);
            $page = session('page', 1);

            DB::commit();
            return redirect()->to($actionType == 'stay' ? admin_route('products.edit', [$product->id]) : admin_route('products.index', ['page' => $page]))->with('success', trans('common.updated_success'));
        } catch (\Exception $e) {
            DB::rollBack();
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

    /**
     * @param Request $request
     * @param Product $product
     * @return mixed
     * @throws \Exception
     */
    protected function form(Request $request, Product $product)
    {
        if ($product->id) {
            $descriptions = $product->descriptions->keyBy('locale');
            $categoryIds  = $product->categories->pluck('id')->toArray();
            $product->load('brand', 'attributes');
        }

        $product    = hook_filter('admin.product.form.product', $product);
        $taxClasses = TaxClassRepo::getList();
        array_unshift($taxClasses, ['title' => trans('admin/builder.text_no'), 'id' => 0]);

        $data = [
            'product'               => $product,
            'descriptions'          => $descriptions ?? [],
            'category_ids'          => $categoryIds  ?? [],
            'product_attributes'    => ProductAttributeResource::collection($product->attributes),
            'relations'             => ProductResource::collection($product->relations)->resource,
            'languages'             => LanguageRepo::all(),
            'tax_classes'           => $taxClasses,
            'weight_classes'        => Weight::getWeightUnits(),
            'source'                => [
                'flatten_categories' => FlattenCategoryRepo::getCategoryList(),
                'categories'         => CategoryRepo::flatten(locale(), false),
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
     * @return JsonResponse
     */
    public function getNames(Request $request): JsonResponse
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

    /**
     * @throws \Exception
     */
    public function latest(Request $request): JsonResponse
    {
        $limit          = $request->get('limit', 10);
        $productList    = ProductRepo::getBuilder(['active' => 1, 'sort' => 'id'])->limit($limit)->get();
        $products       = ProductSimple::collection($productList)->jsonSerialize();

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
