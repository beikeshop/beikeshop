<?php

namespace App\Tools\Commands\Database;

use App\Tools\Commands\BaseCommand;
use App\Tools\Migrations\Migrator;
use App\Tools\Traits\MigrationLoaderTrait;
use Symfony\Component\Console\Input\InputOption;

class MigrateRollbackCommand extends BaseCommand
{
    use MigrationLoaderTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:migrate-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback the plugins migrations.';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $migrator = new Migrator($module, $this->getLaravel(), $this->option('subpath'));

        $database = $this->option('database');

        if (! empty($database)) {
            $migrator->setDatabase($database);
        }

        $migrated = $migrator->rollback();

        if (count($migrated)) {
            foreach ($migrated as $migration) {
                $this->components->task("Rollback: <info>{$migration}</info>", );
            }

            return;
        }

        $this->components->warn("Nothing to rollback on plugin <fg=cyan;options=bold>{$module->getName()}</>");

    }

    public function getInfo(): string|null
    {
        return null;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'desc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['subpath', null, InputOption::VALUE_OPTIONAL, 'Indicate a subpath for plugins specific migration file'],
        ];
    }
}
