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
use Beike\Services\DesignService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $appHomeData = system_setting('base.app_home_setting');
        $modules     = $appHomeData['modules'] ?? [];

        $productCodes = ['product', 'category', 'latest'];
        $moduleItems  = [];
        foreach ($modules as $module) {
            $code    = $module['code'];
            $content = $module['content'];
            if (in_array($code, $productCodes)) {
                $content['products'] = collect($content['products'])->pluck('id')->toArray();
            }

            $moduleItems[] = [
                'code'    => $code,
                'content' => DesignService::handleModuleContent($code, $content),
            ];
        }

        return json_success(trans('common.get_success'), $moduleItems);
    }
}
