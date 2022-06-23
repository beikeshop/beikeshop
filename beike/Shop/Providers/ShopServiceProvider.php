<?php

namespace Beike\Shop\Providers;

use Beike\Models\Setting;
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
}
