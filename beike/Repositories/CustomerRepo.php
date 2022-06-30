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
use Illuminate\Support\Facades\Hash;

class CustomerRepo
{
    /**
     * 创建一个customer记录
     * @param $customerData
     * @return int
     */
    public static function create($customerData)
    {
        $customerData['password'] = Hash::make($customerData['password']);
        return Customer::query()->insertGetId($customerData);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int
     */
    public static function update($id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
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
        $builder = Customer::query()
            ->leftJoin('customer_groups AS cg', 'customers.customer_group_id', 'cg.id')
            ->leftJoin('customer_group_descriptions AS cgd', function ($join) {
                $join->on('cgd.customer_group_id', 'cg.id')
                    ->where('cgd.locale', locale());
            })
            ->select(['customers.id', 'customers.email', 'customers.name', 'customers.avatar', 'customers.status', 'customers.from', 'cgd.name AS customer_group_name']);


        if (isset($data['name'])) {
            $builder->where('customers.name', 'like', "%{$data['name']}%");
        }
        if (isset($data['email'])) {
            $builder->where('customers.email', 'like', "%{$data['email']}%");
        }
        if (isset($data['status'])) {
            $builder->where('customers.status', $data['status']);
        }
        if (isset($data['from'])) {
            $builder->where('customers.from', $data['from']);
        }
        if (isset($data['customer_group_name'])) {
            $builder->where('cgd.name', 'like', "%{$data['name']}%");
        }

        return $builder->paginate(20)->withQueryString();
    }
}

