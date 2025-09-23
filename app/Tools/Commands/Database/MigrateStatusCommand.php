<?php

namespace App\Tools\Commands\Database;

use App\Tools\Commands\BaseCommand;
use App\Tools\Migrations\Migrator;
use Symfony\Component\Console\Input\InputOption;

class MigrateStatusCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:migrate-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Status for all plugin migrations';

    /**
     * @var \App\Tools\Contracts\RepositoryInterface
     */
    protected $module;

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $path = str_replace(base_path(), '', (new Migrator($module, $this->getLaravel()))->getPath());

        $this->call('migrate:status', [
            '--path'     => $path,
            '--database' => $this->option('database'),
        ]);
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
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
        ];
    }
}
