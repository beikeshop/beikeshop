<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ScopeMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';
    protected $name = 'plugin:make-scope';
    protected $description = 'Create a new scope class for the specified plugin.';

    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['plugins']->getModulePath($this->getModuleName());

        $filePath = GenerateConfigReader::read('scopes')->getPath() ?? config('plugins.paths.generator.model.path') . '/Scopes';

        return $path . $filePath . '/' . $this->getScopeName() . '.php';
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
            ['name', InputArgument::REQUIRED, 'The name of the scope class.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    protected function getScopeName(): array|string
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getScopeName());
    }

    public function getDefaultNamespace(): string
    {
        $namespace = config('plugins.paths.generator.model.path');

        $parts = explode("/", $namespace);
        $models = end($parts);

        return $models.'\Scopes';
    }

    protected function getStubName(): string
    {
        return '/scope.stub';
    }
}
