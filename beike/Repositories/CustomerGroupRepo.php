<?php
/**
 * AddressRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-28 15:22:05
 * @modified   2022-06-28 15:22:05
 */

namespace Beike\Repositories;

use Beike\Models\CustomerGroup;

class CustomerGroupRepo
{
    /**
     * 创建一个CustomerGroup记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $id = CustomerGroup::query()->insertGetId($data);
        return self::find($id);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int
     */
    public static function update($id, $data)
    {
        $group = CustomerGroup::query()->find($id);
        if (!$group) {
            throw new \Exception("Customer Group id {$id} 不存在");
        }
        $group->update($data);
        return $group;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return CustomerGroup::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $group = CustomerGroup::query()->find($id);
        if ($group) {
            $group->delete();
        }
    }

    public static function list()
    {
        $builder = CustomerGroup::query();
        $groups = $builder->get();

        return $groups;
    }
}
