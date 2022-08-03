<?php
/**
 * AccountController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-23 20:22:54
 * @modified   2022-06-23 20:22:54
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Models\Customer;
use Beike\Shop\Http\Controllers\Controller;
use http\Env\Request;

class EditController extends Controller
{
    public function index()
    {
        $customer = auth(Customer::AUTH_GUARD)->user();
        $data['customer'] = $customer;
        return view('account/edit', $data);
    }
}
