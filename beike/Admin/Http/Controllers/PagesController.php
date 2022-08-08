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

    public function store(Request $request)
    {
        $requestData = json_decode($request->getContent(), true);
        $page = PageRepo::createOrUpdate($requestData);
        return json_success('保存成功', $page);
    }

    public function update(Request $request, int $pageId)
    {
        $requestData = json_decode($request->getContent(), true);
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
