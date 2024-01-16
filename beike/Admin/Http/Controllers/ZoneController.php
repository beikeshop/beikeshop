<?php
/**
 * ZoneController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-30 16:17:04
 * @modified   2022-06-30 16:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Repositories\CountryRepo;
use Beike\Repositories\ZoneRepo;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $zones = ZoneRepo::list($request->all());

        $data = [
            'zones'     => $zones,
            'countries' => CountryRepo::all(),
        ];

        $data = hook_filter('admin.zone.index.data', $data);

        if ($request->expectsJson()) {
            return json_success(trans('common.success'), $data);
        }

        return view('admin::pages.zones.index', $data);
    }

    public function store(Request $request)
    {
        $zone = ZoneRepo::create($request->only('country_id', 'name', 'code', 'sort_order', 'status'));

        return json_success(trans('common.created_success'), $zone);
    }

    public function update(Request $request, int $id)
    {
        $zone = ZoneRepo::update($id, $request->only('country_id', 'name', 'code', 'sort_order', 'status'));
        $zone->load('country');

        return json_success(trans('common.updated_success'), $zone);
    }

    public function destroy(int $id)
    {
        ZoneRepo::delete($id);

        return json_success(trans('common.deleted_success'));
    }

    public function listByCountry(Request $request, int $countryId)
    {
        ZoneRepo::listByCountry($countryId);

        $data = [
            'zones' => ZoneRepo::listByCountry($countryId),
        ];

        return json_success(trans('common.success'), $data);
    }
}
