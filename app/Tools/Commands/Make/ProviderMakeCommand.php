<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Module;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service provider class for the specified plugin.';

    public function getDefaultNamespace(): string
    {
        return config('plugins.paths.generator.provider.namespace')
            ?? ltrim(config('plugins.paths.generator.provider.path', 'Providers'), config('plugins.paths.app_folder', ''));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The service provider name.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['master', null, InputOption::VALUE_NONE, 'Indicates the master service provider', null],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $stub = $this->option('master') ? 'scaffold/provider' : 'provider';

        /** @var Module $module */
        $module = $this->laravel['plugins']->findOrFail($this->getModuleName());

        return (new Stub('/' . $stub . '.stub', [
            'NAMESPACE'        => $this->getClassNamespace($module),
            'CLASS'            => $this->getClass(),
            'LOWER_NAME'       => $module->getLowerName(),
            'MODULE'           => $this->getModuleName(),
            'NAME'             => $this->getFileName(),
            'STUDLY_NAME'      => $module->getStudlyName(),
            'MODULE_NAMESPACE' => $this->laravel['plugins']->config('namespace'),
            'PATH_VIEWS'       => GenerateConfigReader::read('views')->getPath(),
            'PATH_LANG'        => GenerateConfigReader::read('lang')->getPath(),
            'PATH_CONFIG'      => GenerateConfigReader::read('config')->getPath(),
            'MIGRATIONS_PATH'  => GenerateConfigReader::read('migration')->getPath(),
            'FACTORIES_PATH'   => GenerateConfigReader::read('factory')->getPath(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['plugins']->getModulePath($this->getModuleName());

        $generatorPath = GenerateConfigReader::read('provider');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
