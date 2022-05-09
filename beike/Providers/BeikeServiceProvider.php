<?php

namespace Beike\Providers;

use Beike\Models\AdminUser;
use Beike\Models\Setting;
use Beike\View\Components\Admin\Filter;
use Beike\View\Components\Admin\Header;
use Beike\View\Components\Admin\Sidebar;
use Beike\View\Components\Form\Input;
use Beike\View\Components\Form\InputLocale;
use Beike\View\Components\Form\SwitchRadio;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class BeikeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/shop.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/admin.php');

        $this->mergeConfigFrom(__DIR__.'/../Config/beike.php', 'beike');


        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'beike');

        $this->loadViewComponentsAs('beike', [
            'admin-header' => Header::class,
            'admin-sidebar' => Sidebar::class,
            'admin-filter' => Filter::class,
            'form-input-locale' => InputLocale::class,
            'form-switch' => SwitchRadio::class,
            'form-input' => Input::class,
        ]);

        $this->loadSettings();
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

    protected function registerGuard()
    {
        Config::set('auth.guards.'.AdminUser::AUTH_GUARD, [
            'driver' => 'session',
            'provider' => 'admin_users',
        ]);

        Config::set('auth.providers.admin_users', [
            'driver' => 'eloquent',
            'model' => AdminUser::class,
        ]);
    }

}
