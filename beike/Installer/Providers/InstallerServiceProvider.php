<?php

namespace Beike\Installer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;


class InstallerServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/installer.php');

        $uri = request()->getRequestUri();
        if (!Str::startsWith($uri, "/installer")) {
            return;
        }

        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'installer');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'installer');

        $pathInstaller = base_path('beike/installer');
        $this->loadTranslationsFrom("{$pathInstaller}/Lang", 'installer');
    }
}
