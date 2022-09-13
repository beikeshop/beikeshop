<?php
/**
 * CustomerService.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
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
        $data['locale'] = system_setting('base.locale');
        $data['from'] = 'admin';
        $customer = CustomerRepo::create($data);
        return $customer;
    }

}
