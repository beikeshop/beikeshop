<?php
/**
 * OrderController.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-06-06 10:47:27
 * @modified   2023-06-06 10:47:27
 */

namespace Beike\Shop\Http\Controllers;

use Beike\Repositories\OrderRepo;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Request $request, int $number)
    {
        $email = trim($request->get('email'));
        if (empty($email)) {
            return null;
        }

        $order = OrderRepo::getListBuilder(['number' => $number, 'email' => $email])->firstOrFail();
        $data = hook_filter('order.show.data', ['order' => $order, 'html_items' => []]);

        return $data;
    }
}