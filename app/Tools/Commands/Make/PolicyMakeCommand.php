<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class PolicyMakeCommand extends GeneratorCommand
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
    protected $name = 'plugin:make-policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new policy class for the specified plugin.';

    public function getDefaultNamespace(): string
    {
        return config('plugins.paths.generator.policies.namespace')
            ?? ltrim(config('plugins.paths.generator.policies.path', 'Policies'), config('plugins.paths.app_folder', ''));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the policy class.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['plugins']->findOrFail($this->getModuleName());

        return (new Stub('/policy.plain.stub', [
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

        $policyPath = GenerateConfigReader::read('policies');

        return $path . $policyPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }
}
