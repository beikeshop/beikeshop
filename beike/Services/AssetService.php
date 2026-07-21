<?php

namespace Beike\Services;

class AssetService
{
    protected array $styles = [];

    protected array $scripts = [];

    /**
     * 添加 CSS
     */
    public function addStyle(string $path): void
    {
        $key                = md5($path);
        $this->styles[$key] = $path;
    }

    /**
     * 添加 JS
     *
     * @param string $path  资源路径
     * @param array  $attrs 附加属性，例如 ['defer' => true, 'async' => true]
     */
    public function addScript(string $path, array $attrs = []): void
    {
        $key                 = md5($path . serialize($attrs));
        $this->scripts[$key] = [
            'path'  => $path,
            'attrs' => $attrs,
        ];
    }

    /**
     * 获取样式
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * 获取脚本
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }
}
