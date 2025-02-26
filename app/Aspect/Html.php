<?php

namespace App\Aspect;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Pipeline\Pipeline;
use QL\QueryList;
use stdClass;

class Html
{
    private mixed $data;

    private mixed $path;

    public function __construct($data, $path)
    {
        $this->data = $data;
        $this->path = $path;
    }

    private function jsEvents(): array
    {
        return [
            'click', 'dblclick', 'mouseup', 'mousedown', 'contextmenu',
            'mousewheel', 'DOMMouseScroll', 'mouseover', 'mouseout',
            'mousemove', 'selectstart', 'selectend', 'keydown',
            'keypress', 'keyup', 'orientationchange', 'touchstart',
            'touchmove', 'touchend', 'touchcancel', 'pointerdown',
            'pointermove', 'pointerup', 'pointerleave', 'pointercancel',
            'gesturestart', 'gesturechange', 'gestureend', 'focus', 'blur',
            'change', 'reset', 'select', 'submit', 'focusin', 'focusout',
            'load', 'unload', 'beforeunload', 'resize', 'move', 'DOMContentLoaded',
            'readystatechange', 'error', 'abort', 'scroll', 'input',
            'dragstart', 'dragend', 'dragover', 'drop', 'start', 'update', 'close',
        ];

    }

    /**
     *  get path
     *
     * @return mixed
     */
    public function getPath(): mixed
    {
        return $this->path;
    }

    /**
     *  get data
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     *  handle the action
     *
     * @return void
     */
    public function handle(): void
    {
        $files = $this->getAspectFiles();

        $config = $this->getConfig($files);

        $pathKey = ltrim(str_replace([base_path(), '\\'], ['', '/'], $this->path), '/');

        $html = file_get_contents($this->path);

        if (! config('app.debug') && ! isset($config[$pathKey])) {
            return;
        }

        if (isset($config[$pathKey])) {
            $html = $this->getHtml($html, $config[$pathKey]);
        }

        $filename = md5($this->path) . '_' . basename($this->path);
        $newPath  = storage_path('aspect/html');

        if (! is_dir($newPath)) {
            (new Filesystem)->makeDirectory($newPath, recursive: true);
        }

        $newPath = $newPath . '/' . $filename;

        file_put_contents(
            $newPath,
            html_entity_decode($html, ENT_QUOTES, 'UTF-8')
        );

        if ($newPath) {
            $this->path = $newPath;
        }
    }

    /**
     *  get aspect files
     *
     * @return array
     */
    public function getAspectFiles(): array
    {
        $aspectFiles = [];

        $pluginPath = plugin_path();
        $dirs       = scandir($pluginPath);

        array_filter($dirs, function ($dirs) use ($pluginPath, &$aspectFiles) {
            $fullPath = $pluginPath . DIRECTORY_SEPARATOR . $dirs;
            if (is_dir($fullPath) && ! in_array($dirs, ['.', '..', '.gitignore', '.git'])) {
                $pattern = $fullPath . '/Aspect/Html/*Aspect.php';
                $files   = glob($pattern);
                foreach ($files as $file) {
                    $aspectFiles[] = $file;
                }
            }
        });

        return $aspectFiles;
    }

    /**
     *  get aspect config
     *
     * @param $files
     * @return array|mixed
     */
    private function getConfig($files): mixed
    {
        $data       = [];

        $pluginPath = plugin_path();

        $config = storage_path('aspect/config');

        if (! is_dir($config)) {
            (new Filesystem)->makeDirectory($config, recursive: true);
        }

        $config = $config . '/html.config';

        if (file_exists($config) && ! config('app.debug')) {
            return json_decode(file_get_contents($config), true);
        }

        foreach ($files as $file) {
            $search  = [$pluginPath, '.php', '/'];
            $replace = ['', '', '\\'];

            $file            = str_replace($search, $replace, $file);
            $aspectClassName = 'Plugin\\' . ltrim($file, '\\');

            if (class_exists($aspectClassName)) {
                $app = app($aspectClassName);
                if (! method_exists($app, 'handle')) {
                    continue;
                }

                if ($app->template_file) {
                    $data[] = [
                        'template_file' => $app->template_file,
                        'priority'      => $app->priority,
                        'class_name'    => $aspectClassName,
                    ];
                }

                unset($app);
            }
        }

        $sortByPriority = function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        };

        usort($data, $sortByPriority);

        $newArray  = [];
        foreach ($data as $item) {
            $newArray[$item['template_file']][] = $item['class_name'];
        }

        file_put_contents($config, json_encode($newArray));

        return $newArray;
    }

    /**
     *  get html after update
     *
     * @param $data
     * @param $handles
     * @return array|string
     */
    private function getHtml($data, $handles): array|string
    {
        $fileData = $this->beforeReplace($data);

        $ql = QueryList::html("<div id='beikeshop-root'>" . $fileData . '</div>');

        $this->applyAspectToUpdateHtml($ql, $handles);

        $html = $ql->find('div#beikeshop-root')->html();

        return $this->afterReplace(rawurldecode($html));
    }

    /**
     * apply aspect to update html
     *
     * @param QueryList $ql
     * @param           $handles
     * @return void
     */
    private function applyAspectToUpdateHtml(QueryList $ql, $handles): void
    {
        if (! $handles) {
            return;
        }

        $arguments       = new stdClass();
        $arguments->ql   = $ql;
        $arguments->data = $this->data;

        $this->data = $this->pipelineHandle($arguments, $handles)->data;
    }

    /**
     *  pipeline handle
     *
     * @param $data
     * @param $handles
     * @return mixed
     */
    public function pipelineHandle($data, $handles): mixed
    {
        return app(Pipeline::class)
            ->send($data)
            ->through($handles)
            ->thenReturn();
    }

    /**
     *  before replace data
     *
     * @param $data
     * @return array|string
     */
    public function beforeReplace($data): array|string
    {
        foreach ($this->jsEvents() as $event) {
            $data = str_replace('@' . $event, 'v-on:' . $event, $data);
        }

        $search   = ['x-admin::', '&nbsp;'];
        $replace  = ['x-admin.', '-nbsp'];

        return str_replace($search, $replace, $data);
    }

    /**
     *  after replace data
     *
     * @param $data
     * @return array|string
     */
    public function afterReplace($data): array|string
    {
        foreach ($this->jsEvents() as $event) {
            $data = str_replace('v-on:' . $event, '@' . $event, $data);
        }

        $search  = ['x-admin.', '-nbsp'];
        $replace = ['x-admin::', '&nbsp;'];

        return str_replace($search, $replace, $data);
    }
}
