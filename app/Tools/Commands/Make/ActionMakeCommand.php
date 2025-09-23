<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ActionMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';
    protected $name = 'plugin:make-action';
    protected $description = 'Create a new action class for the specified plugin.';

    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['plugins']->getModulePath($this->getModuleName());

        $filePath = GenerateConfigReader::read('actions')->getPath() ?? config('plugins.paths.app_folder') . 'Actions';

        return $path . $filePath . '/' . $this->getActionName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['plugins']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getClassNameWithoutNamespace(),
        ]))->render();
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the action class.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['invokable', 'i', InputOption::VALUE_NONE, 'Generate an invokable class', null],
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    protected function getActionName(): array|string
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getActionName());
    }

    public function getDefaultNamespace(): string
    {
        return config('plugins.paths.generator.actions.namespace', 'Actions');
    }

    protected function getStubName(): string
    {
        return $this->option('invokable') === true ? '/action-invoke.stub' : '/action.stub';
    }
}
