<?php
/**
 * ZoneController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-30 16:17:04
 * @modified   2022-06-30 16:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Resources\CustomerResource;
use Beike\Models\Customer;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\CustomerGroupRepo;
use Beike\Repositories\CustomerRepo;
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

        return json_success('成功!', $data);
    }
}
