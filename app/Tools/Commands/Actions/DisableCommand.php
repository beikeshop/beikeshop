<?php

namespace App\Tools\Commands\Actions;

use App\Tools\Commands\BaseCommand;

class DisableCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:disable';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'plugin:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable an array of plugins.';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $status = $module->isDisabled()
            ? '<fg=red;options=bold>Disabled</>'
            : '<fg=green;options=bold>Enabled</>';

        $this->components->task("Disabling <fg=cyan;options=bold>{$module->getName()}</> Plugin, old status: $status", function () use ($module) {
            $module->disable();
        });
    }

    public function getInfo(): string|null
    {
        return 'Disabling plugin ...';
    }
}
