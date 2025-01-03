<?php
namespace Plugin\Wtp\Libraries;

class Wintopay
{
    protected $utils;

    protected $config;

    public function __construct()
    {
        $this->utils = new WtpUtils();
        $this->config = [
            //支付网关
            'gateway' => 'https://api.cartadicreditopay.com/v3/payments',
            //支持的支付方式接口
            'paymentMethodsUrl' => 'https://api.cartadicreditopay.com/v3/payment_method',
            //支持的币种接口
            'paymentCurrencyUrl' => 'https://api.cartadicreditopay.com/v3/support_currencies',
            //前端js链接
            'shieldUrl' => 'https://js.cartadicreditopay.com/js/shield/v3',

            //没有州的国家
            'countryNoState' => array('AF', 'AX', 'AT', 'BH', 'BB', 'BE', 'BZ', 'BA', 'BG', 'BI', 'HR', 'CW', 'CZ', 'DK', 'EE', 'ET', 'FI', 'FR', 'GF', 'DE', 'GR', 'GP', 'GG', 'HU', 'IS', 'IR', 'IM', 'IL', 'KW', 'LV', 'LB', 'LI', 'LU', 'MT', 'MQ', 'YT', 'NL', 'NZ', 'NO', 'PL', 'PT', 'PR', 'RE', 'RW', 'MF', 'RS', 'SG', 'SK', 'SI', 'KR', 'LK', 'SE', 'CH', 'AE', 'GB', 'VN',),

            //没有邮编的国家
            'countryNoZipcode' => array('BH', 'BD', 'CL', 'CO', 'GH', 'HK', 'IE', 'JM', 'NP', 'UG', 'VN', 'ZW', 'BO', 'MZ', 'NL', 'NG', 'NO', 'WS', 'ST', 'AO', 'BS', 'BH', 'BD', 'BZ', 'BO', 'CL', 'CO', 'CW', 'GH', 'GT', 'HK', 'IE', 'JM', 'MZ', 'NP', 'NG', 'KN', 'WS', 'UG', 'AE', 'VN', 'ZW',),

            //城市可为空的国家
            'countryNoCity' => array('SG'),

            //信用卡支持的支付方式列表
            'cardMaps' => array("visa"=>'Visa',
                "mastercard"=>'MasterCard',
                "ae"=>'American Express',
                "jcb"=>'JCB',
                "dc"=>'Discover',
                "DClub"=>'Diners Club',),
        ];
    }

    /**
     * 通过api请求wtp获取支持的支付方式
     * @param $merchantId
     * @param $apiKey
     * @return mixed
     * @throws Exception
     */
    public function getPaymentMethods($merchantId = '',$apiKey = '')
    {
        $headers = array(
            'X-API-KEY'=>$apiKey,
            'X-MERCHANT-ID'=>$merchantId,
        );
        $result = $this->utils->curlPost($this->config['paymentMethodsUrl'],'',$headers,$this->getUserAgent());
        $data = json_decode($result,true);
        $message = empty($data['message'])?'':$data['message'];
        if($message == 'success'){
            return $data['method_list'];
        }else{
            return $message;
        }
    }

    public function getPaymentCurrency($merchantId,$apiKey)
    {
        $headers = array(
            'X-API-KEY'=>$apiKey,
            'X-MERCHANT-ID'=>$merchantId,
        );
        return $this->utils->curlPost($this->config['paymentCurrencyUrl'],'',$headers,$this->getUserAgent());
    }

    public function getCardMaps($cardType)
    {
        $cards = array();
        $cardMaps = $this->config['cardMaps'];
        if(is_array($cardType)){
            foreach($cardType as $v){
                if(array_key_exists($v,$cardMaps)){
                    $cards[$v] = $cardMaps[$v];
                }
            }
        }
        return $cards;
    }

    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * 处理空邮编订单，如果国家没有邮编，则赋默认值 000000
     * @param $country
     * @param $countriesNoZipcode
     * @return string
     */
    public function initEmptyZipcode($country,$countriesNoZipcode)
    {
        if(is_array($countriesNoZipcode) && !empty($countriesNoZipcode)){
            if(in_array($country,$countriesNoZipcode)){
                return '000000';
            }
        }
        return '';
    }

    /**
     * 处理空省/州订单，如果国家没有省/州，则赋值city的值
     * @param $country
     * @param $countriesNoState
     * @param $city
     * @return mixed|string
     */
    public function initEmptyState($country,$countriesNoState,$city)
    {
        if(is_array($countriesNoState) && !empty($countriesNoState)){
            if(in_array($country,$countriesNoState)){
                return empty($city)?'default':$city;
            }
        }
        return '';
    }

    /**
     * 部分地区城市可以为空，付州默认值，无州则默认“default“
     * @param $country
     * @param $state
     * @param $countriesNoCity
     * @return mixed|string
     */
    public function initEmptyCity($country,$state,$countriesNoCity)
    {
        if(!empty($countriesNoCity) && is_array($countriesNoCity)){
            if(in_array($country,$countriesNoCity)){
                return empty($state)?'default':$state;
            }
        }
        return '';
    }

    /**
     * 支付请求
     * @param $data
     * @param $headers
     * @param $userAgent
     * @return mixed
     * @throws Exception
     */
    public function pay($gateway,$data,$headers,$userAgent)
    {
        $resultJson = $this->utils->curlPost($gateway,$data,$headers,$userAgent);
//        dd($gateway,$data,$headers,$userAgent, json_decode($resultJson));
        $result = json_decode($resultJson,true);
        return $this->formatPayResult($result);
    }

    /**
     * 统一处理错误和失败信息
     * @param $result
     * @return mixed
     */
    public function formatPayResult($result)
    {
        if(!empty($result['payment_result']) && is_array($result['payment_result'])){
            $result['wtp_format_fail_code'] = $result['payment_result']['fail_code'];
            $result['wtp_format_fail_message'] = $result['payment_result']['fail_message'];
        }
        if(!empty($result['error']) && is_array($result['error'])){
            $result['wtp_format_fail_code'] = $result['error']['code'];
            $result['wtp_format_fail_message'] = $result['error']['message'];
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * 格式化处理金额
     * @param $amount
     * @param $currency
     * @param $wtpCurrency
     * @return mixed|string
     */
    public function amountFormat($amount,$currency,$wtpCurrency)
    {
        $wtpCurrency = json_decode($wtpCurrency,true);
        if(is_array($wtpCurrency)){
            foreach ($wtpCurrency as $v){
                if($v['currency'] == $currency){
                    $amount_float = floatval($amount);
                    // 币种在需要处理的列表中，将金额转为整数
                    $amount = number_format($amount_float,$v['exponent'],'.','');
                }
            }
        }
        return $amount;
    }

    public function checkReturn($merchantId,$md5key,$data)
    {
        if(!empty($data) && is_array($data)){
            $message = '';
            $code = '';
            $payType = empty($data['pay_type'])?'':$data['pay_type'];
            $pay_method = empty($data['pay_method'])?'':$data['pay_method'];
            if($payType == '3ds'){
                $id         = empty($data['id'])?'':$data['id'];
                $resultCode  = empty($data['result_code'])?'':$data['result_code'];
                $cardNumber   = empty($data['card_no'])?'':$data['card_no'];
                $cardOrgn   = empty($data['card_orgn'])?'':$data['card_orgn'];
                $orderId   = empty($data['order_id'])?'':$data['order_id'];
                $amount   = empty($data['amount'])?'':$data['amount'];
                $currency   = empty($data['currency'])?'':$data['currency'];
                $signVerify   = empty($data['sign_verify'])?'':$data['sign_verify'];
                $resultMessage   = empty($data['result_msg'])?'':$data['result_msg'];
                $str = $merchantId.$md5key.$orderId.$amount.$currency.$this->getDomain().$resultCode;
                if($this->hashEncrypt($str) == $signVerify){
                    if($resultCode == '0000'){
                        $status = 'paid';
                    }else{
                        $status = 'failed';
                        $message = $resultMessage;
                        $code = $resultCode;
                    }
                }else{
                    $status = 'failed';
                    $message = 'Invalid parameter!';
                    $code = $resultCode;
                }
            }elseif(!empty($pay_method)){
                $id = empty($data['id'])?'':$data['id'];
                $order_id = empty($data['order_id'])?'':$data['order_id'];
                $result_code = empty($data['status'])?'':$data['status'];
                $currency = empty($data['currency'])?'':$data['currency'];
                $amount_value = empty($data['amount_value'])?'':$data['amount_value'];
                $request_id = empty($data['request_id'])?'':$data['request_id'];
                $version = empty($data['version'])?'':$data['version'];
                $pay_method = empty($data['pay_method'])?'':$data['pay_method'];
                $fail_code = empty($data['fail_code'])?'':$data['fail_code'];
                $fail_message = empty($data['fail_message'])?'':$data['fail_message'];
                $sign_verify = empty($data['sign_verify'])?'':$data['sign_verify'];
                $str = $id.$result_code.$amount_value.$md5key.$merchantId.$request_id;
                if($this->hashEncrypt($str) == $sign_verify){
                    $status = $result_code;
                    $message = $fail_message;
                    $code = $fail_code;
                }else {
                    $status = 'failed';
                    $message = 'Invalid parameter!';
                    $code = $result_code;
                }
            }else{
                $id = empty($data['id'])?'':$data['id'];
                $order_id = empty($data['order_id'])?'':$data['order_id'];
                $currency = empty($data['currency'])?'':$data['currency'];
                $payment_id = empty($data['payment_id'])?'':$data['payment_id'];
                $pay_type = empty($data['pay_type'])?'':$data['pay_type'];
                $result_code = empty($data['result_code'])?'':$data['result_code'];
                $result_msg = empty($data['result_msg'])?'':$data['result_msg'];
                $sign_verify = empty($data['sign_verify'])?'':$data['sign_verify'];
                $str = $merchantId.$md5key.$order_id.$currency.$this->getDomain().$result_code;
                if($this->hashEncrypt($str) == $sign_verify){
                    if($result_code == '0000'){
                        $status = 'paid';
                    }else{
                        $status = $result_code;
                        $message = $result_msg;
                    }
                }else{
                    $status = 'failed';
                    $message = 'Invalid parameter!';
                }
            }
            return array(
                'status'=>$status,
                'message'=>$message,
                'code'=>$code,
            );
        }
        return array(
            'status'=>'failed',
            'message'=>'Invalid request!',
        );
    }

    /**
     * 回调处理
     * @param $merchantId
     * @param $md5key
     * @param $data
     * @return string
     */
    public function checkCallback($merchantId,$md5key,$data)
    {
        $id = empty($data['id'])?'':$data['id'];
        $orderId = empty($data['order_id'])?'':$data['order_id'];
        $status = empty($data['status'])?'':$data['status'];
        $currency = empty($data['currency'])?'':$data['currency'];
        $amountValue = empty($data['amount_value'])?'':$data['amount_value'];
        $metadata = empty($data['metadata'])?'':$data['metadata'];
        $fail_code = empty($data['fail_code'])?'':$data['fail_code'];
        $fail_message = empty($data['fail_message'])?'':$data['fail_message'];
        $requestId = empty($data['request_id'])?'':$data['request_id'];
        $signVerify = empty($data['sign_verify'])?'':$data['sign_verify'];
        $str = $id.$status.$amountValue.$md5key.$merchantId.$requestId;
        if($id && $this->hashEncrypt($str) == $signVerify){
            return 'success';
        }elseif($requestId){
            return 'error';
        }else{
            return 'invalid';
        }
    }

    /**
     * @param $string
     * @return string
     */
    public function hashEncrypt($string)
    {
        return hash('sha256',$string);
    }



    /**
     * @return false|mixed|string
     */
    public function getIp()
    {
        return $this->utils->getIp();
    }


    /**
     * @return array
     */
    public function getWebsiteInfo()
    {
       return $this->utils->getServerInfo();
    }
}
