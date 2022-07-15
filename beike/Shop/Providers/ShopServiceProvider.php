<?php

namespace Beike\Shop\Providers;

use Beike\Models\Customer;
use Illuminate\Support\Str;
use Beike\Repositories\CategoryRepo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Beike\Shop\View\Components\AccountSidebar;
use Illuminate\View\FileViewFinder;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        $uri = request()->getRequestUri();
        load_settings();

        if (Str::startsWith($uri, '/admin')) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop.php');
        $this->mergeConfigFrom(__DIR__ . '/../../Config/beike.php', 'beike');
        $this->registerGuard();

        $this->app->bind('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];
            $customTheme[] = base_path('themes/black');
            $paths = array_merge($customTheme, $paths);
            return new FileViewFinder($app['files'], $paths);
        });

        $this->app->booted(function () {
            $this->loadShareViewData();
        });

        $this->loadViewComponentsAs('shop', [
            'sidebar' => AccountSidebar::class,
        ]);
    }

    protected function registerGuard()
    {
        Config::set('auth.guards.' . Customer::AUTH_GUARD, [
            'driver' => 'session',
            'provider' => 'shop_customer',
        ]);

        Config::set('auth.providers.shop_customer', [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]);
    }

    protected function loadShareViewData()
    {
        View::share('languages', languages());

        $menuCategories = CategoryRepo::getTwoLevelCategories();
        View::share('categories', $menuCategories);

        View::share('shop_base_url', shop_route('home.index'));
    }
}
