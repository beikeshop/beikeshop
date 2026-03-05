<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
        $enabledLanguages = languages()->toArray();
        $rawPath = $this->getRawPath($request);
        $locale = $this->resolveLocale($request, $enabledLanguages, $rawPath);
        $defaultLocale = system_setting('base.locale');

        // 设置语言
        App::setLocale($locale);
        session(['locale' => $locale]);

        // 规范化URL中的语言代码
        $redirectResponse = $this->redirectIfNeeded($request, $rawPath, $locale, $defaultLocale, $enabledLanguages);
        if ($redirectResponse) {
            return $redirectResponse;
        }

        return $next($request);
    }

    /**
     * 检查并在需要时重定向到带有语言代码的URL
     *
     * @param Request $request
     * @param string $rawPath
     * @param string $locale
     * @param string $defaultLocale
     * @param array $enabledLanguages
     */
    private function redirectIfNeeded(
        Request $request,
        string $rawPath,
        string $locale,
        string $defaultLocale,
        array $enabledLanguages
    ): ?RedirectResponse
    {
        $segments = array_values(array_filter(explode('/', trim($rawPath, '/'))));

        // 语言切换路由自身不做重写，避免干扰切换逻辑
        if (($segments[0] ?? null) === 'lang') {
            return null;
        }

        $knownLanguages = config('app.langs', $enabledLanguages);
        $urlLocale = $segments[0] ?? null;
        $hasLocalePrefix = $urlLocale && in_array($urlLocale, $knownLanguages, true);

        $targetPath = null;

        // URL包含语言前缀时：默认语言或未启用语言都应移除前缀
        if ($hasLocalePrefix) {
            $shouldRemovePrefix = ($urlLocale === $defaultLocale) || !in_array($urlLocale, $enabledLanguages, true);
            if ($shouldRemovePrefix) {
                array_shift($segments);
                $targetPath = '/' . implode('/', $segments);
            }
        } elseif ($locale !== $defaultLocale) {
            // URL不包含语言前缀时：非默认语言自动补前缀
            $targetPath = '/' . trim($locale . '/' . trim($rawPath, '/'), '/');
        }

        if ($targetPath === null) {
            return null;
        }

        $targetPath = $targetPath === '' ? '/' : $targetPath;
        if (!str_starts_with($targetPath, '/')) {
            $targetPath = '/' . $targetPath;
        }

        $queryString = $request->getQueryString();
        $currentPath = $rawPath;
        $currentPath .= $queryString ? '?' . $queryString : '';
        $targetFullPath = $targetPath . ($queryString ? '?' . $queryString : '');

        if ($targetFullPath === $currentPath) {
            return null;
        }

        return redirect()->to($targetFullPath, 302);
    }

    private function getRawPath(Request $request): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? $request->server('REQUEST_URI', '/');
        $path = parse_url($uri, PHP_URL_PATH);

        return $path ?: '/';
    }

    private function resolveLocale(Request $request, array $enabledLanguages, string $rawPath): string
    {
        $locale = $request->header('locale');
        if ($locale && in_array($locale, $enabledLanguages, true)) {
            return $locale;
        }

        $segments = array_values(array_filter(explode('/', trim($rawPath, '/'))));
        if (!empty($segments) && in_array($segments[0], $enabledLanguages, true)) {
            return $segments[0];
        }

        $locale = $request->query('locale');
        if ($locale && in_array($locale, $enabledLanguages, true)) {
            return $locale;
        }

        $locale = session('locale');
        if ($locale && in_array($locale, $enabledLanguages, true)) {
            return $locale;
        }

        return system_setting('base.locale');
    }
}
