<?php

namespace App\Providers;

use App\Rewrite\Factory;
use Illuminate\Support\ServiceProvider;

class RewriteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('view', function ($app) {
            return new Factory($app['view.engine.resolver'], $app['view.finder'], $app['events']);
        });
    }
}
