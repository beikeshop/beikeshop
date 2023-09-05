<?php
/**
 * Youdao.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-09-04 15:31:23
 * @modified   2023-09-04 15:31:23
 */

namespace Plugin\Youdao\Libraries;

class Youdao
{
    // 文本翻译
    public const API_URL = 'https://openapi.youdao.com/api';

    // 富文本翻译
    public const API_HTML_URL = 'https://openapi.youdao.com/translate_html';

    public const CURL_TIMEOUT = 2000;

    /**
     * @var string 应用ID
     */
    private string $appKey = '';

    /**
     * @var string 应用密钥
     */
    private string $secKey = '';

    private string $baseUrl = '';

    private bool $isHtml;

    /**
     * @param string $appKey
     * @param string $secKey
     * @param bool   $html
     */
    public function __construct(string $appKey = '', string $secKey = '', bool $html = false)
    {
        $this->appKey = $appKey;
        $this->secKey = $secKey;
        if ($html) {
            $this->baseUrl = self::API_HTML_URL;
        } else {
            $this->baseUrl = self::API_URL;
        }
        $this->isHtml = $html;
    }

    /**
     * @param $text
     * @param $from
     * @param $to
     * @return string
     * @throws \Exception
     */
    public function translate($text, $from, $to): string
    {
        if (empty($text) || ! is_string($text)) {
            return '';
        }
        $response = $this->translateSingle($text, $from, $to);
        if ($this->isHtml) {
            $result = $response['data']['translation'] ?? '';
            $result = html_entity_decode($result);
        } else {
            $result = $response['translation'][0] ?? '';
        }

        $errorCode = $response['errorCode'] ?? '';
        if ($errorCode) {
            $errorMessage = "Error code: {$errorCode}, 请查看: https://ai.youdao.com/DOCSIRMA/html/trans/api/wbfy/index.html#section-14";

            throw new \Exception($errorMessage);
        }

        return $result ?: $text;
    }

    /**
     * @param $data
     * @param $from
     * @param $to
     * @return array
     */
    public function translateBatch($data, $from, $to): array
    {
        if (! $data || ! is_array($data)) {
            return [];
        }
        $result = [];
        foreach ($data as $field => $text) {
            if (empty($text)) {
                $result[$field] = '';

                continue;
            }
            $item           = $this->translate($text, $from, $to);
            $result[$field] = $item;
        }

        return $result;
    }

    /**
     * @param $text
     * @param $from
     * @param $to
     * @return mixed
     */
    public function translateSingle($text, $from, $to): mixed
    {
        $salt = $this->createGuid();
        $args = [
            'q'      => $text,
            'appKey' => $this->appKey,
            'salt'   => $salt,
        ];
        $args['from']     = $from;
        $args['to']       = $to;
        $args['signType'] = 'v3';
        $currentTime      = strtotime('now');
        $args['curtime']  = $currentTime;
        $signStr          = $this->appKey . $this->truncate($text) . $salt . $currentTime . $this->secKey;
        $args['sign']     = hash('sha256', $signStr);
        $args['vocabId']  = '您的用户词表ID';
        $ret              = $this->call($this->baseUrl, $args);

        return json_decode($ret, true);
    }

    /**
     * uuid generator
     *
     * @return string
     */
    private function createGuid(): string
    {
        $microTime       = microtime();
        [$a_dec, $a_sec] = explode(' ', $microTime);
        $dec_hex         = dechex($a_dec * 1000000);
        $sec_hex         = dechex($a_sec);
        $this->ensureLength($dec_hex, 5);
        $this->ensureLength($sec_hex, 6);
        $guid = $dec_hex;
        $guid .= $this->createGuidSection(3);
        $guid .= '-';
        $guid .= $this->createGuidSection(4);
        $guid .= '-';
        $guid .= $this->createGuidSection(4);
        $guid .= '-';
        $guid .= $this->createGuidSection(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->createGuidSection(6);

        return $guid;
    }

    /**
     * @param $string
     * @param $length
     * @return void
     */
    private function ensureLength(&$string, $length): void
    {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, '0');
        } elseif ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }

    /**
     * @param $characters
     * @return string
     */
    private function createGuidSection($characters): string
    {
        $return = '';
        for ($i = 0; $i < $characters; $i++) {
            $return .= dechex(mt_rand(0, 15));
        }

        return $return;
    }

    /**
     * @param $q
     * @return string
     */
    private function truncate($q): string
    {
        $len = $this->absLength($q);

        return $len <= 20 ? $q : (mb_substr($q, 0, 10) . $len . mb_substr($q, $len - 10, $len));
    }

    /**
     * @param $str
     * @return int
     */
    private function absLength($str): int
    {
        if (empty($str)) {
            return 0;
        }
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, 'utf-8');
        }
            preg_match_all('/./u', $str, $ar);

            return count($ar[0]);

    }

    /**
     * 发起网络请求
     *
     * @param $url
     * @param $args
     * @param string $method
     * @param int    $timeout
     * @param array  $headers
     * @return bool|mixed|string
     */
    private function call($url, $args = null, string $method = 'post', int $timeout = self::CURL_TIMEOUT, array $headers = []): mixed
    {
        $ret = false;
        $i   = 0;
        while ($ret === false) {
            if ($i > 1)
                break;
            if ($i > 0) {
                sleep(1);
            }
            $ret = $this->callOnce($url, $args, $method, false, $timeout, $headers);
            $i++;
        }

        return $ret;
    }

    /**
     * @param $url
     * @param $args
     * @param $method
     * @param $withCookie
     * @param $timeout
     * @param $headers
     * @return bool|string
     */
    private function callOnce($url, $args = null, $method = 'post', $withCookie = false, $timeout = self::CURL_TIMEOUT, $headers = []): bool|string
    {
        $ch = curl_init();
        if ($method == 'post') {
            $data = $this->convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            $data = $this->convert($args);
            if ($data) {
                if (stripos($url, '?') > 0) {
                    $url .= "&$data";
                } else {
                    $url .= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (! empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($withCookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);

        return $r;
    }

    /**
     * @param $args
     * @return mixed|string
     */
    private function convert(&$args): mixed
    {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $data .= $key . '[' . $k . ']=' . rawurlencode($v) . '&';
                    }
                } else {
                    $data .= "$key=" . rawurlencode($val) . '&';
                }
            }

            return trim($data, '&');
        }

        return $args;
    }
}
