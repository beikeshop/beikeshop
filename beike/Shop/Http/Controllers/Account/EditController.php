<?php
/**
 * AccountController.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2022-06-23 20:22:54
 * @modified   2022-06-23 20:22:54
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\EditRequest;
use Illuminate\Http\RedirectResponse;

class EditController extends Controller
{
    public function index()
    {
        $customer         = current_customer();
        $data['customer'] = $customer;
        $data             = hook_filter('account.edit.index', $data);

        return view('account/edit', $data);
    }

    /**
     * 顾客修改个人信息
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        CustomerRepo::update(current_customer(), $request->only('name', 'email', 'avatar'));

        return redirect()->to(shop_route('account.edit.index'))->with('success', trans('common.edit_success'));
    }
}
