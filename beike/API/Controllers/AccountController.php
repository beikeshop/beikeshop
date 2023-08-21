<?php
/**
 * AccountController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-08-15 11:02:22
 * @modified   2023-08-15 11:02:22
 */

namespace Beike\API\Controllers;

use App\Http\Controllers\Controller;
use Beike\Repositories\CustomerRepo;
use Beike\Shop\Http\Requests\EditRequest;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    /**
     * @param EditRequest $request
     * @return JsonResponse
     */
    public function update(EditRequest $request): JsonResponse
    {
        try {
            CustomerRepo::update(current_customer(), $request->only('name', 'email', 'avatar'));

            return json_success(trans('common.edit_success'));
        } catch (\Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
