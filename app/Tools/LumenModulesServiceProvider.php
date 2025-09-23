<?php

namespace App\Tools;

use App\Tools\ModulesServiceProvider;
use App\Tools\Support\Stub;

class LumenModulesServiceProvider extends ModulesServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->setupStubPath();
    }

    /**
     * Register all plugins.
     */
    public function register()
    {
        $this->registerNamespaces();
        $this->registerServices();
        $this->registerModules();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        Stub::setBasePath(__DIR__ . '/Commands/stubs');

        if (app('plugins')->config('stubs.enabled') === true) {
            Stub::setBasePath(app('plugins')->config('stubs.path'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices()
    {
        $this->app->singleton(\App\Tools\Contracts\RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('plugins.paths.modules');

            return new \App\Tools\Lumen\LumenFileRepository($app, $path);
        });
        $this->app->singleton(\App\Tools\Contracts\ActivatorInterface::class, function ($app) {
            $activator = $app['config']->get('plugins.activator');
            $class = $app['config']->get('plugins.activators.' . $activator)['class'];

            return new $class($app);
        });
        $this->app->alias(\App\Tools\Contracts\RepositoryInterface::class, 'modules');
    }
}
