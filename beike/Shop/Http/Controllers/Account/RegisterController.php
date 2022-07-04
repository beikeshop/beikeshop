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

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Models\Customer;
use Beike\Shop\Services\AccountService;
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
        $data = $request->only('register.email', 'register.password');
        AccountService::register($data['register']);

        return json_success("注册成功，您现在可以使用您的账号登录网站!");
    }
}
