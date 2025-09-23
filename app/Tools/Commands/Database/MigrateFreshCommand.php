<?php

namespace App\Tools\Commands\Database;

use Illuminate\Console\Command;
use App\Tools\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateFreshCommand extends Command
{
    use ModuleCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:migrate-fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all database tables and re-run the plugins migrations.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $plugin = $this->argument('plugin');

        if ($plugin && !$this->getModuleName()) {
            $this->error("Plugin [$plugin] does not exists.");

            return E_ERROR;
        }

        $this->call('plugin:migrate-refresh', [
            'plugin' => $this->getModuleName(),
            '--database' => $this->option('database'),
            '--force' => $this->option('force'),
            '--seed' => $this->option('seed'),
        ]);

        return 0;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['plugin', InputArgument::OPTIONAL, 'The name of plugin will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
        ];
    }

    public function getModuleName()
    {
        $module = $this->argument('plugin');

        if (!$module) {
            return null;
        }

        $module = app('plugins')->find($module);

        return $module ? $module->getStudlyName() : null;
    }
}
