<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\CategoryRequest;
use Beike\Admin\Http\Resources\CategoryResource;
use Beike\Admin\Http\Resources\ProductSimple;
use Beike\Admin\Services\CategoryService;
use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Beike\Repositories\LanguageRepo;
use Beike\Repositories\ProductRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected string $defaultRoute = 'categories.index';

    public function index()
    {
        $categories = CategoryRepo::getAdminList();
        $data       = [
            'categories' => CategoryResource::collection($categories),
        ];
        $data = hook_filter('admin.category.index.data', $data);

        return view('admin::pages.categories.index', $data);
    }

    public function create(Request $request)
    {
        return $this->form($request);
    }

    public function store(CategoryRequest $request)
    {
        return $this->save($request);
    }

    public function edit(Request $request, Category $category)
    {
        return $this->form($request, $category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        return $this->save($request, $category);
    }

    /**
     * 删除分类
     * @param Request  $request
     * @param Category $category
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Category $category): JsonResponse
    {
        CategoryRepo::delete($category);
        hook_action('admin.category.destroy.after', $category);

        return json_success(trans('common.deleted_success'));
    }

    public function name(int $id)
    {
        $name = CategoryRepo::getName($id);

        return json_success(trans('common.get_success'), $name);
    }

    protected function form(Request $request, ?Category $category = null)
    {
        if ($category) {
            $descriptions  = $category->descriptions->keyBy('locale');
            $descendantIds = $category->getDescendantIds();
        }

        // 取出所有分类
        $categories = CategoryRepo::flatten(locale());

        // 如果是编辑分类，就排除自己和所有后代
        if ($category && $category->id) {
            $categories = $categories->reject(function ($item) use ($category, $descendantIds) {
                return $item->id == $category->id || in_array($item->id, $descendantIds);
            });
        }

        $data = [
            'category'     => $category ?? new Category,
            'languages'    => LanguageRepo::all(),
            'descriptions' => $descriptions ?? null,
            'categories'   => $categories,
            '_redirect'    => $this->getRedirect(),
        ];

        $data = hook_filter('admin.category.form.data', $data);

        return view('admin::pages.categories.form', $data);
    }

    protected function save(Request $request, ?Category $category = null)
    {
        $requestData = $request->all();

        $isUpdate    = ! is_null($category);
        $category    = (new CategoryService)->createOrUpdate($requestData, $category);
        $data        = [
            'category'     => $category,
            'request_data' => $requestData,
        ];

        hook_action('admin.category.save.after', $data);

        $success = trans('common.updated_success');

        return $isUpdate
        ? redirect()->back()->with('success', $success)
        : redirect($this->getRedirect())->with('success', $success);
    }

    public function autocomplete(Request $request)
    {
        $categories = CategoryRepo::autocomplete($request->get('name') ?? '');

        return json_success(trans('common.get_success'), $categories);
    }

    /**
     * 获取分类下商品列表
     *
     * @param Request  $request
     * @param Category $category
     * @return JsonResponse
     * @throws \Exception
     */
    public function getProducts(Request $request, Category $category): JsonResponse
    {
        $limit          = $request->get('limit', 10);
        $productList    = ProductRepo::getBuilder(['category_id' => $category->id, 'active' => 1])->limit($limit)->get();
        $products       = ProductSimple::collection($productList)->jsonSerialize();

        return json_success(trans('common.get_success'), $products);
    }
}
