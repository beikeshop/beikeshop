<?php
/**
 * BrandController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-27 21:17:04
 * @modified   2022-07-27 21:17:04
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Repositories\AdminUserRepo;
use Beike\Repositories\AdminUserTokenRepo;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $user = current_user();
        $data = [
            'current_user' => $user,
            'tokens'       => AdminUserTokenRepo::getTokenByAdminUser($user)->pluck('token')->toArray(),
        ];

        return view('admin::pages.account.index', $data);
    }

    public function update(Request $request)
    {
        $user = current_user();

        $adminUserData = $request->all();
        AdminUserRepo::updateAdminUser($user->id, $adminUserData);

        return json_success(trans('common.updated_success'));
    }
}
