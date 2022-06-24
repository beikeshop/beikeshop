<?php
/**
 * LoginController.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     TL <mengwb@opencart.cn>
 * @created    2022-06-22 20:22:54
 * @modified   2022-06-22 20:22:54
 */

namespace Beike\Shop\Http\Controllers\account;

use Beike\Models\Customer;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use function redirect;
use function view;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(RegisterRequest $request)
    {
        $customer = new Customer();
        $customer->name = $request->get('name', '');
        $customer->email = $request->get('email');
        $customer->customer_group_id = 0;
        $customer->language_id = 0;
        $customer->password = Hash::make($request->get('password'));
        $customer->save();

        return redirect(shop_route('login.index'));
    }
}
