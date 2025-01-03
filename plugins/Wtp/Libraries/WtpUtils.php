<?php
namespace Plugin\Wtp\Libraries;

class WtpUtils
{

    public function getServerInfo()
    {
        $phpVersion = PHP_VERSION;
        $serverType = PHP_OS;
        $serverVersion = $_SERVER['SERVER_SOFTWARE'];
        $serverIp = $_SERVER['SERVER_ADDR'];
        return array(
            'php_version'=>$phpVersion,
            'server_type'=>$serverType,
            'server_version'=>$serverVersion,
            'server_ip'=>$serverIp,
        );
    }

    /**
     * @param $url
     * @param $data
     * @param array $header
     * @param string $userAgent
     * @return bool|string
     * @throws \Exception
     */
    public function curlPost($url, $data, array $header = array(), $userAgent='', $timeout='10' )
    {
        $header['Content-Type'] = 'application/json';
        $header = $this->headerFormat($header);
        if(is_array($data)){
            $data = json_encode($data);
        }
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 超时设置
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT,$userAgent);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $data = curl_exec($curl);
        // 显示错误信息
        if (curl_error($curl)) {
            throw new \Exception("Error: " . curl_error($curl));
        }
        curl_close($curl);
        return $data;
    }

    /**
     * @param $url
     * @param $header
     * @return bool|string
     * @throws \Exception
     */
    public function curlGet($url, array $header = array())
    {
        $header = $this->headerFormat($header);
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
        // 超时设置，以毫秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);
        // 显示错误信息
        if (curl_error($curl)) {
            throw new \Exception("Error: " . curl_error($curl));
        }
        curl_close($curl);
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public function headerFormat(array $data)
    {
        $header = array();
        foreach ($data as $k=>$v){
            $header[] = $k.':'.$v;
        }
        return $header;
    }

    public function getIp()
    {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $online_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $online_ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(isset($_SERVER['HTTP_X_REAL_IP'])){
            $online_ip = $_SERVER['HTTP_X_REAL_IP'];
        }else{
            $online_ip = $_SERVER['REMOTE_ADDR'];
        }
        $ips = explode(",",$online_ip);
        $ip = $ips[0];
        if (substr($ip,0, 7) == "::ffff:") {
            $ip = substr($ip,7);
        }
        return $ip;
    }

}
