<?php
/**
 * Plugin.php
 *
 * @copyright  2022 opencart.cn - All Rights Reserved
 * @link       http://www.guangdawangluo.com
 * @author     Edward Yang <yangjin@opencart.cn>
 * @created    2022-04-18 16:20:43
 * @modified   2022-04-18 16:20:43
 */

namespace Beike\Plugin;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

class Plugin implements Arrayable, \ArrayAccess
{
    /**
     * The full directory of the plugin.
     * @var string
     */
    protected string $path;

    /**
     * The directory name of the plugin.
     *
     * @var string
     */
    protected string $dirName;

    /**
     * The information of package.json of the package.
     *
     * @var array
     */
    protected array $packageInfo;

    /**
     * Whether the plugin is installed.
     *
     * @var bool
     */
    protected bool $installed = true;

    /**
     * The installed version of the plugin.
     *
     * @var string
     */
    protected string $version;

    /**
     * The namespace used by the plugin.
     *
     * @var string
     */
    protected string $namespace;

    /**
     * Whether the plugin is enabled.
     *
     * @var bool
     */
    protected bool $enabled = false;

    /**
     * @var array
     */
    private array $require;


    /**
     * @param string $path
     * @param array $packageInfo
     */
    public function __construct(string $path, array $packageInfo)
    {
        $this->path = $path;
        $this->packageInfo = $packageInfo;
    }

    /**
     * Get package information by name
     *
     * @return string
     */
    public function __get($name)
    {
        return $this->packageInfoAttribute(Str::snake($name, '-'));
    }

    public function __isset($name)
    {
        return isset($this->{$name}) || $this->packageInfoAttribute(Str::snake($name, '-'));
    }

    /**
     * @param $name
     * @return array|\ArrayAccess|mixed
     */
    public function packageInfoAttribute($name)
    {
        return Arr::get($this->packageInfo, $name);
    }

    /**
     * @param bool $installed
     * @return Plugin
     */
    public function setInstalled(bool $installed): Plugin
    {
        $this->installed = $installed;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->installed;
    }

    public function getDirname(): string
    {
        return $this->dirName;
    }

    public function setDirname($dirname): Plugin
    {
        $this->dirName = $dirname;

        return $this;
    }

    public function getNameSpace(): string
    {
        return $this->namespace;
    }

    public function setNameSpace($namespace): Plugin
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getViewPath($name): string
    {
        return $this->getViewPathByFileName("$name.blade.php");
    }

    public function getViewPathByFileName($filename): string
    {
        return $this->path . "/views/$filename";
    }

    public function getConfigView(): ?\Illuminate\Contracts\View\View
    {
        if ($this->hasConfigView()) {
            return view()->file($this->getViewPathByFileName(Arr::get($this->packageInfo, 'config')));
        }
        return null;
    }

    public function hasConfigView(): bool
    {
        $filename = Arr::get($this->packageInfo, 'config');

        return $filename && file_exists($this->getViewPathByFileName($filename));
    }

    /**
     * @param string $version
     * @return Plugin
     */
    public function setVersion(string $version): Plugin
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param array $require
     * @return Plugin
     */
    public function setRequirements(array $require): Plugin
    {
        $this->require = $require;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequirements(): array
    {
        return (array)$this->require;
    }

    /**
     * @param bool $enabled
     * @return Plugin
     */
    public function setEnabled(bool $enabled): Plugin
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
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
