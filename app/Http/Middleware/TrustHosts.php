<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Handle the incoming request.
     * 当 trust_hosts_enabled 为 false 时跳过 Host 校验。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $next)
    {
        if (! config('app.trust_hosts_enabled', true)) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }

    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string>
     */
    public function hosts(): array
    {
        $trustedHost = get_safe_host();

        if (empty($trustedHost)) {
            return [];
        }

        // 如果是 IP 地址，直接精确匹配
        if (filter_var($trustedHost, FILTER_VALIDATE_IP)) {
            return [
                '^' . preg_quote($trustedHost, '/') . '$',
            ];
        }

        // 使用项目的 get_domain() 函数提取主域名
        // 支持常规域名和多级顶级域名（如 .co.uk, .com.au 等）
        // 例如：www.example.com → example.com
        //      api.shop.example.co.uk → example.co.uk
        $rootDomain = get_domain($trustedHost);
        $escapedRootDomain = preg_quote($rootDomain, '/');

        return [
            '^' . $escapedRootDomain . '$',                     // 精确匹配根域名
            '^.*\.' . $escapedRootDomain . '$',                // 匹配所有子域名（如 www.example.com, api.example.com, admin.api.example.com）
        ];
    }
}
