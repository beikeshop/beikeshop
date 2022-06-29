<?php
/**
 * Plugin.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-06-29 20:27:21
 * @modified   2022-06-29 20:27:21
 */

namespace Beike\Plugin;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Plugin implements Arrayable, \ArrayAccess
{
    protected $path;
    protected $name;
    protected $packageInfo;
    protected $dirName;
    protected $installed;
    protected $enabled;
    protected $version;


    public function __construct(string $path, array $packageInfo)
    {
        $this->path = $path;
        $this->packageInfo = $packageInfo;
    }

    public function __get($name)
    {
        return $this->packageInfoAttribute(Str::snake($name, '-'));
    }

    public function __isset($name)
    {
        return isset($this->{$name}) || $this->packageInfoAttribute(Str::snake($name, '-'));
    }

    public function packageInfoAttribute($name)
    {
        return Arr::get($this->packageInfo, $name);
    }


    public function setDirname(string $dirName): Plugin
    {
        $this->dirName = $dirName;
        return $this;
    }

    public function setInstalled(bool $installed): Plugin
    {
        $this->installed = $installed;
        return $this;
    }

    public function setEnabled(bool $enabled): Plugin
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function setVersion(string $version): Plugin
    {
        $this->version = $version;
        return $this;
    }


    public function getVersion(): string
    {
        return $this->version;
    }

    public function toArray(): array
    {
        return (array)array_merge([
            'name' => $this->name,
            'version' => $this->getVersion(),
            'path' => $this->path
        ], $this->packageInfo);
    }

    public function offsetExists($key): bool
    {
        return Arr::has($this->packageInfo, $key);
    }

    public function offsetGet($key)
    {
        return $this->packageInfoAttribute($key);
    }

    public function offsetSet($key, $value)
    {
        return Arr::set($this->packageInfo, $key, $value);
    }

    public function offsetUnset($key)
    {
        unset($this->packageInfo[$key]);
    }
}
