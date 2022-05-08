<?php

namespace Beike\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
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
        $category = new Category();

        $data = [
            'category' => $category,
        ];

        return view('beike::admin.pages.categories.form', $data);
    }

    public function store(CategoryRequest $request)
    {
        $redirect = $request->_redirect ?? admin_route('categories.index');

        $category = (new CategoryService())->create($request->all());

        return redirect($redirect)->with('success', 'Category created successfully');
    }

    public function edit(Category $category)
    {

        $descriptions = $category->descriptions->keyBy('locale');
        $data = [
            'category' => $category,
            'descriptions' => $descriptions,
        ];

        return view('beike::admin.pages.categories.form', $data);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $redirect = $request->_redirect ?? admin_route('categories.index');

        $category = (new CategoryService())->update($category, $request->all());

        return redirect($redirect)->with('success', 'Category created successfully');
    }
}
