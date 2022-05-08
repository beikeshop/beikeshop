<?php

namespace Beike\Providers;

use Beike\View\Components\Admin\Filter;
use Beike\View\Components\Admin\Header;
use Beike\View\Components\Admin\Sidebar;
use Illuminate\Support\ServiceProvider;

class BeikeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/admin.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'beike');

        $this->loadViewComponentsAs('beike', [
            'admin-header' => Header::class,
            'admin-sidebar' => Sidebar::class,
            'admin-filter' => Filter::class,
        ]);
    }

}
