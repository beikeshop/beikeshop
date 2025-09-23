<?php

namespace App\Tools\Commands\Publish;

use App\Tools\Commands\BaseCommand;
use App\Tools\Migrations\Migrator;
use App\Tools\Publishing\MigrationPublisher;

class PublishMigrationCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:publish-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a plugin's migrations to the application";

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->task("Publishing Migration <fg=cyan;options=bold>{$module->getName()}</> Plugin", function () use ($module) {
            with(new MigrationPublisher(new Migrator($module, $this->getLaravel())))
                ->setRepository($this->laravel['plugins'])
                ->setConsole($this)
                ->publish();
        });
    }

    public function getInfo(): string|null
    {
        return 'Publishing plugin migrations ...';
    }
}
