<?php

namespace Beike\Admin\Providers;

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

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        $uri = request()->getRequestUri();
        load_settings();

        $adminName = admin_name();
        if (!Str::startsWith($uri, "/{$adminName}")) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        $this->mergeConfigFrom(__DIR__ . '/../../Config/beike.php', 'beike');
        $this->loadViewsFrom(resource_path('/beike/admin/views'), 'admin');

        $this->loadViewComponentsAs('admin', [
            'header' => Header::class,
            'sidebar' => Sidebar::class,
            'filter' => Filter::class,
            'form-input-locale' => InputLocale::class,
            'form-switch' => SwitchRadio::class,
            'form-input' => Input::class,
        ]);

        $this->registerGuard();

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRootAdminUser::class,
            ]);

            $this->publishResources();
        }

        Config::set('filesystems.disks.upload', [
            'driver' => 'local',
            'root' => public_path('upload'),
        ]);

        $this->loadDesignComponents();
    }

    protected function registerGuard()
    {
        Config::set('auth.guards.' . AdminUser::AUTH_GUARD, [
            'driver' => 'session',
            'provider' => 'admin_users',
        ]);

        Config::set('auth.providers.admin_users', [
            'driver' => 'eloquent',
            'model' => AdminUser::class,
        ]);
    }

    protected function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../Database/Seeders/ProductSeeder.php' => database_path('seeders/ProductSeeder.php'),
        ], 'beike-seeders');
    }

    /**
     * 加载首页 page builder 相关组件
     *
     * @throws \Exception
     */
    protected function loadDesignComponents()
    {
        $viewPath = base_path() . '/beike/Admin/View';
        $builderPath = $viewPath . '/DesignBuilders/';

        $builderFolders = glob($builderPath . '*');
        foreach ($builderFolders as $builderFolder) {
            $folderName = basename($builderFolder, '.php');
            $aliasName = Str::snake($folderName);
            $componentName = Str::studly($folderName);
            $classBaseName = "\\Beike\\Admin\\View\\DesignBuilders\\{$componentName}";

            if (!class_exists($classBaseName)) {
                throw new \Exception("请先定义自定义模板类 {$classBaseName}");
            }
            $this->loadViewComponentsAs('editor', [
                $aliasName => $classBaseName
            ]);
        }
    }

}
