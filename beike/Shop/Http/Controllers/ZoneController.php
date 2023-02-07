<?php
/**
 * ZoneController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-04 16:21:14
 * @modified   2022-07-04 16:21:14
 */

namespace Beike\Shop\Http\Controllers;

use Beike\Repositories\ZoneRepo;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index(Request $request, int $countryId)
    {
        ZoneRepo::listByCountry($countryId);

        $data = [
            'zones' => ZoneRepo::listByCountry($countryId),
        ];

        $data = hook_filter('zone.index.data', $data);

        return json_success(trans('common.success'), $data);
    }
}
