<?php

namespace Beike\Facades\BeikeHttp;

use Illuminate\Support\ServiceProvider;

class BeikeHttpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('beike_http', function () {
            return new Http();
        });
    }
}
