<?php

namespace App\Tools\Commands\Database;

use App\Tools\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputOption;

class MigrateRefreshCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:migrate-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback & re-migrate the plugins migrations.';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->task("Refreshing Migration {$module->getName()} plugin", function () use ($module) {
            $this->call('plugin:migrate-reset', [
                'plugin'     => $module->getStudlyName(),
                '--database' => $this->option('database'),
                '--force'    => $this->option('force'),
            ]);

            $this->call('plugin:migrate', [
                'plugin'     => $module->getStudlyName(),
                '--database' => $this->option('database'),
                '--force'    => $this->option('force'),
            ]);

            if ($this->option('seed')) {
                $this->call('plugin:seed', [
                    'plugin' => $module->getStudlyName(),
                ]);
            }
        });

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
}
