<?php
/**
 * PagesController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-08 15:07:33
 * @modified   2022-08-08 15:07:33
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\PageRequest;
use Beike\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Beike\Admin\Repositories\PageRepo;
use Beike\Shop\Http\Resources\PageDetail;

class PagesController
{
    /**
     * 显示单页列表
     *
     * @return mixed
     */
    public function index()
    {
        $pageList = PageRepo::getList();
        $data = [
            'pages' => $pageList,
            'pages_format' => PageDetail::collection($pageList)->jsonSerialize()
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
        $requestData = $request->all();
        PageRepo::createOrUpdate($requestData);
        return redirect()->to(admin_route('pages.index'));
    }


    /**
     * @param Request $request
     * @param int $pageId
     * @return mixed
     */
    public function edit(Request $request, int $pageId)
    {
        $data = [
            'page' => PageRepo::findByPageId($pageId),
            'descriptions' => PageRepo::getDescriptionsByLocale($pageId),
        ];
        return view('admin::pages.pages.form', $data);
    }


    /**
     * 保存更新
     *
     * @param PageRequest $request
     * @param int $pageId
     * @return RedirectResponse
     */
    public function update(PageRequest $request, int $pageId)
    {
        $requestData = $request->all();
        $requestData['id'] = $pageId;
        $page = PageRepo::createOrUpdate($requestData);
        return redirect()->to(admin_route('pages.index'));
    }


    /**
     * 删除单页
     *
     * @param Request $request
     * @param int $pageId
     * @return RedirectResponse
     */
    public function destroy(Request $request, int $pageId)
    {
        PageRepo::deleteById($pageId);
        return redirect()->to(admin_route('pages.index'));
    }


    /**
     * 搜索页面标题自动完成
     * @param Request $request
     * @return array
     */
    public function autocomplete(Request $request): array
    {
        $products = PageRepo::autocomplete($request->get('name') ?? '');
        return json_success('获取成功！', $products);
    }


    /**
     * 获取单页名称
     * @param Page $page
     * @return array
     */
    public function name(Page $page): array
    {
        $name = $page->description->title ?? '';
        return json_success('获取成功', $name);
    }
}
