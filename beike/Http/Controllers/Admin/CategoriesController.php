<?php

namespace Beike\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Beike\Http\Requests\Admin\CategoryRequest;
use Beike\Http\Resources\Admin\CategoryResource;
use Beike\Models\Category;
use Beike\Services\CategoryService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
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

        $_redirect = $request->header('referer', admin_route('categories.index'));

        $data = [
            'category' => $category ?? new Category(),
            'descriptions' => $descriptions ?? null,
            '_redirect' => $_redirect,
        ];

        return view('beike::admin.pages.categories.form', $data);
    }

    protected function save(Request $request, Category $category = null)
    {
        if ($category) {
            $category = (new CategoryService())->update($category, $request->all());
        } else {
            $category = (new CategoryService())->create($request->all());
        }

        $_redirect = $request->_redirect ?? admin_route('categories.index');
        return redirect($_redirect)->with('success', 'Category created successfully');
    }
}
