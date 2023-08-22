<?php
/**
 * HomeController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-06-06 15:50:32
 * @modified   2023-06-06 15:50:32
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $appHomeData = system_setting('base.app_home_setting');

        return json_success(trans('common.get_success'), $appHomeData);
    }
}
