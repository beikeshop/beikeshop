<?php

namespace App\Tools\Commands\Make;

use App\Tools\Commands\Make\GeneratorCommand;
use Illuminate\Support\Str;
use App\Tools\Support\Config\GenerateConfigReader;
use App\Tools\Support\Stub;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AspectMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make-aspect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new aspect for the specified plugin.';

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['plugins']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('plugin-html-aspect');

        return $path . $controllerPath->getPath() . '/' . $this->getControllerName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['plugins']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getControllerNameWithoutNamespace(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['plugins']->config('namespace'),
        ]))->render();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the aspect class.'],
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
        ];
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = Str::studly($this->argument('name'));

        if (Str::contains(strtolower($controller), 'name') === false) {
            $controller .= 'Aspect';
        }

        return $controller;
    }

    /**
     * @return array|string
     */
    private function getControllerNameWithoutNamespace()
    {
        return class_basename($this->getControllerName());
    }

    public function getDefaultNamespace(): string
    {
        return config('plugins.paths.generator.plugin-html-aspect.namespace')
            ?? ltrim(config('plugins.paths.generator.plugin-html-aspect.path', 'Html/Aspect'), config('plugins.paths.app_folder'));
    }

    /**
     * Get the stub file name based on the options
     * @return string
     */
    protected function getStubName()
    {
        return '/custom/plugin-html-aspect.stub';
    }
}
