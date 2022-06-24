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

namespace Beike\Shop\Http\Controllers\account;

use Beike\Models\Customer;
use Beike\Shop\Http\Controllers\Controller;
use function auth;
use function view;

class AccountController extends Controller
{
    public function index()
    {
        $data = auth(Customer::AUTH_GUARD)->user()->toArray();
        return view('account', $data);
    }

}
