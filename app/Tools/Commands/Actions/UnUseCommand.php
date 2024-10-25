<?php

namespace App\Tools\Commands\Actions;

use App\Tools\Commands\BaseCommand;

class UnUseCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:unuse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forget the used plugin with plugin:use';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->task("Forget Using <fg=cyan;options=bold>{$module->getName()}</> Plugin", function () use ($module) {
            $this->laravel['plugins']->forgetUsed($module);
        });
    }

    public function getInfo(): string|null
    {
        return 'Forget Using Module ...';
    }
}
