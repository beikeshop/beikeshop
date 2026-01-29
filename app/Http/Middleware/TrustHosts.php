<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
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

        // 转换为正则表达式格式，确保精确匹配
        // preg_quote 转义特殊字符，如 . → \.
        // ^ 和 $ 锚点确保完整匹配，防止部分匹配攻击
        return [
            '^' . preg_quote($trustedHost, '/') . '$',
        ];
    }
}
