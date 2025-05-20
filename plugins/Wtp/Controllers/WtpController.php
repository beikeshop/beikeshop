<?php
/**
 * WtpController.php
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2024-05-13 18:57:56
 * @modified   2024-05-13 18:57:56
 *
 */

namespace Plugin\Wtp\Controllers;

use Illuminate\Http\Request;
use Plugin\Wtp\Services\WtpService;

class WtpController
{
    public function pay(Request $request, int $id)
    {
        $paymentInformation = [];
        $paymentInfo = $request->get('payment_information');
        if ($paymentInfo) {
            $expiryYearMonth = explode('/', $paymentInfo['expiry_year_month']);
            $paymentInformation = json_encode([
                'card_number' => $paymentInfo['card_number'],
                'expiry_month' => $expiryYearMonth[0],
                'expiry_year' => '20' . $expiryYearMonth[1],
                'cvv' => $paymentInfo['cvv'],
                'holder_name' => $paymentInfo['holder_name'],
            ]);
        }

        try {
            $redirectUrl = WtpService::getInstance($id)->getPaymentUrl($request->get('payment_type'), $request->get('sid'), $paymentInformation);
            // 返回$redirectUrl有值，代表支付需要跳转到收银台，为空代表支付成功或支付待审核（等待回调确认），有异常代表支付失败
            if ($redirectUrl == 'success') {
                $data['message'] = trans('Wtp::common.paid_success');
            } elseif ($redirectUrl) {
                $data['redirect_url'] = $redirectUrl;
            } else {
                $data['message'] = trans('Wtp::common.pay_submit_success');
            }
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
        }

        return json_success(trans('common.success'), $data);
    }

    public function notify(Request $request)
    {
        $result = WtpService::notify();
    }

}
