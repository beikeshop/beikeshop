<?php
/**
 * CategoryController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 17:00:40
 * @modified   2023-04-20 17:00:40
 */

namespace Beike\AdminAPI\Controllers;

use Beike\Admin\Http\Requests\CategoryRequest;
use Beike\Admin\Http\Resources\CategoryResource;
use Beike\Admin\Services\CategoryService;
use Beike\Models\Category;
use Beike\Repositories\CategoryRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController
{
    /**
     * 商品分类列表
     *
     * @return mixed
     */
    public function index()
    {
        $categories = CategoryRepo::getAdminList();
        $data       = [
            'categories' => CategoryResource::collection($categories),
        ];

        return hook_filter('admin_api.category.index.data', $data);
    }

    /**
     * 单个商品分类
     *
     * @param Request  $request
     * @param Category $category
     * @return mixed
     */
    public function show(Request $request, Category $category)
    {
        if (! $category->active) {
            return [];
        }
        $category->load('description');

        return hook_filter('admin_api.category.show.data', $category);
    }

    /**
     * 保存商品分类
     *
     * @param CategoryRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(CategoryRequest $request)
    {
        $requestData = $request->all();
        $category    = (new CategoryService())->createOrUpdate($requestData, null);
        $data        = [
            'category'     => $category,
            'request_data' => $requestData,
        ];

        hook_action('admin_api.category.save.after', $data);

        return $data;
    }

    /**
     * 更新产品分类
     *
     * @param CategoryRequest $request
     * @param Category        $category
     * @return array
     * @throws \Exception
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $requestData = $request->all();
        $category    = (new CategoryService())->createOrUpdate($requestData, $category);
        $data        = [
            'category'     => $category,
            'request_data' => $requestData,
        ];

        hook_action('admin_api.category.save.after', $data);

        return $data;
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
}
