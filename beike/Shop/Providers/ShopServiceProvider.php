<?php

namespace Beike\Shop\Providers;

use Beike\Models\Customer;
use Beike\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Beike\Shop\Repositories\CategoryRepo;

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
        $this->loadShareView();
        $this->registerGuard();
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
        config(['global' => $settings]);
    }

    protected function loadShareView()
    {
        $menuCategories = CategoryRepo::getTwoLevelCategories();
        View::share('categories', $menuCategories);
    }

    protected function registerGuard()
    {
        Config::set('auth.guards.'.Customer::AUTH_GUARD, [
            'driver' => 'session',
            'provider' => 'shop_customer',
        ]);

        Config::set('auth.providers.shop_customer', [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]);
    }
}
