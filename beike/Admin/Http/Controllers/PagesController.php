<?php

/**
 * PagesController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     guangda <service@guangda.work>
 * @created    2022-08-08 15:07:33
 * @modified   2022-08-08 15:07:33
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\PageRequest;
use Beike\Admin\Http\Resources\PageCategoryResource;
use Beike\Admin\Repositories\PageRepo;
use Beike\Models\Page;
use Beike\Repositories\PageCategoryRepo;
use Beike\Shop\Http\Resources\PageDetail;
use Beike\Shop\Http\Resources\ProductSimple;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * 显示单页列表
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        // 获取筛选参数
        $filters = [
            'category_id' => $request->input('category_id'),
            'title'       => $request->input('title'),
            // 可根据实际需求添加更多筛选项
        ];

        // 过滤掉空值
        $filters = array_filter($filters, function ($v) {
            return $v !== null && $v !== '';
        });

        // 获取筛选后的列表
        $pageList = PageRepo::getList($filters);

        // 获取分类
        $categories             = PageCategoryRepo::getBuilder()->get();
        $page_categories_format = PageCategoryResource::collection($categories)->jsonSerialize();

        // 生成 pages_format 并补充分类名称
        $pages_format     = PageDetail::collection($pageList)->jsonSerialize();
        $categoryTitleMap = collect($page_categories_format)->pluck('title', 'id')->all();
        foreach ($pages_format as &$item) {
            $categoryId            = $item['page_category_id'] ?? null;
            $item['category_name'] = $categoryId ? ($categoryTitleMap[$categoryId] ?? '') : '';
        }

        $data = [
            'pages'        => $pageList,
            'pages_format' => $pages_format,
            'categories'   => $page_categories_format,
            'filter'       => $filters,
        ];

        return view('admin::pages.pages.index', $data);
    }

    /**
     * 创建页面
     *
     * @return mixed
     */
    public function create()
    {
        return view('admin::pages.pages.form', ['page' => new Page]);
    }

    /**
     * 保存新建
     *
     * @param PageRequest $request
     * @return RedirectResponse
     */
    public function store(PageRequest $request)
    {
        try {
            $requestData = $request->all();
            $page        = PageRepo::createOrUpdate($requestData);
            hook_action('admin.page.store.after', ['request_data' => $requestData, 'page' => $page]);

            return redirect(admin_route('pages.index'));
        } catch (\Exception $e) {
            return $this->handleDatabaseException($e);
        }
    }

    /**
     * @param Request $request
     * @param Page    $page
     * @return mixed
     */
    public function edit(Request $request, Page $page)
    {
        $page->load(['products.description', 'category.description']);

        $data = [
            'page'         => $page,
            'products'     => ProductSimple::collection($page->products)->jsonSerialize(),
            'descriptions' => PageRepo::getDescriptionsByLocale($page->id),
        ];

        return view('admin::pages.pages.form', $data);
    }

    /**
     * 保存更新
     *
     * @param PageRequest $request
     * @param int         $pageId
     * @return RedirectResponse
     */
    public function update(PageRequest $request, int $pageId)
    {
        try {
            $requestData       = $request->all();
            $requestData['id'] = $pageId;
            $page              = PageRepo::createOrUpdate($requestData);
            hook_action('admin.page.update.after', ['request_data' => $requestData, 'page' => $page]);

            return redirect()->to(url()->previous())->with('success', trans('common.updated_success'));
        } catch (\Exception $e) {
            return $this->handleDatabaseException($e);
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
        PageRepo::deleteById($pageId);

        hook_action('admin.page.destroy.after', $pageId);

        return json_success(trans('common.deleted_success'));
    }

    /**
     * 搜索页面标题自动完成
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $products = PageRepo::autocomplete($request->get('name') ?? '');

        return json_success(trans('common.get_success'), $products);
    }

    /**
     * 根据文章ID批量获取文章名称
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNames(Request $request): JsonResponse
    {

        $pageIds = explode(',', $request->get('page_ids'));
        $name    = PageRepo::getNames($pageIds);

        return json_success(trans('common.get_success'), $name);
    }

    /**
     * 获取单页名称
     * @param Page $page
     * @return JsonResponse
     */
    public function name(Page $page): JsonResponse
    {
        $name = $page->description->title ?? '';

        return json_success(trans('common.get_success'), $name);
    }

    // 文章提交异常处理，优化长度限制提示
    private function handleDatabaseException(\Exception $e)
    {
        $errorMessage        = $e->getMessage();
        $userFriendlyMessage = $errorMessage;

        if (strpos($errorMessage, 'SQLSTATE[22001]') !== false) {
            preg_match("/Data too long for column '(\w+)'/", $errorMessage, $matches);
            if (isset($matches[1])) {
                $columnName          = $matches[1];
                $userFriendlyMessage = trans('admin/common.error_length_text', ['key' => $columnName]);
            }
        }

        return redirect()->back()->withInput()->withErrors(['error' => $userFriendlyMessage]);
    }
}
