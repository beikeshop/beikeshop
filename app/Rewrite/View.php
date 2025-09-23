<?php

namespace App\Rewrite;

use App\Aspect\Html;
use Illuminate\Contracts\View\Engine;
use Illuminate\View\Factory;

class View extends \Illuminate\View\View
{
    public function __construct(Factory $factory, Engine $engine, $view, $path, $data = [])
    {
        parent::__construct($factory, $engine, $view, $path, $data);
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @return string
     */
    protected function getContents(): string
    {
        $is_rewrite = true;

        foreach (config('app.ignore_dirs') as $dir) {
            if ($this->isSubdirectory(base_path($dir), $this->path)) {
                $is_rewrite = false;

                break;
            }
        }

        if ($is_rewrite) {
            $html = new Html($this->data, $this->path);
            $html->handle();

            $this->data = $html->getData();
            $this->path = $html->getPath();
        }

        return $this->engine->get($this->path, $this->gatherData());
    }

    /**
     * is child dir
     * @param $parentDir
     * @param $childDir
     * @return bool
     */
    public function isSubdirectory($parentDir, $childDir): bool
    {
        $parentDir = str_replace('\\', '/', rtrim($parentDir, '/')) . '/';
        $childDir = str_replace('\\', '/', rtrim($childDir, '/')) . '/';

        // 使用 preg_quote() 转义路径中的特殊字符
        $escapedParentDir = preg_quote($parentDir, '#');

        // 使用正则表达式匹配
        if (preg_match('#^' . $escapedParentDir . '#', $childDir)) {
            return true;
        }

        return false;
    }
}
