<?php
/**
 * CountryController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-15 14:27:07
 * @modified   2023-08-15 14:27:07
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\Country;
use Beike\Repositories\CountryRepo;
use Beike\Repositories\ZoneRepo;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        return CountryRepo::listEnabled();
    }

    public function zones(Request $request, Country $country)
    {
        return ZoneRepo::listByCountry($country->id);
    }
}
