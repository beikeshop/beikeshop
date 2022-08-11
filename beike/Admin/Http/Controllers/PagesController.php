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

use Beike\Models\Page;
use Beike\Repositories\ProductRepo;
use Illuminate\Http\Request;
use Beike\Admin\Repositories\PageRepo;

class PagesController
{
    public function index()
    {
        $data = [
            'pages' => PageRepo::getList()
        ];
        return view('admin::pages.pages.index', $data);
    }

    public function create()
    {
        return view('admin::pages.pages.form', ['page' => new Page()]);
    }

    public function edit(Request $request, int $pageId)
    {
        $data = [
            'page' => PageRepo::findByPageId($pageId),
            'descriptions' => PageRepo::getDescriptionsByLocale($pageId),
        ];
        return view('admin::pages.pages.form', $data);
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
        PageRepo::createOrUpdate($requestData);
        return redirect()->to(admin_route('pages.index'));
    }

    public function update(Request $request, int $pageId)
    {
        $requestData = $request->all();
        $requestData['id'] = $pageId;
        $page = PageRepo::createOrUpdate($requestData);
        return redirect()->to(admin_route('pages.index'));
    }

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
