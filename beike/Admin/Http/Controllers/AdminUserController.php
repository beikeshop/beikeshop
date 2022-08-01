<?php
/**
 * AdminUserController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-08-01 11:44:54
 * @modified   2022-08-01 11:44:54
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Models\AdminUser;

class AdminUserController extends Controller
{
    public function index()
    {
        $data = [
            'admin_users' => AdminUser::query()->get(),
            'admin_user_groups' => [],
        ];

        return view('admin::pages.admin_users.index', $data);
    }
}
