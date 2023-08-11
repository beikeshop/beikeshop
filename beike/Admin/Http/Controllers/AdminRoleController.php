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
use Beike\Admin\Repositories\AdminRoleRepo;
use Beike\Admin\Repositories\PermissionRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    public function index()
    {
        $data = [
            'roles' => Role::query()->get(),
        ];
        $data = hook_filter('admin.admin_role.index.data', $data);

        return view('admin::pages.admin_roles.index', $data);
    }

    public function create(Request $request)
    {
        $permissionRepo = (new PermissionRepo());
        $data           = [
            'core_permissions'   => $permissionRepo->getRoleCorePermissions(),
            'plugin_permissions' => $permissionRepo->getRolePluginPermissions(),
        ];

        $data = hook_filter('admin.admin_role.create.data', $data);

        return view('admin::pages.admin_roles.edit', $data);
    }

    public function edit(Request $request, int $id)
    {
        Cache::forget('spatie.permission.cache');
        $role           = Role::query()->findOrFail($id);
        $permissionRepo = (new PermissionRepo())->setRole($role);
        $data           = [
            'core_permissions'   => $permissionRepo->getRoleCorePermissions(),
            'plugin_permissions' => $permissionRepo->getRolePluginPermissions(),
            'role'               => $role,
        ];
        $data = hook_filter('admin.admin_role.edit.data', $data);

        return view('admin::pages.admin_roles.edit', $data);
    }

    /**
     * 保存后台用户角色
     *
     * @param AdminRoleRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(AdminRoleRequest $request): JsonResponse
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
