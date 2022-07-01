<?php
/**
 * CartService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Sam Chen <sam.chen@opencart.cn>
 * @created    2022-01-05 10:12:57
 * @modified   2022-01-05 10:12:57
 */

namespace Beike\Shop\Services;


use Beike\Models\Customer;
use Beike\Repositories\CustomerRepo;
use Illuminate\Support\Facades\Hash;

class AccountService
{
    /**
     * 注册用户
     *
     * @param array $data // ['email', 'password']
     * @return Customer
     */
    public static function register(array $data): Customer
    {
        $data['customer_group_id'] = setting('default_customer_group_id', 1); // default_customer_group_id为默认客户组名称
        $data['status'] = !setting('approve_customer'); // approve_customer为是否需要审核客户
        $data['from'] = $data['from'] ?? 'pc';
        $data['locale'] = locale();

        if ($data['email'] ?? 0) {
            $data['name'] = substr($data['email'], 0, strrpos($data['email'], '@'));;
        }
        $data['avatar'] = '';

        return CustomerRepo::create($data);
    }
}
