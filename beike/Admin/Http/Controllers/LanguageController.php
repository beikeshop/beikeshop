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
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

    public function store(Request $request): array
    {
        $language = LanguageRepo::create($request->only('name', 'code', 'locale', 'image', 'sort_order', 'status'));

        return json_success('创建成功', $language);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function update(Request $request, int $id): array
    {
        $language = LanguageRepo::update($id, $request->only('name', 'code', 'locale', 'image', 'sort_order', 'status'));

        return json_success('更新成功！', $language);
    }

    public function destroy(int $currencyId): array
    {
        LanguageRepo::delete($currencyId);

        return json_success('删除成功！');
    }
}
