<?php

namespace App\Tools\Commands\Database;

use App\Tools\Commands\BaseCommand;
use App\Tools\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the migrations from the specified plugin or from all plugins.';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->task("Running Migration <fg=cyan;options=bold>{$module->getName()}</> Plugin", function () use ($module) {
            $path = str_replace(base_path(), '', (new Migrator($module, $this->getLaravel()))->getPath());

            if ($this->option('subpath')) {
                $path = $path . "/" . $this->option("subpath");
            }

            $this->call('migrate', [
                '--path'     => $path,
                '--database' => $this->option('database'),
                '--pretend'  => $this->option('pretend'),
                '--force'    => $this->option('force'),
            ]);

            if ($this->option('seed')) {
                $this->call('plugin:seed', ['module' => $module->getName(), '--force' => $this->option('force')]);
            }
        });

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
            ['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.'],
            ['subpath', null, InputOption::VALUE_OPTIONAL, 'Indicate a subpath to run your migrations from'],
        ];
    }
}
