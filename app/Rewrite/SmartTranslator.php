<?php

namespace App\Rewrite;

use Illuminate\Translation\Translator;

class SmartTranslator extends Translator
{
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $locale = $locale ?: $this->locale;

        // 尝试多种 key 格式
        $normalizedKey = $this->normalizeKey($key, $locale);

        return parent::get($normalizedKey, $replace, $locale, $fallback);
    }

    protected function normalizeKey($key, $locale)
    {
        // 尝试原始 key
        $result = parent::get($key, [], $locale, false);
        if ($result !== $key) {
            return $key;
        }

        // 支持点号格式：admin.download.invalid_zip_format => admin/download.invalid_zip_format
        // 只替换第一个点号（从左到右）作为路径分隔符，保留后续点号作为嵌套键分隔符
        if (str_contains($key, '.')) {
            // 尝试不同的转换策略
            $strategies = [
                // 策略1：只替换第一个点号为斜杠
                function ($k) {
                    $pos = strpos($k, '.');
                    if ($pos !== false) {
                        return substr_replace($k, '/', $pos, 1);
                    }

                    return $k;
                },
                // 策略2：将最后一段的点号替换为下划线（处理混合格式）
                function ($k) {
                    $lastDotPos = strrpos($k, '.');
                    if ($lastDotPos !== false) {
                        return substr_replace($k, '_', $lastDotPos, 1);
                    }

                    return $k;
                },
                // 策略3：所有点号替换为斜杠
                function ($k) {
                    return str_replace('.', '/', $k);
                },
            ];

            foreach ($strategies as $strategy) {
                $converted = $strategy($key);
                $result    = parent::get($converted, [], $locale, false);
                if ($result !== $converted) {
                    return $converted;
                }
            }
        }

        // 支持旧格式：admin/download/invalid_zip_format => admin.download.invalid_zip_format
        // 将所有斜杠替换为点号
        if (str_contains($key, '/')) {
            $converted = str_replace('/', '.', $key);
            $result    = parent::get($converted, [], $locale, false);
            if ($result !== $converted) {
                return $converted;
            }
        }

        return $key;
    }
}
