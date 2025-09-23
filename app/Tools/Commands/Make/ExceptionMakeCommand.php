<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ExceptionMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';
    protected $name = 'plugin:make-exception';
    protected $description = 'Create a new exception class for the specified plugin.';

    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['plugins']->getModulePath($this->getModuleName());

        $filePath = GenerateConfigReader::read('exceptions')->getPath() ?? config('plugins.paths.app_folder') . 'Exceptions';

        return $path . $filePath . '/' . $this->getExceptionName() . '.php';
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
            ['render', '', InputOption::VALUE_NONE, 'Create the exception with an empty render method', null],
            ['report', '', InputOption::VALUE_NONE, 'Create the exception with an empty report method', null],
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    protected function getExceptionName(): array|string
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getExceptionName());
    }

    public function getDefaultNamespace(): string
    {
        return config('plugins.paths.generator.exceptions.namespace', 'Exceptions');
    }

    protected function getStubName(): string
    {
        if ($this->option('render')) {
            return $this->option('report')
                ? '/exception-render-report.stub'
                : '/exception-render.stub';
        }

        return $this->option('report')
            ? '/exception-report.stub'
            : '/exception.stub';
    }
}
