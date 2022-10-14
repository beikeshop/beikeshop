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

namespace Plugin\Social\Controllers;

use Illuminate\Http\Request;
use Beike\Repositories\SettingRepo;
use Beike\Admin\Http\Controllers\Controller;

class AdminSocialController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function saveSetting(Request $request): array
    {
        SettingRepo::storeValue('setting', $request->all(), 'social', 'plugin');
        return json_success('保存成功');
    }
}
