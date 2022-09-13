<?php
/**
 * CountryController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-08-29 16:17:04
 * @modified   2022-08-29 16:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\CountryRepo;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $countries = CountryRepo::list($request->only('name', 'code', 'status'));

        $data = [
            'country' => $countries,
        ];

        if ($request->expectsJson()) {
            return json_success(trans('common.success'), $data);
        }

        return view('admin::pages.country.index', $data);
    }

    public function store(Request $request)
    {
        $Country = CountryRepo::create($request->only('name', 'code', 'sort_order', 'status'));

        return json_success(trans('common.created_success'), $Country);
    }

    public function update(Request $request, int $id)
    {
        $Country = CountryRepo::update($id, $request->only('name', 'code', 'sort_order', 'status'));

        return json_success(trans('common.updated_success'), $Country);
    }

    public function destroy(int $id)
    {
        CountryRepo::delete($id);

        return json_success(trans('common.deleted_success'));
    }

}
