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

use Beike\Models\Address;

class AddressRepo
{
    /**
     * 创建一个address记录
     * @param $data
     * @return int
     */
    public static function create($data)
    {
        $id = Address::query()->insertGetId($data);
        return self::find($id);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int
     */
    public static function update($id, $data)
    {
        $address = Address::query()->find($id);
        if (!$address) {
            throw new \Exception("地址id {$id} 不存在");
        }
        $address->update($data);
        return $address;
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public static function find($id)
    {
        return Address::query()->find($id);
    }

    /**
     * @param $id
     * @return void
     */
    public static function delete($id)
    {
        $address = Address::query()->find($id);
        if ($address) {
            $address->delete();
        }
    }

    public static function listByCustomer($customer)
    {
        if (gettype($customer) != 'object') {
            $customer = CustomerRepo::find($customer);
        }
        if ($customer) {
            return $customer->addresses()->with('country')->get();
        }
    }
}
