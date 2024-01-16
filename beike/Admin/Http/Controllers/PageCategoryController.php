<?php
/**
 * PageCategoryController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-02-09 10:21:27
 * @modified   2023-02-09 10:21:27
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\PageCategoryRequest;
use Beike\Admin\Http\Resources\PageCategoryResource;
use Beike\Models\PageCategory;
use Beike\Repositories\PageCategoryRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageCategoryController extends Controller
{
    /**
     * 显示文章分类列表
     *
     * @return mixed
     */
    public function index()
    {
        $pageCategoryList = PageCategoryRepo::getList();
        $data             = [
            'page_categories'        => $pageCategoryList,
            'page_categories_format' => PageCategoryResource::collection($pageCategoryList)->jsonSerialize(),
        ];

        $data = hook_filter('admin.page_category.index.data', $data);

        return view('admin::pages.page_categories.index', $data);
    }

    /**
     * 创建文章分类
     *
     * @return mixed
     */
    public function create(): mixed
    {
        return view('admin::pages.page_categories.form', ['page_category' => new PageCategory()]);
    }

    /**
     * 保存新建
     *
     * @param PageCategoryRequest $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(PageCategoryRequest $request)
    {
        try {
            $requestData = $request->all();
            hook_action('admin.page_category.store.before', $requestData);
            $pageCategory = PageCategoryRepo::createOrUpdate($requestData);

            hook_action('admin.page_category.store.after', ['page_category' => $pageCategory, 'request_data' => $requestData]);

            return redirect(admin_route('page_categories.index'));
        } catch (\Exception $e) {
            return redirect(admin_route('page_categories.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param Request      $request
     * @param PageCategory $pageCategory
     * @return mixed
     */
    public function edit(Request $request, PageCategory $pageCategory)
    {
        $pageCategory->load(['descriptions', 'parent.description']);
        $descriptions = $pageCategory->descriptions->keyBy('locale')->toArray();
        $data         = [
            'page_category' => $pageCategory,
            'descriptions'  => $descriptions,
        ];

        $data = hook_filter('admin.page_category.edit.data', $data);

        return view('admin::pages.page_categories.form', $data);
    }

    /**
     * 保存更新
     *
     * @param PageCategoryRequest $request
     * @param PageCategory        $pageCategory
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(PageCategoryRequest $request, PageCategory $pageCategory)
    {
        try {
            $requestData       = $request->all();
            $requestData['id'] = $pageCategory->id;
            hook_action('admin.page_category.update.before', $requestData);
            $pageCategory = PageCategoryRepo::createOrUpdate($requestData);
            hook_action('admin.page_category.update.after', ['page_category' => $pageCategory, 'request_data' => $requestData]);

            return redirect()->to(admin_route('page_categories.index'));
        } catch (\Exception $e) {
            return redirect(admin_route('page_categories.index'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * 删除单页
     *
     * @param Request $request
     * @param int     $pageId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $pageId): JsonResponse
    {
        PageCategoryRepo::deleteById($pageId);
        hook_action('admin.page_category.destroy.after', $pageId);

        return json_success(trans('common.deleted_success'));
    }

    /**
     * 搜索页面标题自动完成
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $products = PageCategoryRepo::autocomplete($request->get('name') ?? '');

        return json_success(trans('common.get_success'), $products);
    }

    /**
     * 获取单页名称
     * @param PageCategory $pageCategory
     * @return JsonResponse
     */
    public function name(PageCategory $pageCategory): JsonResponse
    {
        $name = $pageCategory->description->title ?? '';

        return json_success(trans('common.get_success'), $name);
    }
}
