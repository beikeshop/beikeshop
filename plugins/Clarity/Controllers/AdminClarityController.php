<?php
/**
 * SocialController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-09-30 18:46:38
 * @modified   2022-09-30 18:46:38
 */

namespace Plugin\Clarity\Controllers;

use Beike\Admin\Http\Controllers\Controller;
use Beike\Repositories\SettingRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminClarityController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function saveSetting(Request $request): JsonResponse
    {
        SettingRepo::storeValue('setting', $request->all(), 'clarity', 'plugin');

        return json_success('保存成功');
    }
}
