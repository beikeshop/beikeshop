<?php

namespace App\Tools\Providers;

use App\Tools\Contracts\RepositoryInterface;
use App\Tools\Laravel\LaravelFileRepository;
use Illuminate\Support\ServiceProvider;

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
