<?php
/**
 * PagesController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 15:07:33
 * @modified   2022-08-08 15:07:33
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\PageRequest;
use Beike\Admin\Repositories\PageRepo;
use Beike\Models\Page;
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
    public function index()
    {
        $pageList = PageRepo::getList();
        $data     = [
            'pages'        => $pageList,
            'pages_format' => PageDetail::collection($pageList)->jsonSerialize(),
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
        return view('admin::pages.pages.form', ['page' => new Page()]);
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
            return redirect(admin_route('pages.index'))->withErrors(['error' => $e->getMessage()]);
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

            return redirect()->to(admin_route('pages.index'));
        } catch (\Exception $e) {
            return redirect(admin_route('pages.index'))->withErrors(['error' => $e->getMessage()]);
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
}
