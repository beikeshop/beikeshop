<?php
/**
 * AdminUserController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-01 11:44:54
 * @modified   2022-08-01 11:44:54
 */

namespace Beike\Admin\Http\Controllers;

use Beike\Admin\Http\Requests\AdminRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
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
        Cache::forget('spatie.permission.cache');
        $role = Role::query()->findOrFail($id);
        $permissionRepo = (new PermissionRepo())->setRole($role);
        $data = [
            'permissions' => $permissionRepo->getAllPermissions(),
            'role' => $role,
        ];
        return view('admin::pages.admin_roles.edit', $data);
    }


    /**
     * 保存后台用户角色
     *
     * @param AdminRoleRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(AdminRoleRequest $request): array
    {
        $adminUser = AdminRoleRepo::createAdminRole($request->toArray());
        return json_success(trans('common.created_success'), $adminUser);
    }

    public function update(Request $request, int $adminUserId)
    {
        $adminUser = AdminRoleRepo::updateAdminRole($request->toArray());
        return json_success(trans('common.updated_success'), $adminUser);
    }

    public function destroy(Request $request, int $adminUserId)
    {
        AdminRoleRepo::deleteAdminRole($adminUserId);
        return json_success(trans('common.deleted_success'));
    }
}
