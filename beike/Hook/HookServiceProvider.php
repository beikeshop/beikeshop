<?php

namespace Beike\Hook;

use Beike\Hook\Console\HookListeners;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class HookServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            HookListeners::class,
        ]);

        $this->app->singleton('Hook', function () {
            return new Hook();
        });
    }

    public function boot()
    {
        $this->bootHookDirectives();
        $this->bootWrapperHookDirectives();
    }

    /**
     * 添加 blade hook 标签, 不需要 @endhook
     * @hook('xxx'), 添加 hook 直接输出到页面
     */
    protected function bootHookDirectives()
    {
        Blade::directive('hook', function ($parameter) {
            $parameter  = trim($parameter, '()');
            $parameters = explode(',', $parameter);

            $name = trim($parameters[0], "'");
            $definedVars = $this->parseParameters($parameters);

            return ' <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                '. $definedVars .'
                $output = \Hook::getHook("' . $name . '",["data"=>$__definedVars],function($data) { return null; });
                if ($output)
                echo $output;
                ?>';
        });
    }

    /**
     * 添加 blade wrapper hook 标签
     *
     * @hookwrapper('xxx') --- @endhookwrapper, 将某段代码打包输出再添加 hook 输出
     */
    protected function bootWrapperHookDirectives()
    {
        Blade::directive('hookwrapper', function ($parameter) {
            $parameter  = trim($parameter, '()');
            $parameters = explode(',', $parameter);
            $name       = trim($parameters[0], "'");

            return ' <?php
                    $__hook_name="' . $name . '";
                    ob_start();
                ?>';
        });

        Blade::directive('endhookwrapper', function () {
            return ' <?php
                $__definedVars = (get_defined_vars()["__data"]);
                if (empty($__definedVars))
                {
                    $__definedVars = [];
                }
                $__hook_content = ob_get_clean();
                $output = \Hook::getWrapper("$__hook_name",["data"=>$__definedVars],function($data) { return null; },$__hook_content);
                unset($__hook_name);
                unset($__hook_content);
                if ($output)
                echo $output;
                ?>';
        });
    }

    /**
     * Parse parameters from Blade
     *
     * @param $parameters
     * @return string
     */
    protected function parseParameters($parameters):string
    {
        $definedVars = '';
        foreach ($parameters as $paraItem) {
            $paraItem = trim($paraItem);
            if (Str::startsWith($paraItem,'$')) {
                $paraKey = trim($paraItem,  '$');
                $definedVars .= '$__definedVars["'.$paraKey.'"] = $'.$paraKey.';';
            }
        }
        return $definedVars;
    }
}
