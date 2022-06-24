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

namespace Beike\Shop\Repositories;

use Beike\Models\Category;
use Beike\Models\Customer;
use Beike\Shop\Http\Resources\CategoryList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepo
{
    /**
     * 创建一个customer记录
     */
    public static function create($customerData)
    {
        return Customer::query()->insertGetId([
            'name' => $customerData['name'],
            'email' => $customerData['email'],
            'password' => $customerData['password'],
            'status' => $customerData['status'],
            'avatar' => $customerData['avatar'],
            'customer_group_id' => $customerData['customer_group_id'],
            'language_id' => $customerData['language_id'],
            'status' => $customerData['status'],
            'from' => $customerData['from'],
        ]);
    }

}

