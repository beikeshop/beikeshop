<?php
/**
 * LanguageController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-05 16:37:04
 * @created    2022-07-05 16:37:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\LanguageRepo;
use Illuminate\Http\Request;

class LanguageController extends Controller
{

    public function index()
    {
        $languages = LanguageRepo::all();

        $data = [
            'languages' => $languages,
        ];

        return view('admin::pages.languages.index', $data);
    }

    public function store(Request $request)
    {
        $language = LanguageRepo::create($request->only('name', 'code', 'locale', 'image', 'sort_order', 'status'));

        return json_success('创建成功', $language);
    }

    public function update(Request $request, int $id)
    {
        $language = LanguageRepo::update($id, $request->only('name', 'code', 'locale', 'image', 'sort_order', 'status'));

        return json_success('更新成功！', $language);
    }

    public function destroy(Request $request, int $currencyId)
    {
        CurrencyRepo::delete($currencyId);

        return json_success('删除成功！');
    }
}
