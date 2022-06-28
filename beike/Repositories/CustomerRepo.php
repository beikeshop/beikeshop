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
     */
    public static function create($customerData)
    {
        return Customer::query()->insertGetId($customerData);
    }

}

