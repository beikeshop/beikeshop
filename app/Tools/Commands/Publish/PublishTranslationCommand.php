<?php

namespace App\Tools\Commands\Publish;

use App\Tools\Commands\BaseCommand;
use App\Tools\Publishing\LangPublisher;

class PublishTranslationCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:publish-translation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a plugin\'s translations to the application';

    public function executeAction($name): void
    {
        $module = $this->getModuleModel($name);

        $this->components->task("Publishing Translations <fg=cyan;options=bold>{$module->getName()}</> Plugin", function () use ($module) {
            with(new LangPublisher($module))
                ->setRepository($this->laravel['plugins'])
                ->setConsole($this)
                ->publish();
        });
    }

    public function getInfo(): string|null
    {
        return 'Publishing plugin translations ...';
    }
}
