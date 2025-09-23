<?php

namespace App\Tools\Commands\Actions;

use Illuminate\Database\Console\ShowModelCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('plugin:model-show', 'Show information about an Eloquent model in plugins')]
class ModelShowCommand extends ShowModelCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:model-show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show information about an Eloquent model in plugins';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'plugin:model-show {model : The model to show}
                {--database= : The database connection to use}
                {--json : Output the model as JSON}';

    /**
     * Qualify the given model class base name.
     *
     * @param string $model
     * @return string
     *
     * @see \Illuminate\Console\GeneratorCommand
     */
    protected function qualifyModel(string $model): string
    {
        if (str_contains($model, '\\') && class_exists($model)) {
            return $model;
        }

        $rootNamespace = config('plugins.namespace');

        $modelPath = glob($rootNamespace . DIRECTORY_SEPARATOR .
            '*' . DIRECTORY_SEPARATOR .
            config('plugins.paths.generator.model.path') . DIRECTORY_SEPARATOR .
            "$model.php");

        if (!count($modelPath)) {
            return $model;
        }

        return str_replace(['/', '.php'], ['\\', ''], $modelPath[0]);
    }

}
