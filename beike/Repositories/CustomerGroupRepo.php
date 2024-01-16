<?php
/**
 * AddressRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-28 15:22:05
 * @modified   2022-06-28 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\CustomerGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class CustomerGroupRepo
{
    /**
     * 创建一个CustomerGroup记录
     * @param $data
     * @return Builder|Model
     */
    public static function create($data): Model|Builder
    {
        return CustomerGroup::query()->create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return Builder|Builder[]|Collection|Model
     * @throws \Exception
     */
    public static function update($id, $data): Model|Collection|Builder|array
    {
        $group = CustomerGroup::query()->find($id);
        if (! $group) {
            throw new \Exception("Customer Group id {$id} 不存在");
        }
        $group->update($data);

        return $group;
    }

    /**
     * @param $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public static function find($id): Model|Collection|Builder|array|null
    {
        return CustomerGroup::query()->findOrFail($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function delete($id)
    {
        $defaultCustomerGroupId = system_setting('base.default_customer_group_id');
        if ($id == $defaultCustomerGroupId) {
            throw new NotAcceptableHttpException(trans('admin/customer_group.default_cannot_delete'));
        }
        $group = CustomerGroup::query()->find($id);
        if ($group) {
            $group->delete();
        }
    }

    /**
     * 获取用户组列表
     *
     * @return Builder[]|Collection
     */
    public static function list(): Collection|array
    {
        $builder = CustomerGroup::query()->with('description', 'descriptions');

        return $builder->get();
    }
}
