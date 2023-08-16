<?php
/**
 * SettingController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-16 18:11:22
 * @modified   2023-08-16 18:11:22
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Repositories\SettingRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request): JsonResponse|array
    {
        try {
            return SettingRepo::getMobileSetting();
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
