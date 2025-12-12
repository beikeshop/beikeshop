<?php

namespace Beike\Libraries;

class ToolCache
{
    protected static function basePath()
    {
        return storage_path('app/tool_cache');
    }

    protected static function filePath($key)
    {
        $safeKey = md5($key); // 防止非法字符
        return self::basePath() . '/' . $safeKey . '.json';
    }

    /**
     * 获取缓存
     */
    public static function get($key, $default = null)
    {
        $file = self::filePath($key);

        if (!file_exists($file)) {
            return $default;
        }

        $data = json_decode(file_get_contents($file), true);

        if (!is_array($data)) {
            return $default;
        }

        // 检查过期
        if (!empty($data['expire']) && time() > $data['expire']) {
            @unlink($file);
            return $default;
        }

        return $data['value'] ?? $default;
    }

    /**
     * 写入缓存
     */
    public static function put($key, $value, $ttl = null)
    {
        $dir = self::basePath();
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = self::filePath($key);

        $expire = null;

        // 兼容 Laravel 的时间对象: now()->addDays()
        if ($ttl instanceof \DateTimeInterface) {
            $expire = $ttl->getTimestamp();
        } elseif (is_numeric($ttl)) {
            $expire = time() + $ttl;
        }

        $data = [
            'value'  => $value,
            'expire' => $expire,
        ];

        // 防止并发写入：文件锁
        $fp = fopen($file, 'c+');
        if ($fp === false) {
            return false;
        }

        flock($fp, LOCK_EX);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }

    /**
     * 删除缓存
     */
    public static function forget($key)
    {
        $file = self::filePath($key);
        if (file_exists($file)) {
            @unlink($file);
        }
        return true;
    }
}
