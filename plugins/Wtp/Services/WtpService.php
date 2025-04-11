<?php
/**
 * WtpService.php
 *
 * @copyright  2024 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     TL <mengwb@guangda.work>
 * @created    2024-05-13 16:09:21
 * @modified   2024-05-13 16:09:21
 */

namespace Plugin\Wtp\Services;

use Beike\Models\Country;
use Beike\Models\Order;
use Beike\Services\StateMachineService;
use Beike\Shop\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use Plugin\Wtp\Libraries\Wintopay;
use Plugin\Wtp\Libraries\Wtp;

class WtpService extends PaymentService
{
    private $url = '';
    private $apiKey = '';
    private $md5Key = '';
    private $merchantId = '';

    /**
     * @throws \Exception
     */
    public function __construct($order)
    {
        parent::__construct($order);
        $this->url = plugin_setting('wtp.api') == 'test' ? 'https://stg-gateway.wintopay.com/v3/payments' : 'https://api.cartadicreditopay.com/v3/payments';
        $this->apiKey = plugin_setting('wtp.api_key');
        $this->md5Key = plugin_setting('wtp.md5_key');
        $this->merchantId = plugin_setting('wtp.merchant_id');
    }

    /**
     * @throws \Exception
     */
    public static function getInstance($orderId)
    {
        $order = Order::query()->find($orderId);
        return (new self($order));
    }

    /**
     * 提交订单数据并跳转到绿界付款API
     */
    public function getPaymentUrl($paymentMethod, $sid, $paymentInformation = [])
    {
        $wtp = new Wintopay();
        $products = array();
        foreach ($this->order->orderProducts as $item) {
            $products[] = [
                'sku' => $item->product_sku,
                'name' => $item->name,
                'price' => currency_format($item->price, $this->order->currency_code, $this->order->currency_value, false),
                'quantity' => $item->quantity,
                'currency' => $this->order->currency_code,
            ];
        }
        $userAgent = $wtp->getUserAgent();

        $shippingName = $this->getFirstnameAndLastname($this->order->shipping_customer_name);
        $paymentName = $this->getFirstnameAndLastname($this->order->payment_customer_name);
        $requestData = array(
            'merchant_id'=> $this->merchantId,
            'ip'=> $wtp->getIp(),
            'payment_method' => $paymentMethod,
            'amount' => currency_format($this->order->total, $this->order->currency_code, $this->order->currency_value, false),
            'currency' => $this->order->currency_code,
            'merchant_reference' => $this->orderId . '-' . time(),
            'products' => $products,
            'customer_email' => $this->order->email,
            'return_url' => shop_route('plugin.wtp.notify'),
            'billing_details' => array(
                'email'=>$this->order->email,
                'phone'=>$this->order->payment_telephone,
                'first_name'=>$paymentName[0],
                'last_name'=> $paymentName[1],
                'address'=>$this->order->payment_address_1 . ' ' . $this->order->payment_address_2,
                'city'=>$this->order->payment_city,
                'state'=>$this->order->payment_zone,
                'postal_code'=>$this->order->payment_zipcode ?: '000000',
                'country'=>Country::query()->where('id', $this->order->payment_country_id)->value('code'),
            ),
            'shipping_details' => array(
                'email'=>$this->order->email,
                'phone'=>$this->order->shipping_telephone,
                'first_name'=>$shippingName[0],
                'last_name'=> $shippingName[1],
                'address'=>$this->order->shipping_address_1 . ' ' . $this->order->shipping_address_2,
                'city'=>$this->order->shipping_city,
                'state'=>$this->order->shipping_zone,
                'postal_code'=>$this->order->shipping_zipcode ?: '000000',
                'country'=>Country::query()->where('id', $this->order->shipping_country_id)->value('code'),
            ),
            'user_agent' => $userAgent,
            'sid' => $sid,
        );
        if ($paymentInformation) {
            $requestData['payment_information'] = $paymentInformation;
        }

        $headers = array(
            'X-API-KEY'=>$this->apiKey,
            'X-MERCHANT-ID'=>$this->merchantId,
            'X-SITE-DOMAIN'=>$wtp->getDomain(),
            'X-ADDON-PLATFORM'=>'beikeshop',
            'X-ADDON-VERSION'=>'4.0.0',
            'X-ADDON-TYPE'=>'web',
        );
        $payResult = $wtp->pay($this->url, $requestData, $headers, $userAgent);
        Log::info('Wintopay pay request Start =========');
        Log::info("url:  {$this->url}");
        Log::info('request_data: ' . var_export($requestData, true));
        Log::info('response: ' . var_export($payResult, true));

        if ($payResult['status'] == 'unpaid') {
            if ($payResult['redirect_url']) { // unpaid，且redirect_url有值代表为3D验证连接
                return $payResult['redirect_url'];
            } else { // unpaid，且redirect_url为空代表为异常交易，需要等待异步通知确定最终状态
                return '';
            }
        } elseif (!in_array($payResult['status'], ['pending', 'paid'])) { // failed或canceled状态
            throw (new \Exception('Payment Error: ' . $payResult['wtp_format_fail_message']));
        } elseif ($payResult['status'] == 'paid') {
            StateMachineService::getInstance($this->order)->changeStatus(StateMachineService::PAID, 'WTP-RETURN: Payment success.');
            return 'success';
        } elseif ($payResult['status'] == 'pending') {
            StateMachineService::getInstance($this->order)->changeStatus('paying');
        }

        return $payResult['redirect_url'];
    }

    private function getFirstnameAndLastname($name) {
        // 查找最后一个空格的位置
        $lastSpacePos = strrpos($name, ' ');
        if ($lastSpacePos !== false) {
            // 使用substr函数分割字符串
            $firstname = substr($name, 0, $lastSpacePos);
            $lastname = substr($name, $lastSpacePos + 1);
            return array($firstname, $lastname);
        } else {
            // 如果没有空格，返回原字符串
            return array($name, $name);
        }
    }

    /**
     * 回调通知
     */
    public static function notify()
    {
        Log::info('Wtp notify:');
        $wtp = new Wintopay();
        $md5key = plugin_setting('wtp.md5_key');
        $merchantId = plugin_setting('wtp.merchant_id');
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $data = $_REQUEST;
            $result = $wtp->checkReturn($merchantId,$md5key,$data);
            $orderId = empty($data['order_id'])?'':$data['order_id'];
            if(!$orderId){
                $redirect_url = $wtp->getDomain();
                header('Location: '.$redirect_url);
                exit;
            }
            if(strpos($orderId,'_')){
                $order_arr = explode('_',$orderId);
                $orderId = $order_arr[0];
            }
            $order = Order::find((int)$orderId);
            $preMessage = 'WTP-RETURN: ';
            if($result['status'] == 'paid'){
                //支付成功
                $redirect_url = shop_route('checkout.success', ['order_number' => $order->number]);
                if ($order->status == StateMachineService::UNPAID || $order->status == 'paying') {
                    StateMachineService::getInstance($order)->changeStatus(StateMachineService::PAID, $preMessage . 'Payment success.');
                }
            }elseif($result['status'] == 'pending' || $result['status'] == 'unpaid'){
                $redirect_url = shop_route('checkout.success', ['order_number' => $order->number]);
                if ($order->status == StateMachineService::UNPAID) {
                    StateMachineService::getInstance($order)->changeStatus('paying');
                }
            }else{ // $result['status'] = 'failed'
                $redirect_url = shop_route('account.order.show', ['number' => $order->number]);
            }
            header('Location: '.$redirect_url);
            exit;
        }else {
            $requestData = file_get_contents('php://input', 'r');
            Log::info('Wtp notify params: ' . $requestData);
            $data = json_decode($requestData, true);
            $result = $wtp->checkCallback($merchantId, $md5key, $data);
            $reqId = empty($data['request_id']) ? '' : $data['request_id'];
            $message = empty($data['fail_message']) ? '' : $data['fail_message'];
            $preMessage = 'WTP-CALLBACK: reqId:' . $reqId . ',';
            if ($merchantId && $md5key && !empty($data)) {
                $orderId = empty($data['order_id']) ? '' : $data['order_id'];
                if (!$orderId) {
                    exit('[INVALID-ORDER]');
                }
                if (strpos($orderId, '_')) {
                    $order_arr = explode('_', $orderId);
                    $orderId = $order_arr[0];
                }
                if ($result == 'success') {
                    $status = $data['status'];
                    $order = Order::find($orderId);
                    if ($status == 'paid') {
                        if ($order->status == StateMachineService::UNPAID || $order->status == 'paying') {
                            StateMachineService::getInstance($order)->changeStatus(StateMachineService::PAID, $preMessage . 'Payment failed.');
                        }
                    } elseif ($status == 'failed') {
                        if ($order->status == StateMachineService::UNPAID || $order->status == 'paying') {
                            StateMachineService::getInstance($order)->changeStatus(StateMachineService::CANCELLED, $preMessage . 'Payment success.');
                        }
                    }
                    exit('[success]');
                } elseif ($result == 'error') {
                    exit('[INVALID-PARAMETERS]');
                } else {
                    exit('[INVALID-REQUEST]');
                }
            } else {
                exit('[INVALID-REQUEST]');
            }
        }
    }

}
