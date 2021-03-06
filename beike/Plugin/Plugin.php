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

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Beike\Repositories\PluginRepo;
use Beike\Repositories\SettingRepo;
use Illuminate\Contracts\Support\Arrayable;

class Plugin implements Arrayable, \ArrayAccess
{
    protected $path;
    protected $name;
    protected $packageInfo;
    protected $dirName;
    protected $installed;
    protected $enabled;
    protected $version;
    protected $columns;


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

    public function setName(string $name): Plugin
    {
        $this->name = $name;
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

    public function setColumns(): Plugin
    {
        $columnsPath = $this->path . DIRECTORY_SEPARATOR . 'columns.php';
        if (!file_exists($columnsPath)) {
            return $this;
        }
        $this->columns = require_once $columnsPath;
        return $this;
    }


    public function getPath(): string
    {
        return $this->path;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getEditUrl(): string
    {
        return admin_route('plugins.edit', ['code' => $this->code]);
    }

    public function getStatus(): bool
    {
        return SettingRepo::getPluginStatus($this->code);
    }

    public function getInstalled(): bool
    {
        return PluginRepo::installed($this->code);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }


    /**
     * ?????????????????????????????????, ?????????????????????DB????????????
     *
     * @return array
     */
    public function getColumns(): array
    {
        $this->columns[] = SettingRepo::getPluginStatusColumn();
        $existValues = SettingRepo::getPluginColumns($this->code);
        foreach ($this->columns as $index => $column) {
            $dbColumn = $existValues[$column['name']] ?? null;
            $value = $dbColumn ? $dbColumn->value : null;
            if ($column['name'] == 'status') {
                $value = (int)$value;
            }
            $this->columns[$index]['value'] = $value;
        }
        return $this->columns;
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
