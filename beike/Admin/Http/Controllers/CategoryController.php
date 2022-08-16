<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Models\Category;
use Illuminate\Http\Request;
use Beike\Repositories\CategoryRepo;
use Beike\Admin\Services\CategoryService;
use Beike\Admin\Http\Requests\CategoryRequest;
use Beike\Admin\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    protected string $defaultRoute = 'categories.index';

    public function index()
    {
        $categories = Category::with('description', 'children.description', 'children.children.description')
            ->where('parent_id', 0)
            ->get();

        $data = [
            'categories' => CategoryResource::collection($categories),
        ];

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
     * @param Request $request
     * @param Category $category
     * @return array
     * @throws \Exception
     */
    public function destroy(Request $request, Category $category): array
    {
        CategoryRepo::delete($category);
        return json_success(trans('common.deleted_success'));
    }


    public function name(int $id)
    {
        $name = CategoryRepo::getName($id);

        return json_success(trans('common.get_success'), $name);
    }

    protected function form(Request $request, Category $category = null)
    {
        if ($category) {
            $descriptions = $category->descriptions->keyBy('locale');
        }

        $data = [
            'category' => $category ?? new Category(),
            'descriptions' => $descriptions ?? null,
            'categories' => CategoryRepo::flatten(locale()),
            '_redirect' => $this->getRedirect(),
        ];

        return view('admin::pages.categories.form', $data);
    }

    protected function save(Request $request, ?Category $category = null)
    {
        (new CategoryService())->createOrUpdate($request->all(), $category);
        return redirect($this->getRedirect())->with('success', 'Category created successfully');
    }

    public function autocomplete(Request $request)
    {
        $categories = CategoryRepo::autocomplete($request->get('name') ?? '');

        return json_success(trans('common.get_success'), $categories);
    }
}
