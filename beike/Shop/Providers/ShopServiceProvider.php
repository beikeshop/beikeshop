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

        $this->loadSettings();

        if (Str::startsWith($uri, '/admin')) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop.php');
        $this->mergeConfigFrom(__DIR__ . '/../../Config/beike.php', 'beike');
        $this->registerGuard();

        $this->app->booted(function () {
            $this->loadShareViewData();
        });
    }

    protected function loadSettings()
    {
        $settings = Setting::all(['type', 'space', 'name', 'value', 'json'])
            ->groupBy('space');

        $result = [];
        foreach ($settings as $space => $groupSettings) {
            $space = $space ?: 'system';
            foreach ($groupSettings as $groupSetting) {
                $name = $groupSetting->name;
                $value = $groupSetting->value;
                if ($groupSetting->json) {

                    $result[$space][$name] = json_encode($value);
                } else {
                    $result[$space][$name] = $value;
                }
            }
        }
        config(['bk' => $result]);
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
