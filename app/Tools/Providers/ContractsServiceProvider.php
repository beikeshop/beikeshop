<?php

namespace App\Tools\Providers;

use Illuminate\Support\ServiceProvider;
use App\Tools\Contracts\RepositoryInterface;
use App\Tools\Laravel\LaravelFileRepository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, LaravelFileRepository::class);
    }
}
