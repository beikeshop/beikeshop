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

use Exception;
use Illuminate\Http\Request;
use Beike\Repositories\LanguageRepo;

class LanguageController extends Controller
{
    /**
     * 语言列表
     * @return mixed
     */
    public function index()
    {
        $languages = LanguageRepo::all();

        $data = [
            'languages' => $languages,
        ];

        return view('admin::pages.languages.index', $data);
    }

    /**
     * 新建语言
     * @param Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        $language = LanguageRepo::create($request->all());

        return json_success(trans('common.created_success'), $language);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function update(Request $request, int $id): array
    {
        $language = LanguageRepo::update($id, $request->all());

        return json_success(trans('common.updated_success'), $language);
    }


    /**
     * 删除语言
     *
     * @param int $currencyId
     * @return array
     */
    public function destroy(int $currencyId): array
    {
        LanguageRepo::delete($currencyId);

        return json_success(trans('common.deleted_success'));
    }
}
