<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromSession
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // 优先从请求头中获取 'locale'
        $localeFromHeader = $request->header('locale');
        if ($localeFromHeader && in_array($localeFromHeader, languages()->toArray())) {
            $locale = $localeFromHeader;
        } else {
            // 如果请求头没有语言，尝试从 URL 中提取
            $localeFromUrl = $this->getLocaleFromUrl($request);
            if ($localeFromUrl) {
                $locale = $localeFromUrl;
            } else {
                // 如果都没有从请求头或 URL 中获取到语言，使用会话中的语言
                $locale = session('locale');
                if (!$locale || !in_array($locale, languages()->toArray())) {
                    // 如果会话中没有有效语言，使用系统默认语言
                    $locale = system_setting('base.locale');
                }
            }
        }

        // 设置语言
        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }

    /**
     * 从原始请求 URL 中解析出语言代码
     *
     * @return string|null
     */
    private function getLocaleFromUrl(Request $request): ?string
    {
        $uri = $_SERVER['REQUEST_URI'];

        // 提取路径部分
        $path = parse_url($uri, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        // 如果路径的第一个部分是有效的语言代码，返回该语言代码
        if (count($segments) > 0 && in_array($segments[0], languages()->toArray())) {
            return $segments[0];
        }

        // 还要从url中的 locale 获取，兼容 app 那边使用 webview 访问 locale传参
        $localeFromUrl = $request->query('locale');
        if ($localeFromUrl && in_array($localeFromUrl, languages()->toArray())) {
            return $localeFromUrl;
        }

        return null;
    }
}
