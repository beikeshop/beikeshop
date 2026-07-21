<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleAdmin
{
    /**
     * 语言代码映射表：浏览器语言 -> 系统语言
     * 浏览器通常发送 zh-CN, zh-HK, zh-TW 等格式
     * 系统使用 zh_cn, zh_hk 等格式
     */
    private array $localeMap = [
        'zh_cn' => 'zh_cn',   // 简体中文
        'zh_hk' => 'zh_hk',   // 繁体中文（香港）
        'zh_tw' => 'zh_hk',   // 繁体中文（台湾）映射到 zh_hk
        'zh'    => 'zh_cn',   // 纯 zh 默认映射到简体中文
        'en'    => 'en',      // 英文
        'en_us' => 'en',      // en-us 转换后
        'en_gb' => 'en',      // en-gb 转换后
        'ja'    => 'ja',      // 日语
        'ko'    => 'ko',      // 韩语
        'ar'    => 'ar',      // 阿拉伯语
        'de'    => 'de',      // 德语
        'es'    => 'es',      // 西班牙语
        'fr'    => 'fr',      // 法语
        'id'    => 'id',      // 印尼语
        'it'    => 'it',      // 意大利语
        'ru'    => 'ru',      // 俄语
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user           = current_user();
        $adminLanguages = admin_languages();

        if ($user) {
            // 优先使用用户保存的语言设置
            if ($user->locale && in_array($user->locale, $adminLanguages)) {
                App::setLocale($user->locale);
                // 登录后，将用户设置的语言保存到 cookie 中
                setcookie('locale', $user->locale, time() + (60 * 60 * 24 * 7), '/');

                return $next($request);
            }
        }

        // 检查 cookie 中是否有语言设置
        $cookieLocale = $_COOKIE['locale'] ?? null;
        if ($cookieLocale && in_array($cookieLocale, $adminLanguages)) {
            App::setLocale($cookieLocale);

            return $next($request);
        }

        // 用户没有保存的语言设置，根据浏览器语言判断
        $browserLocale = $this->getBrowserLocale($request);
        if ($browserLocale) {
            App::setLocale($browserLocale);

            return $next($request);
        }

        // 默认使用英文
        App::setLocale('en');

        return $next($request);
    }

    /**
     * 获取浏览器首选语言并映射到系统语言
     *
     * @param Request $request
     * @return string|null
     */
    private function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        if (! $acceptLanguage) {
            return null;
        }

        $adminLanguages = admin_languages();

        // 解析 Accept-Language 头，例如: "zh-CN,zh;q=0.9,en;q=0.8"
        $languages = [];
        $parts     = explode(',', $acceptLanguage);

        foreach ($parts as $part) {
            $langPart = explode(';', $part);
            $lang     = trim($langPart[0]);

            // 将浏览器语言格式转换为系统格式：zh-CN -> zh_cn
            $langCode = strtolower(str_replace('-', '_', $lang));

            // 获取优先级 q 值
            $priority = 1.0;
            if (count($langPart) > 1) {
                $qPart = trim($langPart[1]);
                if (preg_match('/q=(\d\.?\d*)/', $qPart, $matches)) {
                    $priority = floatval($matches[1]);
                }
            }

            $languages[$langCode] = $priority;
        }

        // 按优先级排序（q值高的优先）
        arsort($languages);

        // 按优先级尝试匹配
        foreach ($languages as $browserLangCode => $priority) {
            // 1. 直接匹配：浏览器语言在系统支持列表中
            if (in_array($browserLangCode, $adminLanguages)) {
                return $browserLangCode;
            }

            // 2. 映射匹配：使用映射表转换
            if (isset($this->localeMap[$browserLangCode])) {
                $mappedLocale = $this->localeMap[$browserLangCode];
                if (in_array($mappedLocale, $adminLanguages)) {
                    return $mappedLocale;
                }
            }

            // 3. 前缀匹配：zh 匹配 zh_cn 或 zh_hk
            $prefix = explode('_', $browserLangCode)[0];
            if ($prefix === 'zh') {
                // 中文默认优先匹配 zh_cn（简体中文）
                if (in_array('zh_cn', $adminLanguages)) {
                    return 'zh_cn';
                }
                if (in_array('zh_hk', $adminLanguages)) {
                    return 'zh_hk';
                }
            }
        }

        return null;
    }
}
