<?php

namespace App\Tools\Lumen;

use App\Tools\Module as BaseModule;
use Illuminate\Support\Str;

class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath(): string
    {
        return Str::replaceLast('services.php', $this->getSnakeName() . '_module.php', $this->app->basePath('storage/app/') . 'services.php');
    }

    /**
     * {@inheritdoc}
     */
    public function registerProviders(): void
    {
        foreach ($this->get('providers', []) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerAliases(): void {}
}
