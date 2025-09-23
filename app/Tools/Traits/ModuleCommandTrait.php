<?php

namespace App\Tools\Traits;

trait ModuleCommandTrait
{
    public function getModuleName(): string
    {
        $module = $this->argument('plugin') ?: app('plugins')->getUsedNow();

        $module = app('plugins')->findOrFail($module);

        return $module->getStudlyName();
    }
}
