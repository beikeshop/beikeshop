<?php

namespace App\Tools\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'plugin:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up plugins folders for first use.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $code = $this->generateModulesFolder();

        return $this->generateAssetsFolder() | $code;
    }

    /**
     * Generate the modules folder.
     */
    public function generateModulesFolder()
    {
        return $this->generateDirectory(
            $this->laravel['plugins']->config('paths.plugins'),
            'Modules directory created successfully',
            'Modules directory already exist'
        );
    }

    /**
     * Generate the assets folder.
     */
    public function generateAssetsFolder()
    {
        return $this->generateDirectory(
            $this->laravel['plugins']->config('paths.assets'),
            'Assets directory created successfully',
            'Assets directory already exist'
        );
    }

    /**
     * Generate the specified directory by given $dir.
     *
     * @param $dir
     * @param $success
     * @param $error
     * @return int
     */
    protected function generateDirectory($dir, $success, $error): int
    {
        if (!$this->laravel['files']->isDirectory($dir)) {
            $this->laravel['files']->makeDirectory($dir, 0755, true, true);

            $this->components->info($success);

            return 0;
        }

        $this->components->error($error);

        return E_ERROR;
    }
}
