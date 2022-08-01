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
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Beike\Admin\Repositories\AdminRoleRepo;
use Beike\Admin\Repositories\PermissionRepo;

class AdminRoleController extends Controller
{
    public function index()
    {
        $data = [
            'roles' => Role::query()->get()
        ];
        return view('admin::pages.admin_roles.index', $data);
    }

    public function create(Request $request)
    {
        $permissionRepo = (new PermissionRepo());
        $data = [
            'permissions' => $permissionRepo->getAllPermissions(),
        ];
        return view('admin::pages.admin_roles.edit', $data);
    }

    public function edit(Request $request, int $id)
    {
        $adminUser = Auth::guard(AdminUser::AUTH_GUARD)->user();
        $data = [
            'permissions' => (new PermissionRepo($adminUser))->getAllPermissions(),
            'role' => Role::query()->findOrFail($id)
        ];
        app()['cache']->forget('spatie.permission.cache');
        return view('admin::pages.admin_roles.edit', $data);
    }

    public function store(Request $request)
    {
        $adminUser = AdminRoleRepo::createAdminRole($request->toArray());
        return json_success('保存成功', $adminUser);
    }

    public function update(Request $request, int $adminUserId)
    {
        $adminUser = AdminRoleRepo::updateAdminRole($request->toArray());
        return json_success('更新成功', $adminUser);
    }

    public function destroy(Request $request, int $adminUserId)
    {
        AdminRoleRepo::deleteAdminRole($adminUserId);
        return json_success('删除成功');
    }
}
