<?php

namespace App\Tools\Commands\Publish;

use App\Tools\Commands\BaseCommand;
use App\Tools\Publishing\AssetPublisher;

class PublishCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a plugin\'s assets to the application';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->task("Publishing Assets <fg=cyan;options=bold>{$module->getName()}</> Plugin", function () use ($module) {
            with(new AssetPublisher($module))
                ->setRepository($this->laravel['plugins'])
                ->setConsole($this)
                ->publish();
        });

    }

    public function getInfo(): string|null
    {
        return 'Publishing plugin asset files ...';
    }

}
