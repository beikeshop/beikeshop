<?php
/**
 * AdminUserRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-01 20:30:44
 * @modified   2022-08-01 20:30:44
 */

namespace Beike\Admin\Repositories;

use Beike\Admin\Http\Resources\AdminUserDetail;
use Beike\Models\AdminUser;
use Beike\Repositories\AdminUserTokenRepo;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class AdminUserRepo
{
    /**
     * 获取后台用户管理员列表
     */
    public static function getAdminUsers(): array
    {
        $builder    = AdminUser::query()->with(['roles']);
        $adminUsers = $builder->get();

        return AdminUserDetail::collection($adminUsers)->jsonSerialize();
    }

    /**
     * 创建后台管理员用户
     *
     * @param $data
     * @return AdminUser
     */
    public static function createAdminUser($data): AdminUser
    {
        $adminUser = new AdminUser([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'locale'   => $data['locale'],
            'active'   => true,
        ]);
        $adminUser->save();

        if (isset($data['roles'])) {
            $adminUser->assignRole($data['roles']);
        }

        return $adminUser;
    }

    /**
     * 更新后台管理员用户
     *
     * @param $adminUserId
     * @param $data
     * @return mixed
     */
    public static function updateAdminUser($adminUserId, $data)
    {
        $password  = $data['password'] ?? '';
        $adminUser = AdminUser::query()->findOrFail($adminUserId);
        $userData  = [
            'name'   => $data['name'],
            'email'  => $data['email'],
            'locale' => $data['locale'],
            'active' => true,
        ];
        if ($password) {
            $userData['password'] = bcrypt($password);
        }
        $adminUser->update($userData);

        $roles = $data['roles'] ?? [];
        if ($roles) {
            $adminUser->syncRoles($roles);
        }

        $tokens = $data['tokens'] ?? [];
        AdminUserTokenRepo::updateTokensByUser($adminUser, $tokens);

        return $adminUser;
    }

    /**
     * 删除后台用户
     *
     * @param             $adminUserId
     * @throws \Exception
     */
    public static function deleteAdminUser($adminUserId)
    {
        if ($adminUserId == 1) {
            throw new NotAcceptableHttpException(trans('admin/customer.cannot_delete_root'));
        }
        $adminUser = AdminUser::query()->find($adminUserId);
        $adminUser->delete();
    }
}
