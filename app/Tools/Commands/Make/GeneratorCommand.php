<?php

namespace App\Tools\Commands\Make;

use Illuminate\Console\Command;
use App\Tools\Exceptions\FileAlreadyExistException;
use App\Tools\Generators\FileGenerator;
use App\Tools\Traits\PathNamespace;

abstract class GeneratorCommand extends Command
{
    use PathNamespace;

    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected $argumentName = '';

    /**
     * Get template contents.
     *
     * @return string
     */
    abstract protected function getTemplateContents();

    /**
     * Get the destination file path.
     *
     * @return string
     */
    abstract protected function getDestinationFilePath();

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            $this->components->task("Generating file {$path}", function () use ($path, $contents) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();
            });

        } catch (FileAlreadyExistException $e) {
            $this->components->error("File : {$path} already exists.");

            return E_ERROR;
        }

        return 0;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return class_basename($this->argument($this->argumentName));
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        return '';
    }

    /**
     * Get class namespace.
     *
     * @param \App\Tools\Module $module
     *
     * @return string
     */
    public function getClassNamespace($module)
    {
        $path_namespace = $this->path_namespace(str_replace($this->getClass(), '', $this->argument($this->argumentName)));

        return $this->module_namespace($module->getStudlyName(), $this->getDefaultNamespace() . ($path_namespace ? '\\' . $path_namespace : ''));
    }
}
