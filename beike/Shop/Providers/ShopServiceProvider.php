<?php

namespace Beike\Shop\Providers;

use Beike\Libraries\Tax;
use Beike\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;
use Beike\Shop\View\Components\Alert;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Beike\Shop\View\Components\Breadcrumb;
use Beike\Shop\View\Components\AccountSidebar;
use Beike\Shop\View\Components\NoData;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('tax', function () {
            return new Tax();
        });
    }


    /**
     * @throws \Exception
     */
    public function boot()
    {
        $uri = request()->getRequestUri();
        if (is_installer()) {
            return;
        }
        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop.php');

        load_settings();
        $this->registerGuard();

        if (Str::startsWith($uri, '/admin')) {
            return;
        }

        Config::set('filesystems.disks.upload', [
            'driver' => 'local',
            'root' => public_path('upload'),
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../../Config/beike.php', 'beike');
        $this->loadThemeViewPath();
        $this->loadComponents();
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

    protected function loadThemeViewPath()
    {
        $this->app->singleton('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];
            if ($theme = system_setting('base.theme')) {
                $customTheme[] = base_path("themes/{$theme}");
                $paths = array_merge($customTheme, $paths);
            }
            return new FileViewFinder($app['files'], $paths);
        });
    }

    protected function loadComponents()
    {
        $this->loadViewComponentsAs('shop', [
            'sidebar' => AccountSidebar::class,
            'no-data' => NoData::class,
            'alert' => Alert::class,
            'breadcrumb' => Breadcrumb::class
        ]);
    }
}
