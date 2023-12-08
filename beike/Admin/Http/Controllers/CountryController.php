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

use Beike\Admin\Http\Resources\CountryResource;
use Beike\Repositories\CountryRepo;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $countries = CountryRepo::list($request->all());

        $data = [
            'countries'         => $countries,
            'countries_format'  => CountryResource::collection($countries)->jsonSerialize(),
            'continents'        => CountryRepo::getContinents(),
        ];

        $data = hook_filter('admin.country.index.data', $data);
        if ($request->expectsJson()) {
            return json_success(trans('common.success'), $data);
        }

        return view('admin::pages.country.index', $data);
    }

    public function store(Request $request)
    {
        $country = CountryRepo::create($request->all());

        hook_action('admin.country.store.after', $country);

        return json_success(trans('common.created_success'), $country);
    }

    public function update(Request $request, int $id)
    {
        $country = CountryRepo::update($id, $request->all());

        hook_action('admin.country.store.after', $country);

        return json_success(trans('common.updated_success'), $country);
    }

    public function destroy(int $id)
    {
        CountryRepo::delete($id);

        hook_action('admin.country.destroy.after', $id);

        return json_success(trans('common.deleted_success'));
    }
}
