<?php
/**
 * CategoryRepo.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-16 17:45:41
 * @modified   2022-06-16 17:45:41
 */

namespace Beike\Repositories;

use Beike\Models\Customer;

class CustomerRepo
{
    /**
     * 创建一个customer记录
     * @param $customerData
     * @return int
     */
    public static function create($customerData)
    {
        return Customer::query()->insertGetId($customerData);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int
     */
    public static function update($id, $data)
    {
        return Customer::query()->find($id)->update($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return Customer::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        Customer::query()->find($id)->delete();
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function list($data)
    {
        $builder = Customer::query()->from('customers AS c')
            ->leftJoin('customer_groups AS cg', 'c.customer_group_id', 'cg.id')
            ->leftJoin('customer_group_descriptions AS cgd', function ($join) {
                $join->on('cgd.customer_group_id', 'cg.id')
                    ->where('cgd.language_id', current_language_id());
            })
            ->select(['c.id', 'c.email', 'c.name', 'c.avatar', 'c.status', 'c.from', 'cgd.name AS customer_group_name']);


        if (isset($data['name'])) {
            $builder->where('c.name', 'like', "%{$data['name']}%");
        }
        if (isset($data['email'])) {
            $builder->where('c.email', 'like', "%{$data['email']}%");
        }
        if (isset($data['status'])) {
            $builder->where('c.status', $data['status']);
        }
        if (isset($data['from'])) {
            $builder->where('c.from', $data['from']);
        }
        if (isset($data['customer_group_name'])) {
            $builder->where('cgd.name', 'like', "%{$data['name']}%");
        }

        return $builder->paginate(20)->withQueryString();
    }
}

