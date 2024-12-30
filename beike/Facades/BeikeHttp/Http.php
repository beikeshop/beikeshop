<?php

namespace Beike\Facades\BeikeHttp;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;
use Exception;
use Throwable;

class Http extends PendingRequest
{
    private $timestamp;

    private $nonce;

    private $token;

    public function __construct(Factory $factory = null, $middleware = [])
    {
        parent::__construct($factory, $middleware);

        $this->token     = system_setting('base.developer_token');
        $this->timestamp = time();
        $this->nonce     = Str::random(40);

        $this->withOptions([
            'verify' => false,
        ]);
        $host      = request()->getHost();
        $signature = $this->createSignature(
            $host,
            $this->token,
            $this->timestamp,
            $this->nonce,
        );

        $this->withHeaders([
            'developer-token' => $this->token,
            'Timestamp'       => $this->timestamp,
            'Nonce'           => $this->nonce,
            'Signature'       => $signature,
            'Referer'         => $host,
        ]);

        $this->withToken($this->token);
    }

    /**
     * GET request to the given URL.
     *
     * @param string            $apiEndPoint
     * @param array|string|null $query
     * @param string            $format
     * @return array|mixed
     */
    public function sendGet(string $apiEndPoint, array|string $query = null, $format = 'json'): mixed
    {
        $url = $this->getUrl($apiEndPoint);

        $result = $this->get($url, $query)->{$format}();

        if (isset($result['status']) && $result['status'] === 'error') {
            throw new Exception($result['message']);
        }

        return $result;
    }

    /**
     * POST request to the given URL.
     *
     *
     * @param string $apiEndPoint
     * @param string $body
     * @param array  $data
     * @param string $format
     * @return array|mixed
     */
    public function sendPost(string $apiEndPoint, string $body = '', array $data = [], $format = 'json'): mixed
    {
        $url = $this->getUrl($apiEndPoint);

        if ($body) {
            $signature = $this->createSignature(
                request()->getHost(),
                $this->token,
                $this->timestamp,
                $this->nonce,
                $body
            );

            $this->replaceHeaders([
                'Signature' => $signature,
            ]);
            $this->withBody($body, 'application/json');
        }

        $result = $this->post($url, $data)->{$format}();
        if (isset($result['status']) && $result['status'] === 'error') {
            throw new Exception($result['message']);
        }

        return $result;
    }

    /**
     * get sign
     *
     * @param mixed ...$attributes
     * @return string
     */
    public function createSignature(mixed ...$attributes): string
    {
        sort($attributes, SORT_STRING);

        return sha1(implode($attributes));
    }

    /**
     *  get url
     *
     * @param $apiEndPoint
     * @return string
     */
    private function getUrl($apiEndPoint): string
    {
        try {
            $locale = admin_locale();
        }catch (Throwable $e){
            $locale = 'en';
        }

        $queryArr =  [
            'locale' => $locale,
        ];

        $filter = request()->all();
        if ($filter) {
            $queryArr = array_merge($queryArr, $filter);
        }

        $queryString = http_build_query($queryArr);

        return trim(config('beike.api_url'), '/') . '/api/' . trim($apiEndPoint, '/') . '?' . $queryString;
    }
}
