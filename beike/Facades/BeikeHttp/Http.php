<?php

namespace Beike\Facades\BeikeHttp;

use Beike\Services\ApiTokenService;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class Http extends PendingRequest
{
    private ?ApiTokenService $tokenService = null;

    private const UNSIGNED_API_PATHS = [
        '/api/v1/website/get_token',
        '/api/v1/website/get_domain',
        '/api/v1/website/check_token',
        '/api/v1/token/bootstrap'
    ];

    private const FLEXIBLE_SIGNATURE_PATHS = [
        '/api/v1/plugins/*',
        '/api/v1/version',
        '/api/v1/tool/plugin_search',
        '/api/v1/plugins/ticket_expired'
    ];

    private const FILTERED_QUERY_PARAMS = [
        'password',
        'secret',
        'key',
        'api_key',
        'apikey',
        'access_token',
        'throwException',
        'timeout',
    ];

    private const MAX_QUERY_PARAM_LENGTH = 5000;

    private const ERROR_STATUSES = ['error', 'fail'];

    private const DEFAULT_RETRY_TIMES = 2;

    private const RETRY_DELAY_MS = 100;

    private static ?ApiTokenService $tokenServiceInstance = null;

    public function __construct(Factory $factory = null, $middleware = [])
    {
        parent::__construct($factory, $middleware);

        if (self::$tokenServiceInstance === null) {
            self::$tokenServiceInstance = new ApiTokenService();
        }
        $this->tokenService = self::$tokenServiceInstance;

        $this->withOptions([
            'verify'  => config('beike.http_verify_ssl', true),
            'timeout' => config('beike.http_timeout', 0),
        ]);
    }

    /**
     * GET request to the given URL.
     *
     * @param  string  $apiEndPoint
     * @param  array|string|null  $query
     * @param  string  $format
     * @return array|mixed
     */
    public function sendGet(string $apiEndPoint, array|string $query = null, string $format = 'json'): mixed
    {
        $startTime   = microtime(true);
        $queryParams = $this->parseQueryParams($query);
        $timeout     = $queryParams['timeout'] ?? request()->query('timeout');
        $throwException = $queryParams['throwException'] ?? request()->query('throwException') ?? true;

        [$url, $path, $queryString] = $this->buildRequestUrl($apiEndPoint, $queryParams);
        $timestamp   = time();
        $nonce       = Str::random(40);
        $host        = $this->getFormattedHost();
        $skipSignature = $this->shouldSkipSignature($path);

        if ($skipSignature) {
            $this->prepareRequest($timestamp, $nonce, $host, '', '', $timeout);
        } else {
            $bearerToken = $this->resolveBearerToken((bool) $throwException);

            try {
                $signature = $this->tokenService->getSignatureFromApi(
                    'GET',
                    $path,
                    $queryString,
                    '',
                    $timestamp,
                    $nonce,
                    $bearerToken
                );

                $this->prepareRequest($timestamp, $nonce, $host, $signature, $bearerToken, $timeout);
            } catch (Exception $e) {
                return $this->handleSignatureError($e, (bool) $throwException);
            }
        }
        try {
            $response = $this->get($url);
        } catch (ConnectionException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            Log::warning('API connection failed', [
                'domain'      => get_safe_host(),
                'url'         => $url,
                'duration_ms' => $duration,
                'error'       => $e->getMessage(),
            ]);

            if ($this->shouldRetry($e)) {
                return $this->retryWithBackoff(fn() => $this->get($url), $startTime, $url, $throwException, $format);
            }

            if (! $throwException) {
                return [
                    'status'  => 'fail',
                    'message' => $e->getMessage(),
                ];
            }

            throw $e;
        }

        $result   = $this->parseResult($response->{$format}());
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        $this->logApiDuration($url, $duration);

        return $this->handleResponseResult($result, (bool) $throwException);
    }

    private function handleResponseResult(mixed $result, bool $throwException): mixed
    {
        if ($result === null) {
            return $result;
        }

        if (is_array($result) && isset($result['status']) && in_array($result['status'], self::ERROR_STATUSES, true)) {
            if ($throwException) {
                throw new Exception($result['message'] ?? 'Unknown error');
            }

            return $result;
        }

        return $result;
    }

    private function logApiDuration(string $url, float $duration): void
    {
        if ($duration > 2000) {
            Log::warning('Slow API call detected', [
                'url'         => $url,
                'duration_ms' => $duration,
                'domain'      => get_safe_host(),
            ]);
        } else {
            Log::debug('API call completed', [
                'url'         => $url,
                'duration_ms' => $duration,
                'domain'      => get_safe_host(),
            ]);
        }
    }

    private function handleSignatureError(Exception $e, bool $throwException): mixed
    {
        Log::error('Failed to get signature for request', [
            'domain' => get_safe_host(),
            'error'  => $e->getMessage(),
        ]);

        $errorMsg = strtolower($e->getMessage());
        if (str_contains($errorMsg, 'expired') || str_contains($errorMsg, 'invalid') || str_contains($errorMsg, 'token')) {
            $this->tokenService->clearTokens();
        }

        if ($throwException) {
            throw $e;
        }

        return [
            'status'  => 'fail',
            'message' => 'Failed to generate request signature',
        ];
    }

    private function shouldRetry(ConnectionException $e): bool
    {
        $message = strtolower($e->getMessage());
        return str_contains($message, 'connection refused')
            || str_contains($message, 'timeout')
            || str_contains($message, 'empty reply')
            || str_contains($message, 'socket');
    }

    private function retryWithBackoff(callable $request, float $startTime, string $url, bool $throwException, string $format): mixed
    {
        $retry = 0;

        while ($retry <= self::DEFAULT_RETRY_TIMES) {
            $delay = self::RETRY_DELAY_MS * (2 ** $retry);
            usleep($delay * 1000);

            try {
                $response = $request();
                $result   = $this->parseResult($response->{$format}());
                $duration = round((microtime(true) - $startTime) * 1000, 2);
                $this->logApiDuration($url, $duration);

                return $this->handleResponseResult($result, $throwException);
            } catch (ConnectionException $e) {
                $retry++;
                Log::warning('API retry failed', [
                    'url'   => $url,
                    'retry' => $retry,
                    'error' => $e->getMessage(),
                ]);

                if ($retry > self::DEFAULT_RETRY_TIMES) {
                    if (! $throwException) {
                        return [
                            'status'  => 'fail',
                            'message' => $e->getMessage(),
                        ];
                    }
                    throw $e;
                }
            }
        }

        return [
            'status'  => 'fail',
            'message' => 'Max retries exceeded',
        ];
    }

    /**
     * POST request to the given URL.
     *
     * @param  string  $apiEndPoint
     * @param  string  $body
     * @param  array  $data
     * @param  string  $format
     * @return array|mixed
     */
    public function sendPost(string $apiEndPoint, string $body = '', array $data = [], string $format = 'json', bool $throwException = true): mixed
    {
        $startTime = microtime(true);

        [$url, $path, $queryString] = $this->buildRequestUrl($apiEndPoint);
        $requestBody = $body !== '' ? $body : $this->encodeRequestData($data);
        $timestamp   = time();
        $nonce       = Str::random(40);
        $host        = $this->getFormattedHost();
        $skipSignature = $this->shouldSkipSignature($path);

        if ($skipSignature) {
            $this->prepareRequest($timestamp, $nonce, $host, '', '');
            if ($requestBody !== '') {
                $this->withBody($requestBody, 'application/json');
            }
        } else {
            $bearerToken = $this->resolveBearerToken($throwException);

            try {
                $signature = $this->tokenService->getSignatureFromApi(
                    'POST',
                    $path,
                    $queryString,
                    $requestBody,
                    $timestamp,
                    $nonce,
                    $bearerToken
                );

                $this->prepareRequest($timestamp, $nonce, $host, $signature, $bearerToken);
                if ($requestBody !== '') {
                    $this->withBody($requestBody, 'application/json');
                }
            } catch (Exception $e) {
                return $this->handleSignatureError($e, $throwException);
            }
        }

        try {
            $response = $this->send('POST', $url);
        } catch (ConnectionException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            Log::warning('API connection failed', [
                'domain'      => get_safe_host(),
                'url'         => $url,
                'duration_ms' => $duration,
                'error'       => $e->getMessage(),
            ]);

            if ($this->shouldRetry($e)) {
                return $this->retryWithBackoff(fn() => $this->send('POST', $url), $startTime, $url, $throwException, $format);
            }

            if (! $throwException) {
                return [
                    'status'  => 'fail',
                    'message' => $e->getMessage(),
                ];
            }

            throw $e;
        }

        $result   = $this->parseResult($response->{$format}());
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        $this->logApiDuration($url, $duration);

        return $this->handleResponseResult($result, $throwException);
    }

    /**
     * Parse and decode the response result.
     *
     * @param  mixed  $result
     * @return mixed
     */
    private function parseResult(mixed $result): mixed
    {
        if (! is_array($result) && is_string($result)) {
            $decoded = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        return $result;
    }

    /**
     * Get source URL safely.
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
     * @param  array|null  $params
     * @return array
     */
    private function filterQueryParams(?array $params): array
    {
        if (empty($params)) {
            return [];
        }

        $params = array_diff_key($params, array_flip(self::FILTERED_QUERY_PARAMS));

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
     * Build the final request URL and return URL parts used for signing.
     *
     * @param  string  $apiEndPoint
     * @param  array|string|null  $query
     * @return array{0:string,1:string,2:string}
     */
    private function buildRequestUrl(string $apiEndPoint, array|string|null $query = null): array
    {
        try {
            $locale = admin_locale();
        } catch (Throwable $e) {
            Log::warning('Failed to get admin locale: ' . $e->getMessage());
            $locale = 'en';
        }

        $parsedEndpoint = parse_url($apiEndPoint);
        $apiPath = $parsedEndpoint['path'] ?? $apiEndPoint;
        $endpointQuery = [];

        if (! empty($parsedEndpoint['query'])) {
            parse_str($parsedEndpoint['query'], $endpointQuery);
        }

        $queryArr = [
            'locale' => $locale,
        ];

        $requestQuery = $this->filterQueryParams(request()->query());
        $endpointQuery = $this->filterQueryParams($endpointQuery);
        $explicitQuery = $this->filterQueryParams($this->parseQueryParams($query));

        if ($requestQuery) {
            $queryArr = array_merge($queryArr, $requestQuery);
        }

        if ($endpointQuery) {
            $queryArr = array_merge($queryArr, $endpointQuery);
        }

        if ($explicitQuery) {
            $queryArr = array_merge($queryArr, $explicitQuery);
        }

        $queryString = http_build_query($queryArr);
        $baseUrl = trim(config('beike.api_url'), '/') . '/api/' . trim($apiPath, '/');
        $url = $queryString ? $baseUrl . '?' . $queryString : $baseUrl;
        $parsedUrl = parse_url($url);

        return [
            $url,
            $parsedUrl['path'] ?? '',
            $parsedUrl['query'] ?? '',
        ];
    }

    /**
     * Get Token service instance.
     */
    public function getTokenService(): ApiTokenService
    {
        return $this->tokenService;
    }

    /**
     * Refresh API Token.
     *
     * @return array Token information
     * @throws Exception
     */
    public function refreshApiToken(): array
    {
        return $this->tokenService->refreshToken();
    }

    /**
     * Fetch a new API token.
     *
     * @param  string|null  $domain
     * @return array
     * @throws Exception
     */
    public function fetchApiToken(?string $domain = null): array
    {
        $domain = clean_domain(get_safe_host());

        return $this->tokenService->fetchToken($domain);
    }

    /**
     * Revoke the current API token.
     */
    public function revokeApiToken(): bool
    {
        return $this->tokenService->revokeToken();
    }

    /**
     * Check whether a valid token is available.
     */
    public function hasValidToken(): bool
    {
        return $this->tokenService->hasValidToken();
    }

    /**
     * Parse query params from explicit method arguments.
     */
    private function parseQueryParams(array|string|null $query): array
    {
        if (is_array($query)) {
            return $query;
        }

        if (is_string($query) && $query !== '') {
            parse_str(ltrim($query, '?'), $parsed);

            return is_array($parsed) ? $parsed : [];
        }

        return [];
    }

    /**
     * Reset mutable request state and inject fresh per-request headers.
     */
    private function prepareRequest(
        int $timestamp,
        string $nonce,
        string $host,
        string $signature = '',
        string $bearerToken = '',
        mixed $timeout = null
    ): void {
        $this->pendingBody = null;
        $this->pendingFiles = [];
        $this->bodyFormat = null;

        unset(
            $this->options['body'],
            $this->options['query'],
            $this->options['json'],
            $this->options['form_params'],
            $this->options['multipart']
        );

        $this->asJson();
        $this->options['headers'] = [];
        $this->withOptions([
            'verify'  => config('beike.http_verify_ssl', true),
            'timeout' => $timeout !== null && $timeout !== '' ? $timeout : config('beike.http_timeout', 0),
        ]);

        $headers = [
            'developer-token' => $this->getDeveloperTokenHeaderValue(),
            'Timestamp'       => $timestamp,
            'Nonce'           => $nonce,
            'Referer'         => $host,
            'Source-Url'      => $this->getSourceUrl(),
            'Source-Route'    => $this->getSourceRoute(),
            'Locale'          => locale(),
            'Version'         => config('beike.version'),
            'Admin-Name'      => system_setting('base.admin_name'),
        ];

        if ($signature !== '') {
            $headers['Signature'] = $signature;
        }

        if ($bearerToken !== '') {
            $headers['Authorization'] = 'Bearer ' . $bearerToken;
        }

        $this->replaceHeaders($headers);
    }

    /**
     * Build the formatted host used in outbound headers.
     */
    private function getFormattedHost(): string
    {
        $rawHost = get_safe_host() ?: 'localhost';

        return (str_starts_with($rawHost, 'http://') || str_starts_with($rawHost, 'https://'))
            ? rtrim($rawHost, '/')
            : 'https://' . rtrim($rawHost, '/');
    }

    /**
     * Encode structured request data into the exact JSON payload we send remotely.
     */
    private function encodeRequestData(array $data): string
    {
        if ($data === []) {
            return '';
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
    }

    private function shouldSkipSignature(string $path): bool
    {
        if (in_array($path, self::UNSIGNED_API_PATHS, true)) {
            return true;
        }

        if ($this->isFlexibleSignaturePath($path)) {
            return ! $this->hasDeveloperToken();
        }

        return false;
    }

    private function isFlexibleSignaturePath(string $path): bool
    {
        foreach (self::FLEXIBLE_SIGNATURE_PATHS as $configuredPath) {
            if (! is_string($configuredPath) || $configuredPath === '') {
                continue;
            }

            if (Str::is($configuredPath, $path)) {
                return true;
            }
        }

        return false;
    }

    private function hasDeveloperToken(): bool
    {
        return $this->getDeveloperTokenHeaderValue() !== '';
    }

    /**
     * Resolve bearer token and auto-bootstrap when missing.
     */
    private function resolveBearerToken(bool $throwException = true): string
    {
        $bearerToken = $this->tokenService->getAccessToken() ?? '';
        if ($bearerToken !== '') {
            return $bearerToken;
        }

        try {
            $domain = clean_domain(get_safe_host());
            $tokenData = $this->tokenService->fetchToken($domain);
            if (is_array($tokenData) && ! empty($tokenData['access_token'])) {
                return (string) $tokenData['access_token'];
            }
        } catch (Exception $e) {
            Log::warning('Failed to auto-bootstrap API token', [
                'domain' => get_safe_host(),
                'error'  => $e->getMessage(),
            ]);

            if ($throwException) {
                throw $e;
            }
        }

        return $this->tokenService->getAccessToken() ?? '';
    }

    /**
     * 统一清理开发者令牌，避免空白字符导致鉴权失败。
     */
    private function getDeveloperTokenHeaderValue(): string
    {
        $token = (string) (system_setting('base.developer_token') ?? '');
        $normalized = preg_replace('/\s+/u', '', trim($token));

        return is_string($normalized) ? $normalized : '';
    }
}
