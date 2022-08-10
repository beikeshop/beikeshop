<?php
/*
 * @copyright     2022 opencart.cn - All Rights Reserved.
 * @link          https://www.guangdawangluo.com
 * @Author        PS <pushuo@opencart.cn>
 * @Date          2022-08-10 15:05:22
 * @LastEditTime  2022-08-10 15:17:43
 */
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
use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\EditRequest;
use http\Env\Request;

class EditController extends Controller
{
    public function index()
    {
        $customer = current_customer();
        $data['customer'] = $customer;
        return view('account/edit', $data);
    }


    /**
     * 顾客修改个人信息
     * @param EditRequest $request
     * @return array
     */
    public function update(EditRequest $request)
    {
        CustomerRepo::update(current_customer(), $request->only('name', 'email', 'avatar'));

        return redirect()->to(shop_route('account.edit.index'))->with('success', '修改成功');
    }
}
