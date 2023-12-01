<?php
/**
 * Registry.php
 *
 * @copyright  2023 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2023-04-20 16:29:54
 * @modified   2023-04-20 16:29:54
 */

namespace Beike\Libraries;

class Registry
{
    private array $data = [];

    private static $registry;

    public static function getSingleton(): self
    {
        if (self::$registry instanceof self) {
            return self::$registry;
        }

        return self::$registry = new self();
    }

    /**
     * @param      $key
     * @param null $default
     * @return mixed
     */
    public static function get($key, $default = null): mixed
    {
        return self::getSingleton()->getValue($key, $default);
    }

    /**
     * @param      $key
     * @param      $value
     * @param bool $force
     */
    public static function set($key, $value, bool $force = false)
    {
        if (self::getSingleton()->has($key) && ! $force) {
            return;
        }
        self::getSingleton()->setValue($key, $value);
    }

    public function destroy()
    {
        self::$registry = null;
    }

    /**
     * @param      $key
     * @param null $default
     * @return mixed
     */
    public function getValue($key, $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setValue($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return isset($this->data[$key]);
    }
}
