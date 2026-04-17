<?php

namespace Beike\Services;

use Beike\Facades\BeikeHttp\Http;
use Beike\Repositories\SettingRepo;
use Beike\Libraries\ToolCache as Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * API Token 管理服务
 *
 * 负责与 api.beikeshop.com 交互，管理 access_token 和 refresh_token
 */
class ApiTokenService
{
    /**
     * Token 缓存键前缀
     */
    private const CACHE_PREFIX = 'beike_api_token:';

    /**
     * Token 提前刷新时间（秒）- 在过期前 5 分钟刷新
     */
    private const REFRESH_BUFFER = 300;

    /**
     * API 路径常量
     */
    private const API_PATH_TOKEN_BOOTSTRAP = '/api/v1/token/bootstrap';
    private const API_PATH_REFRESH_TOKEN = '/api/v1/token/refresh';
    private const API_PATH_GENERATE_SIGNATURE = '/api/v1/signature/generate';
    private const API_PATH_SIGNATURE_SECRET = '/api/v1/signature/secret';

    /**
     * API 基础 URL
     */
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('beike.api_url', 'https://api.beikeshop.com'), '/');
    }

    /**
     * 获取当前有效的 Access Token（自动刷新）
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        $cacheKey = $this->getCacheKey('access_token');
        $token = Cache::get($cacheKey);

        if ($token) {
            // 检查是否即将过期（剩余时间少于 5 分钟）
            $expiresAt = Cache::get($this->getCacheKey('expires_at'));
            if ($expiresAt && $expiresAt - time() < self::REFRESH_BUFFER) {
                // Token 即将过期，尝试自动刷新
                if ($this->hasRefreshToken()) {
                    try {
                        $this->refreshToken();
                        $token = Cache::get($cacheKey);
                        Log::info('Bearer token auto-refreshed', [
                            'domain' => get_safe_host(),
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Failed to auto-refresh token, clearing expired tokens', [
                            'domain' => get_safe_host(),
                            'error' => $e->getMessage(),
                        ]);
                        // 清除过期 Token，下次请求会重新获取
                        $this->clearTokens();
                        return null;
                    }
                }
            }
        }

        return $token;
    }

    /**
     * 从 API 获取新的 Token（bootstrap 首次接入链路）
     *
     * @param string $domain 当前网站域名
     * @return array Token 信息
     * @throws \Exception
     */
    public function fetchToken(string $domain): array
    {
        try {
            $domain = clean_domain(get_safe_host());
            $developerToken = $this->normalizeDeveloperToken((string) (system_setting('base.developer_token') ?? ''));
            $tokenData = null;
            $result = [];

            try {
                $result = $this->requestBootstrapToken($domain, $developerToken);
                $tokenData = $this->normalizeTokenPayload($result);

                if ($tokenData === null && $this->shouldFallbackToLegacyWebsiteToken(new \Exception((string) ($result['message'] ?? '')))) {
                    $legacyToken = $this->requestLegacyWebsiteToken($domain);
                    if ($legacyToken !== null && $legacyToken !== '') {
                        $tokenData = [
                            'access_token' => $legacyToken,
                            'refresh_token' => null,
                            'expires_in' => 7200,
                        ];

                        Log::warning('Bootstrap token endpoint returned developer token error payload, fell back to legacy website token', [
                            'domain' => $domain,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                if (! $this->shouldFallbackToLegacyWebsiteToken($e)) {
                    throw $e;
                }

                $legacyToken = $this->requestLegacyWebsiteToken($domain);
                if ($legacyToken !== null && $legacyToken !== '') {
                    $tokenData = [
                        'access_token' => $legacyToken,
                        'refresh_token' => null,
                        'expires_in' => 7200,
                    ];

                    Log::warning('Bootstrap token endpoint rejected developer token, fell back to legacy website token', [
                        'domain' => $domain,
                    ]);
                }
            }

            if ($tokenData !== null) {
                // 从响应中获取过期时间，如果没有则使用默认值
                $expiresIn = $tokenData['expires_in'] ?? 7200;

                $this->storeToken(
                    $tokenData['access_token'],
                    $tokenData['refresh_token'] ?? null,
                    $expiresIn
                );

                Log::info('API token fetched successfully', [
                    'domain' => get_safe_host(),
                    'expires_in' => $expiresIn,
                    'has_refresh_token' => isset($tokenData['refresh_token']) ? 'yes' : 'no',
                ]);

                return $tokenData;
            }

            throw new \Exception('Failed to fetch token from API: ' . ($result['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Failed to fetch token: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 兼容 token 接口返回结构：
     * 1) 新结构：data.access_token / data.refresh_token / data.expires_in
     * 2) 旧结构：data 为 access_token 字符串
     */
    private function normalizeTokenPayload(array $result): ?array
    {
        $data = $result['data'] ?? null;

        if (is_array($data) && !empty($data['access_token'])) {
            return [
                'access_token' => (string) $data['access_token'],
                'refresh_token' => isset($data['refresh_token']) ? (string) $data['refresh_token'] : null,
                'expires_in' => (int) ($data['expires_in'] ?? 7200),
            ];
        }

        if (is_string($data) && $data !== '') {
            return [
                'access_token' => $data,
                'refresh_token' => null,
                'expires_in' => 7200,
            ];
        }

        return null;
    }

    /**
     * 请求新的 bootstrap token 对。
     */
    protected function requestBootstrapToken(string $domain, string $developerToken): array
    {
        $timestamp = time();
        $nonce = \Illuminate\Support\Str::random(40);
        $host = get_safe_host();

        $http = new \Illuminate\Http\Client\Factory();
        $client = $http->withOptions([
            'verify' => config('beike.http_verify_ssl', true),
            'timeout' => config('beike.http_timeout', 30),
        ]);

        $client->withHeaders([
            'developer-token' => $developerToken,
            'Timestamp' => $timestamp,
            'Nonce' => $nonce,
            'Referer' => $host,
            'Content-Type' => 'application/json',
        ]);

        $url = $this->apiUrl . self::API_PATH_TOKEN_BOOTSTRAP;
        $response = $client->post($url, ['domain' => $domain]);

        return is_array($response->json()) ? $response->json() : [];
    }

    /**
     * 兼容旧链路：直接按域名取网站 token。
     */
    protected function requestLegacyWebsiteToken(string $domain): ?string
    {
        $result = $this->sendUnsignedApiGet('/v1/website/get_token', ['domain' => $domain], false);
        $normalized = $this->normalizeTokenPayload(is_array($result) ? $result : []);

        return $normalized['access_token'] ?? null;
    }

    /**
     * 通过统一无签名链路调用公开 GET 接口，自动补齐基础安全头。
     */
    protected function sendUnsignedApiGet(string $apiEndPoint, array $query = [], bool $throwException = true): mixed
    {
        if (! $throwException) {
            $query['throwException'] = false;
        }

        return (new Http())->sendGet($apiEndPoint, $query, 'json');
    }

    /**
     * bootstrap 失败时，仅在 developer-token 语义不匹配时回退旧链路。
     */
    protected function shouldFallbackToLegacyWebsiteToken(\Exception $e): bool
    {
        $message = strtolower($e->getMessage());

        return str_contains($message, 'invalid developer-token')
            || str_contains($message, 'missing developer-token')
            || str_contains($message, 'developer token not configured')
            || str_contains($message, 'unsupported user-agent')
            || str_contains($message, 'unsupported user agent');
    }

    /**
     * 刷新 Access Token（通过服务端签名，带并发锁和重试机制）
     *
     * @return array 新的 Token 信息
     * @throws \Exception
     */
    public function refreshToken(): array
    {
        $lockKey = $this->getCacheKey('refresh_lock');
        $acquiredLock = false;

        // 尝试获取锁，避免并发刷新
        if (!Cache::add($lockKey, 1, 10)) {
            // 其他进程正在刷新，等待并返回当前 token
            sleep(1);
            $token = $this->getAccessToken();
            if ($token) {
                return ['access_token' => $token];
            }
            throw new \Exception('Token refresh in progress, please retry');
        }

        $acquiredLock = true;

        $maxRetries = 2;
        $retry = 0;
        $domain = get_safe_host();

        try {
            while ($retry <= $maxRetries) {
                try {
                    $refreshToken = Cache::get($this->getCacheKey('refresh_token'));

                    if (!$refreshToken) {
                        throw new \Exception('No refresh token available');
                    }

                    // 准备请求参数
                    $timestamp = time();
                    $nonce = \Illuminate\Support\Str::random(40);
                    $method = 'POST';
                    $path = self::API_PATH_REFRESH_TOKEN;
                    $body = json_encode(['refresh_token' => $refreshToken]);

                    // 优先使用本地签名，失败时回退到服务端签名
                    $signatureSecret = system_setting('base.signature_secret');

                    if ($signatureSecret) {
                        // 使用本地签名（性能更好，避免循环依赖）
                        $signature = $this->createLocalSignature(
                            $signatureSecret,
                            $method,
                            $path,
                            '',
                            $body,
                            $timestamp,
                            $nonce,
                            ''
                        );
                    } else {
                        // 回退到远程签名
                        Log::warning('Signature secret not configured, using server-side signature for refresh', [
                            'domain' => $domain,
                        ]);
                        $signature = $this->getSignatureFromServer($method, $path, '', $body, $timestamp, $nonce, '');
                    }

                    // 创建 HTTP 客户端
                    $http = new \Illuminate\Http\Client\Factory();
                    $client = $http->withOptions([
                        'verify' => config('beike.http_verify_ssl', true),
                        'timeout' => config('beike.http_timeout', 30),
                    ]);

                    $developerToken = $this->normalizeDeveloperToken((string) (system_setting('base.developer_token') ?? ''));

                    $client->withHeaders([
                        'developer-token' => $developerToken,
                        'Timestamp' => $timestamp,
                        'Nonce' => $nonce,
                        'Signature' => $signature,
                        'Referer' => get_safe_host(),
                        'Content-Type' => 'application/json',
                    ]);

                    $url = $this->apiUrl . $path;
                    $response = $client->withBody($body, 'application/json')->post($url);
                    $result = $response->json();

                    if (isset($result['data'])) {
                        $tokenData = $result['data'];
                        $this->storeToken(
                            $tokenData['access_token'],
                            $tokenData['refresh_token'] ?? null,
                            $tokenData['expires_in'] ?? 7200
                        );

                        Log::info('API token refreshed successfully', [
                            'domain' => $domain,
                            'expires_in' => $tokenData['expires_in'] ?? 7200,
                            'has_refresh_token' => isset($tokenData['refresh_token']) ? 'yes' : 'no',
                            'retry' => $retry,
                        ]);

                        // 刷新成功，清除失败计数
                        Cache::forget($this->getCacheKey('refresh_fail_count'));

                        return $tokenData;
                    }

                    throw new \Exception('Failed to refresh token');

                } catch (\Exception $e) {
                    $retry++;

                    if ($retry > $maxRetries) {
                        // 记录失败次数
                        $failKey = $this->getCacheKey('refresh_fail_count');
                        $failCount = Cache::get($failKey, 0) + 1;
                        Cache::put($failKey, $failCount, 3600); // 1 小时内累计

                        Log::error('Failed to refresh token after retries', [
                            'domain' => $domain,
                            'error' => $e->getMessage(),
                            'retries' => $maxRetries,
                            'fail_count' => $failCount,
                        ]);

                        // 如果 1 小时内失败超过 3 次，记录告警
                        if ($failCount >= 3) {
                            Log::alert('Token refresh failed multiple times', [
                                'domain' => $domain,
                                'fail_count' => $failCount,
                                'last_error' => $e->getMessage(),
                            ]);
                        }

                        // 清除无效的 refresh token
                        $this->clearTokens();
                        throw $e;
                    }

                    // 等待 100ms 后重试
                    usleep(100000);
                }
            }
        } finally {
            // 释放锁
            if ($acquiredLock) {
                Cache::forget($lockKey);
            }
        }

        // 理论上不会到这里
        throw new \Exception('Token refresh failed unexpectedly');
    }

    /**
     * 从 API 服务端获取签名（公开方法，供 Http 类调用）
     *
     * @param string $method HTTP 方法
     * @param string $path 请求路径
     * @param string $query 查询字符串
     * @param string $content 请求体内容
     * @param int $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $token Bearer Token（可选）
     * @return string 签名
     * @throws \Exception
     */
    public function getSignatureFromApi(
        string $method,
        string $path,
        string $query,
        string $content,
        int $timestamp,
        string $nonce,
        string $token = ''
    ): string {
        $query = $this->normalizeQueryForSignature($query);

        // 优先使用本地签名（性能更好）
        $signatureSecret = system_setting('base.signature_secret');

        if ($signatureSecret) {
            return $this->createLocalSignature(
                $signatureSecret,
                $method,
                $path,
                $query,
                $content,
                $timestamp,
                $nonce,
                $token
            );
        }

        // 如果没有配置本地密钥，回退到服务端签名（兼容旧版本）
        Log::warning('Signature secret not configured, falling back to server-side signature generation');
        return $this->getSignatureFromServer($method, $path, $query, $content, $timestamp, $nonce, $token);
    }

    /**
     * 对 query 进行标准化，确保与 API 端 Symfony Request::getQueryString() 结果一致。
     */
    private function normalizeQueryForSignature(string $query): string
    {
        return $query === '' ? '' : SymfonyRequest::normalizeQueryString($query);
    }

    /**
     * 创建本地签名（使用 HMAC-SHA256）
     *
     * 算法与 API 端 Signature::createSignature() 保持一致：
     * 1. 参数顺序：method|referer|path|query|token|timestamp|nonce|content
     * 2. 使用 '|' 分隔符拼接
     * 3. 使用 HMAC-SHA256 生成签名
     *
     * @param string $secret 签名密钥
     * @param string $method HTTP 方法
     * @param string $path 请求路径
     * @param string $query 查询字符串
     * @param string $content 请求体内容
     * @param int $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $token Bearer Token
     * @return string 64 位十六进制签名
     */
    private function createLocalSignature(
        string $secret,
        string $method,
        string $path,
        string $query,
        string $content,
        int $timestamp,
        string $nonce,
        string $token
    ): string {
        $host = clean_domain(get_safe_host());

        // 参数顺序必须与 API 端 Signature::createSignature() 一致
        $params = [
            strtoupper($method),  // method
            $host,                // referer
            $path,                // path
            $query,               // query
            $token,               // token
            (string) $timestamp,  // timestamp
            $nonce,               // nonce
            $content,             // content
        ];

        // 规范化参数（与 API 端逻辑一致）
        $normalized = array_map(function ($param) {
            return $param === null ? '' : (string) $param;
        }, $params);

        // 使用 '|' 拼接（不排序，保持固定顺序）
        $data = implode('|', $normalized);

        // 使用 HMAC-SHA256 生成签名
        return hash_hmac('sha256', $data, $secret);
    }

    /**
     * 从 API 服务端获取签名
     *
     * @param string $method HTTP 方法
     * @param string $path 请求路径
     * @param string $query 查询字符串
     * @param string $content 请求体内容
     * @param int $timestamp 时间戳
     * @param string $nonce 随机字符串
     * @param string $token Bearer Token（可选，获取 token 时为空）
     * @return string 签名
     * @throws \Exception
     */
    private function getSignatureFromServer(
        string $method,
        string $path,
        string $query,
        string $content,
        int $timestamp,
        string $nonce,
        string $token = ''
    ): string {
        try {
            // 直接使用 Laravel HTTP Client，避免使用 Http 类（防止循环依赖）
            $http = new \Illuminate\Http\Client\Factory();
            $client = $http->withOptions([
                'verify' => config('beike.http_verify_ssl', true),
                'timeout' => config('beike.http_timeout', 30),
            ]);

            $developerToken = $this->normalizeDeveloperToken((string) (system_setting('base.developer_token') ?? ''));
            $host = get_safe_host();

            // 签名接口不需要 Signature 头（signature_generate_factors 中不包含 Signature 验证）
            $client->withHeaders([
                'developer-token' => $developerToken,
                'Timestamp' => $timestamp,
                'Nonce' => $nonce,
                'Referer' => $host,
                'Content-Type' => 'application/json',
            ]);

            // 如果有 Bearer Token，添加到请求头
            if ($token) {
                $client->withToken($token);
            }

            $url = $this->apiUrl . self::API_PATH_GENERATE_SIGNATURE;
            $response = $client->post($url, [
                'method' => $method,
                'path' => $path,
                'query' => $query,
                'content' => $content,
                'timestamp' => $timestamp,
                'nonce' => $nonce,
                'token' => $token, // 获取 token 时为空字符串
            ]);

            $result = $response->json();

            if (isset($result['data']['signature'])) {
                return $result['data']['signature'];
            }

            throw new \Exception('Failed to get signature from server: ' . ($result['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Failed to get signature from server', [
                'domain' => get_safe_host(),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * 获取签名密钥（首次配置或定期更新）
     *
     * @return string 签名密钥
     * @throws \Exception
     */
    public function fetchSignatureSecret(?string $developerToken = null): string
    {
        try {
            // 准备请求参数
            $timestamp = time();
            $nonce = \Illuminate\Support\Str::random(40);
            $host = get_safe_host();

            // 创建 HTTP 客户端
            $http = new \Illuminate\Http\Client\Factory();
            $client = $http->withOptions([
                'verify' => config('beike.http_verify_ssl', true),
                'timeout' => config('beike.http_timeout', 30),
            ]);

            $developerToken = $this->normalizeDeveloperToken((string) ($developerToken ?? (system_setting('base.developer_token') ?? '')));

            // 获取签名密钥不需要签名（在 ignore_uri 中）
            $client->withHeaders([
                'developer-token' => $developerToken,
                'Timestamp' => $timestamp,
                'Nonce' => $nonce,
                'Referer' => $host,
            ]);

            $url = $this->apiUrl . self::API_PATH_SIGNATURE_SECRET;
            $response = $client->get($url);
            $result = $response->json();

            if (isset($result['data']['secret'])) {
                $secret = $result['data']['secret'];

                SettingRepo::storeValue('signature_secret', $secret);

                Log::info('Signature secret fetched and saved successfully');

                return $secret;
            }

            throw new \Exception('Failed to fetch signature secret from API');
        } catch (\Exception $e) {
            Log::error('Failed to fetch signature secret: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * 撤销当前 Token
     *
     * @return bool
     */
    public function revokeToken(): bool
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return false;
        }

        try {
            $http = new Http();
            $http->withToken($accessToken);
            $http->sendPost('token/revoke');

            $this->clearTokens();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to revoke token: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 存储 Token 到缓存
     *
     * @param string $accessToken
     * @param string|null $refreshToken
     * @param int $expiresIn 过期时间（秒）
     */
    private function storeToken(string $accessToken, ?string $refreshToken, int $expiresIn): void
    {
        $expiresAt = time() + $expiresIn;

        Cache::put($this->getCacheKey('access_token'), $accessToken, $expiresIn);
        Cache::put($this->getCacheKey('expires_at'), $expiresAt, $expiresIn);

        if ($refreshToken) {
            // Refresh token 有效期通常是 7 天
            Cache::put($this->getCacheKey('refresh_token'), $refreshToken, 604800);
        }
    }

    /**
     * 清除所有缓存的 Token
     */
    public function clearTokens(): void
    {
        Cache::forget($this->getCacheKey('access_token'));
        Cache::forget($this->getCacheKey('refresh_token'));
        Cache::forget($this->getCacheKey('expires_at'));
    }

    /**
     * 获取缓存键
     *
     * @param string $key
     * @return string
     */
    private function getCacheKey(string $key): string
    {
        $domain = get_safe_host();
        return self::CACHE_PREFIX . $domain . ':' . $key;
    }

    /**
     * 检查 Token 是否有效
     *
     * @return bool
     */
    public function hasValidToken(): bool
    {
        $token = $this->getAccessToken();
        $expiresAt = Cache::get($this->getCacheKey('expires_at'));

        return $token && $expiresAt && $expiresAt > time();
    }

    /**
     * 获取 Token 过期时间
     *
     * @return int|null
     */
    public function getTokenExpiresAt(): ?int
    {
        return Cache::get($this->getCacheKey('expires_at'));
    }

    /**
     * 检查是否有 Refresh Token
     *
     * @return bool
     */
    public function hasRefreshToken(): bool
    {
        return Cache::has($this->getCacheKey('refresh_token'));
    }

    /**
     * 统一清理开发者令牌，避免空白字符导致鉴权失败。
     */
    private function normalizeDeveloperToken(string $developerToken): string
    {
        $normalized = preg_replace('/\s+/u', '', trim($developerToken));

        return is_string($normalized) ? $normalized : '';
    }

}
