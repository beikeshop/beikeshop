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
        return view('admin::pages.pages.form');
    }

    public function edit(Request $request, int $pageId)
    {
        $data = [
            'page' => PageRepo::findByPageId($pageId),
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
        return json_success('更新成功', $page);
    }

    public function destroy(Request $request, int $pageId)
    {
        PageRepo::deleteById($pageId);
        return json_success('删除成功');
    }
}
