<?php

namespace App\Tools;

use App\Tools\ModulesServiceProvider;
use Composer\InstalledVersions;
use Illuminate\Foundation\Console\AboutCommand;
use App\Tools\Contracts\RepositoryInterface;
use App\Tools\Exceptions\InvalidActivatorClass;
use App\Tools\Support\Stub;

class LaravelModulesServiceProvider extends ModulesServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerModules();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
        $this->mergeConfigFrom(base_path('config/tools.php'), 'plugins');
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $path = $this->app['config']->get('plugins.stubs.path') ?? __DIR__ . '/Commands/stubs';
        Stub::setBasePath($path);

        $this->app->booted(function ($app) {
            /** @var RepositoryInterface $moduleRepository */
            $moduleRepository = $app[RepositoryInterface::class];
            if ($moduleRepository->config('stubs.enabled') === true) {
                Stub::setBasePath($moduleRepository->config('stubs.path'));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices()
    {
        $this->app->singleton(\App\Tools\Contracts\RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('plugins.paths.plugins');

            return new \App\Tools\Laravel\LaravelFileRepository($app, $path);
        });
        $this->app->singleton(\App\Tools\Contracts\ActivatorInterface::class, function ($app) {
            $activator = $app['config']->get('plugins.activator');
            $class = $app['config']->get('plugins.activators.' . $activator)['class'];

            if ($class === null) {
                throw InvalidActivatorClass::missingConfig();
            }

            return new $class($app);
        });
        $this->app->alias(\App\Tools\Contracts\RepositoryInterface::class, 'plugins');
    }
}
