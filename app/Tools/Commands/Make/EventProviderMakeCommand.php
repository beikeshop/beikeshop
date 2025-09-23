<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class EventProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'plugin';
    protected $name = 'plugin:make-event-provider';
    protected $description = 'Create a new event service provider class for the specified plugin.';

    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['plugins']->getModulePath($this->getModuleName());

        $filePath = GenerateConfigReader::read('provider')->getPath();

        return $path . $filePath . '/' . $this->getEventServiceProviderName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['plugins']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE'         => $this->getClassNamespace($module),
            'CLASS'             => $this->getClassNameWithoutNamespace(),
        ]))->render();
    }

    protected function getArguments(): array
    {
        return [
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

    protected function getEventServiceProviderName(): array|string
    {
        return Str::studly('EventServiceProvider');
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getEventServiceProviderName());
    }

    public function getDefaultNamespace(): string
    {
        return config('plugins.paths.generator.provider.namespace')
            ?? ltrim(config('plugins.paths.generator.provider.path', 'Providers'), config('plugins.paths.app_folder', ''));
    }

    protected function getStubName(): string
    {
        return '/event-provider.stub';
    }
}
