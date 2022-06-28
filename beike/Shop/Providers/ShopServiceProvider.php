<?php

namespace Beike\Shop\Providers;

use Beike\Models\Customer;
use Beike\Models\Setting;
use Beike\Repositories\CategoryRepo;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ShopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $uri = request()->getRequestUri();

        if (Str::startsWith($uri, '/admin')) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop.php');
        $this->mergeConfigFrom(__DIR__ . '/../../Config/beike.php', 'beike');
        $this->loadSettings();
        $this->registerGuard();

        $this->app->booted(function () {
            $this->loadShareViewData();
        });
    }

    protected function loadSettings()
    {
        $settings = Setting::all(['name', 'value', 'json'])
            ->keyBy('name')
            ->transform(function ($setting) {
                if ($setting->json) {
                    return \json_decode($setting->value, true);
                }
                return $setting->value;
            })
            ->toArray();
        config(['bk' => $settings]);
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
        $menuCategories = CategoryRepo::getTwoLevelCategories();
        View::share('categories', $menuCategories);
    }
}
