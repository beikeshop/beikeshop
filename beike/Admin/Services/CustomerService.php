<?php
/**
 * CustomerService.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-07-01 11:15:25
 * @modified   2022-07-01 11:15:25
 */

namespace Beike\Admin\Services;

use Beike\Models\Category;
use Beike\Models\CategoryPath;
use Beike\Repositories\CustomerRepo;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public static function create($data)
    {
        $data['locale'] = setting('locale');
        $data['from'] = 'admin';
        $customer = CustomerRepo::create($data);
        return $customer;
    }

}
