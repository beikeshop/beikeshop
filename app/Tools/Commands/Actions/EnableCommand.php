<?php

namespace App\Tools\Commands\Actions;

use App\Tools\Commands\BaseCommand;

class EnableCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified plugin.';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $status = $module->isDisabled()
            ? '<fg=red;options=bold>Disabled</>'
            : '<fg=green;options=bold>Enabled</>';

        $this->components->task("Enabling <fg=cyan;options=bold>{$module->getName()}</> Plugin, old status: $status", function () use ($module) {
            $module->enable();
        });
    }

    public function getInfo(): string|null
    {
        return 'Disabling plugin ...';
    }
}
