<?php

namespace App\Tools\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

use function Laravel\Prompts\multiselect;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command implements PromptsForMissingInput
{
    public const ALL = 'All';

    /**
     * Create a new console command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->getDefinition()->addOption(new InputOption(
            strtolower(self::ALL),
            'a',
            InputOption::VALUE_NONE,
            'Check all Plugins',
        ));

        $this->getDefinition()->addArgument(new InputArgument(
            'plugin',
            InputArgument::IS_ARRAY,
            'The name of plugin will be used.',
        ));
    }

    abstract public function executeAction($name);

    public function getInfo(): string|null
    {
        return null;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! is_null($info = $this->getInfo())) {
            $this->components->info($info);
        }

        $modules = (array) $this->argument('plugin');

        foreach ($modules as $module) {
            try {
                $this->executeAction($module);
            } catch (\Exception $e) {
                $this->components->error($e->getMessage());
            }
        }
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $modules = $this->hasOption('direction')
            ? array_keys($this->laravel['plugins']->getOrdered($input->hasOption('direction')))
            : array_keys($this->laravel['plugins']->all());

        if ($input->getOption(strtolower(self::ALL))) {
            $input->setArgument('plugin', $modules);

            return;
        }

        if (! empty($input->getArgument('plugin'))) {
            return;
        }

        $selected_item = multiselect(
            label   : 'Select Plugins',
            options : [
                self::ALL,
                ...$modules,
            ],
            required: 'You must select at least one plugin',
        );

        $input->setArgument(
            'plugin',
            value: in_array(self::ALL, $selected_item)
                ? $modules
                : $selected_item
        );
    }

    protected function getModuleModel($name)
    {
        return $name instanceof \App\Tools\Module
            ? $name
            : $this->laravel['plugins']->findOrFail($name);
    }

}
