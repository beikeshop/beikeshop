<?php

namespace Beike\Shop\Providers;

use Beike\Console\Commands\MakeRootAdminUser;
use Beike\Models\AdminUser;
use Beike\Models\Setting;
use Beike\Admin\View\Components\Filter;
use Beike\Admin\View\Components\Header;
use Beike\Admin\View\Components\Sidebar;
use Beike\Admin\View\Components\Form\Input;
use Beike\Admin\View\Components\Form\InputLocale;
use Beike\Admin\View\Components\Form\SwitchRadio;
use Illuminate\Support\Facades\Config;
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

}
