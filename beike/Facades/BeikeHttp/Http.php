<?php

namespace Beike\Facades\BeikeHttp;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
use Throwable;

class Http extends PendingRequest
{
    private int $timestamp;

    private string $nonce;

    private string $token;

    private string $host;

    /**
     * Parameters that should not be forwarded to remote API.
     */
    private const FILTERED_QUERY_PARAMS = [
        'token',
        'password',
        'secret',
        'key',
        'api_key',
        'apikey',
        'access_token',
        'throwException',
        'timeout',
    ];

    /**
     * Maximum allowed length for query parameter values.
     */
    private const MAX_QUERY_PARAM_LENGTH = 5000;

    public function __construct(Factory $factory = null, $middleware = [])
    {
        parent::__construct($factory, $middleware);

        $this->token     = system_setting('base.developer_token') ?? '';
        $this->timestamp = time();
        $this->nonce     = Str::random(40);
        $this->host      = get_safe_host();

        $this->withOptions([
            'verify'  => config('beike.http_verify_ssl', true),
            'timeout' => config('beike.http_timeout', 0),
        ]);

        $signature = $this->createSignature(
            $this->host,
            $this->token,
            $this->timestamp,
            $this->nonce,
        );

        $this->withHeaders([
            'developer-token' => $this->token,
            'Timestamp'       => $this->timestamp,
            'Nonce'           => $this->nonce,
            'Signature'       => $signature,
            'Referer'         => $this->host,
            'Source-Url'      => $this->getSourceUrl(),
            'Source-Route'    => $this->getSourceRoute(),
            'Locale'          => locale(),
            'Version'         => config('beike.version'),
            'Admin-Name'      => system_setting('base.admin_name'),
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
    public function sendGet(string $apiEndPoint, array|string $query = null, string $format = 'json'): mixed
    {
        // Support reading from request query for backward compatibility
        // Method parameters take priority
        $timeout = request()->query('timeout');
        $throwException = request()->query('throwException') ?? true;

        if ($timeout) {
            $this->withOptions([
                'timeout' => $timeout,
            ]);
        }

        $url = $this->getUrl($apiEndPoint);

        try {
            $response = $this->get($url, $query);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            if (!$throwException) {
                return [
                    'status'  => 'fail',
                    'message' => $e->getMessage(),
                ];
            }

            throw $e;
        }

        $result = $this->parseResult($response->{$format}());

        if (is_array($result) && isset($result['status']) && in_array($result['status'], ['error', 'fail'])) {
            if ($throwException) {
                throw new Exception($result['message']);
            }
            return $result;
        }

        return $result;
    }

    /**
     * POST request to the given URL.
     *
     * @param string $apiEndPoint
     * @param string $body
     * @param array  $data
     * @param string $format
     * @return array|mixed
     */
    public function sendPost(string $apiEndPoint, string $body = '', array $data = [], string $format = 'json'): mixed
    {
        $url = $this->getUrl($apiEndPoint);

        if ($body) {
            $signature = $this->createSignature(
                $this->host,
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

        $result = $this->parseResult($this->post($url, $data)->{$format}());

        if (is_array($result) && isset($result['status']) && in_array($result['status'], ['error', 'fail'])) {
            throw new Exception($result['message']);
        }

        return $result;
    }

    /**
     * Parse and decode the response result.
     *
     * @param mixed $result
     * @return mixed
     */
    private function parseResult(mixed $result): mixed
    {
        if (!is_array($result) && is_string($result)) {
            $decoded = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return $result;
    }

    /**
     * Create signature.
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
     * Get source URL safely.
     *
     * @return string
     */
    private function getSourceUrl(): string
    {
        try {
            return request()->fullUrl();
        } catch (Throwable $e) {
            return '';
        }
    }

    /**
     * Get source route safely.
     *
     * @return string
     */
    private function getSourceRoute(): string
    {
        try {
            return current_route() ?? '';
        } catch (Throwable $e) {
            return '';
        }
    }

    /**
     * Filter query parameters to remove sensitive and oversized values.
     *
     * @param array|null $params
     * @return array
     */
    private function filterQueryParams(?array $params): array
    {
        if (empty($params)) {
            return [];
        }

        // Filter out sensitive and control parameters
        $params = array_diff_key($params, array_flip(self::FILTERED_QUERY_PARAMS));

        // Filter out parameters with oversized values
        return array_filter($params, function ($value) {
            if (is_string($value)) {
                return strlen($value) <= self::MAX_QUERY_PARAM_LENGTH;
            }
            if (is_array($value)) {
                return strlen(json_encode($value)) <= self::MAX_QUERY_PARAM_LENGTH;
            }
            return true;
        });
    }

    /**
     * Get URL with query parameters.
     *
     * @param $apiEndPoint
     * @return string
     */
    private function getUrl($apiEndPoint): string
    {
        try {
            $locale = admin_locale();
        } catch (Throwable $e) {
            Log::warning('Failed to get admin locale: ' . $e->getMessage());
            $locale = 'en';
        }

        $queryArr = [
            'locale' => $locale,
        ];

        $filter = $this->filterQueryParams(request()->query());

        if ($filter) {
            $queryArr = array_merge($queryArr, $filter);
        }

        $queryString = http_build_query($queryArr);

        return trim(config('beike.api_url'), '/') . '/api/' . trim($apiEndPoint, '/') . '?' . $queryString;
    }
}
