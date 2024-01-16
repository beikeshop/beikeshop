<?php
/**
 * UserRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-07-14 11:45:41
 * @modified   2022-07-14 11:45:41
 */

namespace Beike\Repositories;

use Beike\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class UserRepo
{
    /**
     * 创建一个记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $data['password'] = Hash::make($data['password']);

        return AdminUser::query()->create($data);
    }

    /**
     * @param $user
     * @param $data
     * @return bool|int
     */
    public static function update($user, $data)
    {
        if (! $user instanceof AdminUser) {
            $user = AdminUser::query()->findOrFail($user);
        }
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $user->update($data);
    }

    /**
     * @param $email
     * @return AdminUser
     */
    public static function findByEmail($email)
    {
        return AdminUser::query()->where('email', $email)->first();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return AdminUser::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        AdminUser::query()->find($id)->delete();
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list($data)
    {
        $builder = AdminUser::query();

        if (isset($data['name'])) {
            $builder->where('admin_users.name', 'like', "%{$data['name']}%");
        }
        if (isset($data['email'])) {
            $builder->where('admin_users.email', 'like', "%{$data['email']}%");
        }
        if (isset($data['active'])) {
            $builder->where('admin_users.active', $data['active']);
        }

        return $builder->paginate(perPage())->withQueryString();
    }
}
