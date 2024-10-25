<?php

namespace App\Tools\Commands\Make;

use Beike\Repositories\PluginRepo;
use Beike\Repositories\SettingRepo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use App\Tools\Contracts\ActivatorInterface;
use App\Tools\Generators\ModuleGenerator;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new plugin.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $names = $this->argument('name');
        $success = true;

        foreach ($names as $name) {
            $code = Str::snake($name);
            if (PluginRepo::installed($code))
            {
                $this->components->error("Plugin $code is already installed.");
                return 0;
            }
        }

        foreach ($names as $name) {
            $code = with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['plugins'])
                ->setConfig($this->laravel['config'])
                ->setActivator($this->laravel[ActivatorInterface::class])
                ->setConsole($this)
                ->setComponent($this->components)
                ->setForce($this->option('force'))
                ->setType($this->getModuleType())
                ->setActive(!$this->option('disabled'))
                ->setVendor($this->option('author-vendor'))
                ->setAuthor($this->option('author-name'), $this->option('author-email'))
                ->generate();

            if ($code === E_ERROR) {
                $success = false;
            }
        }

        if ($success)
        {
           foreach ($names as $name) {
               $this->install($name);
           }
        }

        return $success ? 0 : E_ERROR;
    }

    /**
     *  install plugin
     *
     * @param $code
     * @return void
     */
    private function install($code): void
    {
        try {

            $this->call('plugin:use', [
                'plugin' => $code,
            ]);

            $code = Str::snake($code);
            $plugin = app('plugin')->getPluginOrFail($code);
            PluginRepo::installPlugin($plugin);

            app('plugin')->getPluginOrFail($code);
            SettingRepo::update('plugin', $code, ['status' => 1]);

        } catch (\Exception $e) {
           dd("Plugin [{$code}] install fail: {$e->getMessage()}");
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names of plugins will be created.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain plugin (without some resources).'],
            ['api', null, InputOption::VALUE_NONE, 'Generate an api plugin.'],
            ['web', null, InputOption::VALUE_NONE, 'Generate a web plugin.'],
            ['disabled', 'd', InputOption::VALUE_NONE, 'Do not enable the plugin at creation.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the plugin already exists.'],
            ['author-name', null, InputOption::VALUE_OPTIONAL, 'Author name.'],
            ['author-email', null, InputOption::VALUE_OPTIONAL, 'Author email.'],
            ['author-vendor', null, InputOption::VALUE_OPTIONAL, 'Author vendor.'],
        ];
    }

    /**
    * Get module type .
    *
    * @return string
    */
    private function getModuleType()
    {
        $isPlain = $this->option('plain');
        $isApi = $this->option('api');

        if ($isPlain && $isApi) {
            return 'web';
        }
        if ($isPlain) {
            return 'plain';
        } elseif ($isApi) {
            return 'api';
        } else {
            return 'web';
        }
    }
}
