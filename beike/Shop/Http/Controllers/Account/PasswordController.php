<?php
/**
 * PasswordController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-12-14 10:58:54
 * @modified   2023-12-14 10:58:54
 */

namespace Beike\Shop\Http\Controllers\Account;

use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Controllers\Controller;
use Beike\Shop\Http\Requests\PasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Change password page.
     *
     * @return mixed
     */
    public function index(): mixed
    {
        $customer         = current_customer();
        $data['customer'] = $customer;
        $data             = hook_filter('account.password.index', $data);

        return view('account/password', $data);
    }

    /**
     * Request to change password.
     *
     * @param PasswordRequest $request
     * @return RedirectResponse
     */
    public function update(PasswordRequest $request): RedirectResponse
    {
        try {
            CustomerRepo::updatePassword(current_customer(), $request->all());

            return redirect()->to(shop_route('account.password.index'))
                ->with('success', trans('common.edit_success'));
        } catch (\Exception $e) {
            return redirect()->to(shop_route('account.password.index'))
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }
}
