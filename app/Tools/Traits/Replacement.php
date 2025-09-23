<?php

namespace App\Tools\Traits;

use App\Tools\Support\Config\GenerateConfigReader;
use Illuminate\Support\Str;
trait Replacement
{
    /**
     * Get replacement for $AUTHOR_NAME$.
     *
     * @return string
     */
    protected function getAuthorNameReplacement()
    {
        return $this->author['name'] ?: $this->module->config('composer.author.name');
    }

    /**
     * Get replacement for $AUTHOR_EMAIL$.
     *
     * @return string
     */
    protected function getAuthorEmailReplacement()
    {
        return $this->author['email'] ?: $this->module->config('composer.author.email');
    }

    /**
     * Get the module name in lower case.
     *
     * @return string
     */
    protected function getLowerNameReplacement()
    {
        return strtolower($this->getName());
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getStudlyNameReplacement()
    {
        return $this->getName();
    }

    /**
     * Get replacement for $VENDOR$.
     *
     * @return string
     */
    protected function getVendorReplacement()
    {
        return $this->vendor ?: $this->module->config('composer.vendor');
    }

    /**
     * Get replacement for $MODULE_NAMESPACE$.
     *
     * @return string
     */
    protected function getModuleNamespaceReplacement()
    {
        return str_replace('\\', '\\\\', $this->module->config('namespace') ?? $this->path_namespace($this->module->config('paths.plugins')));
    }

    /**
     * Get replacement for $CONTROLLER_NAMESPACE$.
     */
    private function getControllerNamespaceReplacement(): string
    {
        if ($this->module->config('paths.generator.controller.namespace')) {
            return $this->module->config('paths.generator.controller.namespace');
        } else {
            return $this->path_namespace(ltrim($this->module->config('paths.generator.controller.path', 'app/Http/Controllers'), config('plugins.paths.app_folder')));
        }
    }

    /**
     * Get replacement for $APP_FOLDER_NAME$.
     *
     * @return string
     */
    protected function getAppFolderNameReplacement()
    {
        return  $this->module->config('paths.app_folder');
    }

    protected function getProviderNamespaceReplacement(): string
    {
        return str_replace('\\', '\\\\', GenerateConfigReader::read('provider')->getNamespace());
    }

    /**
     * Get the plugin code in lower case.
     *
     * @return string
     */
    protected function getPluginCodeReplacement(): string
    {
        return Str::snake($this->getName());
    }
}
