<?php
/**
 * WintopayController.php
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2024-05-13 18:57:56
 * @modified   2024-05-13 18:57:56
 *
 */

namespace Plugin\Wintopay\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Plugin\Wintopay\Services\WintopayService;

class WintopayController
{
    public function pay(Request $request, int $id)
    {
        return redirect(WintopayService::getInstance($id)->getPaymentUrl($request->get('payment_type'), $request->get('sid')));
    }

    public function notify(Request $request)
    {
        $result = WintopayService::notify();
    }

}
