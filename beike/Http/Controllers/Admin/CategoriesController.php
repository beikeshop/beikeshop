<?php

namespace Beike\Http\Controllers\Admin;

use Beike\Http\Requests\Admin\CategoryRequest;
use Beike\Http\Resources\Admin\CategoryResource;
use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Beike\Services\CategoryService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
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

        return view('beike::admin.pages.categories.index', $data);
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

        return view('beike::admin.pages.categories.form', $data);
    }

    protected function save(Request $request, ?Category $category = null)
    {
        (new CategoryService())->createOrUpdate($request->all(), $category);
        return redirect($this->getRedirect())->with('success', 'Category created successfully');
    }
}
