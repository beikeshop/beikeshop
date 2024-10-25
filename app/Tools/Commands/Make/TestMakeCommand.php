<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TestMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';
    protected $name = 'plugin:make-test';
    protected $description = 'Create a new test class for the specified plugin.';

    public function getDefaultNamespace(): string
    {
        if ($this->option('feature')) {
            return config('plugins.paths.generator.test-feature.namespace')
                ?? config('plugins.paths.generator.test-feature.path', 'tests/Feature');
        }

        return config('plugins.paths.generator.test-unit.namespace')
            ?? config('plugins.paths.generator.test-unit.path', 'tests/Unit');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
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
            ['feature', null, InputOption::VALUE_NONE, 'Create a feature test.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['plugins']->findOrFail($this->getModuleName());
        $stub = '/unit-test.stub';

        if ($this->option('feature')) {
            $stub = '/feature-test.stub';
        }

        return (new Stub($stub, [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getClass(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['plugins']->getModulePath($this->getModuleName());

        if ($this->option('feature')) {
            $testPath = GenerateConfigReader::read('test-feature');
        } else {
            $testPath = GenerateConfigReader::read('test-unit');
        }

        return $path . $testPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
