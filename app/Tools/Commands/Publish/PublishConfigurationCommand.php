<?php

namespace App\Tools\Commands\Publish;

use Illuminate\Support\Str;
use App\Tools\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputOption;

class PublishConfigurationCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:publish-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a plugin\'s config files to the application';

    public function executeAction($name): void
    {
        $this->call('vendor:publish', [
            '--provider' => $this->getServiceProviderForModule($name),
            '--force'    => $this->option('force'),
            '--tag'      => ['config'],
        ]);
    }

    public function getInfo(): string|null
    {
        return 'Publishing plugin config files ...';
    }

    /**
     * @param string $module
     * @return string
     */
    private function getServiceProviderForModule($module): string
    {
        $namespace  = $this->laravel['config']->get('plugins.namespace');
        $studlyName = Str::studly($module);
        $provider   = $this->laravel['config']->get('plugins.paths.generator.provider.path');
        $provider   = str_replace('/', '\\', $provider);

        return "$namespace\\$studlyName\\$provider\\{$studlyName}ServiceProvider";
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['--force', '-f', InputOption::VALUE_NONE, 'Force the publishing of config files'],
        ];
    }
}
