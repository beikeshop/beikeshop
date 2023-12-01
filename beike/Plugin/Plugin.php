<?php
/**
 * Plugin.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-06-29 20:27:21
 * @modified   2022-06-29 20:27:21
 */

namespace Beike\Plugin;

use Beike\Admin\Services\MarketingService;
use Beike\Repositories\PluginRepo;
use Beike\Repositories\SettingRepo;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Plugin implements \ArrayAccess, Arrayable
{
    public const TYPES = [
        'payment',    // 支付方式
        'shipping',   // 配送方式
        'theme',      // 主题模板
        'feature',    // 功能模块
        'total',      // 订单金额
        'social',     // 社交网络
        'language',   // 语言翻译
        'translator', // 翻译工具
    ];

    protected $type;

    protected $path;

    protected $name;

    protected $description;

    protected $packageInfo;

    protected $dirName;

    protected $installed;

    protected $enabled;

    protected $version;

    protected $columns;

    public function __construct(string $path, array $packageInfo)
    {
        $this->path        = $path;
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

    /**
     * Set plugin Type
     *
     * @throws \Exception
     */
    public function setType(string $type): self
    {
        if (! in_array($type, self::TYPES)) {
            throw new \Exception('Invalid plugin type, must be one of ' . implode(',', self::TYPES));
        }
        $this->type = $type;

        return $this;
    }

    public function setDirname(string $dirName): self
    {
        $this->dirName = $dirName;

        return $this;
    }

    public function setName(string|array $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setDescription(string|array $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setInstalled(bool $installed): self
    {
        $this->installed = $installed;

        return $this;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function setColumns(): self
    {
        $columnsPath = $this->path . DIRECTORY_SEPARATOR . 'columns.php';
        if (! file_exists($columnsPath)) {
            return $this;
        }
        $this->columns = require_once $columnsPath;

        return $this;
    }

    /**
     * 处理插件后台设置字段多语言 优先级: label > label_key
     * 有label字段则直接返回, label_key 则翻译
     */
    public function handleLabel(): void
    {
        $this->columns = collect($this->columns)->map(function ($item) {
            $item = $this->transLabel($item);
            if (isset($item['options'])) {
                $item['options'] = collect($item['options'])->map(function ($option) {
                    return $this->transLabel($option);
                })->toArray();
            }

            return $item;
        })->toArray();
    }

    /**
     * 翻译 label
     * @param $item
     * @return mixed
     */
    private function transLabel($item): mixed
    {
        $labelKey = $item['label_key']    ?? '';
        $label    = $item['label']        ?? '';
        if (empty($label) && $labelKey) {
            $languageKey   = "{$this->dirName}::{$labelKey}";
            $item['label'] = trans($languageKey);
        }

        $descriptionKey = $item['description_key']    ?? '';
        $description    = $item['description']        ?? '';
        if (empty($description) && $descriptionKey) {
            $languageKey         = "{$this->dirName}::{$descriptionKey}";
            $item['description'] = trans($languageKey);
        }

        return $item;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLocaleName(): string
    {
        $currentLocale = admin_locale();

        if (is_array($this->name)) {
            if ($this->name[$currentLocale] ?? '') {
                return $this->name[$currentLocale];
            }

            return array_values($this->name)[0];
        }

        return (string) $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getLocaleDescription(): string
    {
        $currentLocale = admin_locale();

        if (is_array($this->description)) {
            if ($this->description[$currentLocale] ?? '') {
                return $this->description[$currentLocale];
            }

            return array_values($this->description)[0];
        }

        return (string) $this->description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDirname(): string
    {
        return $this->dirName;
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

    public function getSetting($name = '')
    {
        if ($name) {
            return plugin_setting("{$this->code}.{$name}");
        }

        return plugin_setting($this->code);
    }

    /**
     * 获取插件对应的设置字段, 并获取已存储在DB的字段值
     *
     * @return array
     */
    public function getColumns(): array
    {
        $this->columns[] = SettingRepo::getPluginStatusColumn();
        $existValues     = SettingRepo::getPluginColumns($this->code);
        foreach ($this->columns as $index => $column) {
            $dbColumn = $existValues[$column['name']] ?? null;
            $value    = $dbColumn ? $dbColumn->value : null;
            if ($column['name'] == 'status') {
                $value = (int) $value;
            }
            $this->columns[$index]['value'] = $value;
        }

        return $this->columns;
    }

    /**
     * 字段验证
     * @param $requestData
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate($requestData): \Illuminate\Contracts\Validation\Validator
    {
        $rules = array_column($this->columns, 'rules', 'name');

        return Validator::make($requestData, $rules);
    }

    /**
     * 获取插件自定义编辑模板
     * @return string
     */
    public function getColumnView(): string
    {
        $viewFile = $this->getPath() . '/Views/admin/config.blade.php';
        if (file_exists($viewFile)) {
            return "{$this->dirName}::admin.config";
        }

        return '';
    }

    /**
     * 获取插件启动文件路径
     *
     * @return string
     */
    public function getBootFile(): string
    {
        return $this->getPath() . '/Bootstrap.php';
    }

    /**
     * Check plugin has license.
     *
     * @return bool
     * @throws \Exception
     */
    public function checkLicenseValid(): bool
    {
        $appDomain = request()->getHost();

        try {
            $domain         = new \Utopia\Domains\Domain($appDomain);
            $registerDomain = $domain->getRegisterable();
        } catch (\Exception $e) {
            $registerDomain = '';
        }

        if (empty($registerDomain)) {
            return true;
        }

        $license = MarketingService::getInstance()->checkLicense($this->code, $registerDomain);
        $status  = $license['status'] ?? 'fail';
        if ($status == 'fail') {
            SettingRepo::update('plugin', $this->code, ['status' => false]);

            throw new \Exception($license['message'] ?? '插件授权未知错误, 请联系 beikeshop.com');
        }

        return $license['data']['has_license'] ?? false;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge([
            'name'    => $this->name,
            'version' => $this->getVersion(),
            'path'    => $this->path,
        ], $this->packageInfo);
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return Arr::has($this->packageInfo, $offset);
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->packageInfoAttribute($offset);
    }

    /**
     * @param $offset
     * @param $value
     * @return array
     */
    public function offsetSet($offset, $value): array
    {
        return Arr::set($this->packageInfo, $offset, $value);
    }

    /**
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->packageInfo[$offset]);
    }
}
