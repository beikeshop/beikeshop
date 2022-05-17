<?php

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\CategoryRequest;
use Beike\Admin\Http\Resources\CategoryResource;
use Beike\Admin\Repositories\CategoryRepo;
use Beike\Models\Category;
use Beike\Admin\Services\CategoryService;
use Illuminate\Http\Request;

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
}
