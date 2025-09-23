<?php

namespace App\Tools\Commands\Actions;

use App\Tools\Commands\BaseCommand;

class UseCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:use';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the specified plugin.';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->task("Using <fg=cyan;options=bold>{$module->getName()}</> Plugin", function () use ($module) {
            $this->laravel['plugins']->setUsed($module);
        });
    }

    public function getInfo(): string|null
    {
        return 'Using Plugin ...';
    }
}
