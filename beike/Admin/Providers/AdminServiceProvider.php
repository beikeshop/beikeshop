<?php
/**
 * AdminUserRepo.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-08 08:08:08
 * @modified   2022-08-08 08:08:08
 */

namespace Beike\Admin\Providers;

use Beike\Admin\View\Components\Alert;
use Beike\Admin\View\Components\Filter;
use Beike\Admin\View\Components\Form\Image;
use Beike\Admin\View\Components\Form\Input;
use Beike\Admin\View\Components\Form\InputLocale;
use Beike\Admin\View\Components\Form\RichText;
use Beike\Admin\View\Components\Form\Select;
use Beike\Admin\View\Components\Form\SwitchRadio;
use Beike\Admin\View\Components\Form\Textarea;
use Beike\Admin\View\Components\Header;
use Beike\Admin\View\Components\NoData;
use Beike\Admin\View\Components\Sidebar;
use Beike\Console\Commands\AddCountryContinent;
use Beike\Console\Commands\ChangeRootPassword;
use Beike\Console\Commands\FetchCurrencyRate;
use Beike\Console\Commands\GenerateDatabaseDict;
use Beike\Console\Commands\GenerateSitemap;
use Beike\Console\Commands\MakeRootAdminUser;
use Beike\Console\Commands\MigrateFromOpenCart;
use Beike\Console\Commands\ProcessOrder;
use Beike\Models\AdminUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @throws \Exception
     */
    public function boot()
    {
        $uri = request()->getRequestUri();
        if (is_installer()) {
            return;
        }

        $this->loadCommands();
        $this->publishResources();

        load_settings();
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        $this->registerGuard();

        $adminName = admin_name();
        if (! Str::startsWith($uri, "/{$adminName}")) {
            return;
        }

        $this->mergeConfigFrom(__DIR__ . '/../../Config/beike.php', 'beike');
        $this->loadViewsFrom(resource_path('/beike/admin/views'), 'admin');
        $this->loadThemeViewPath();

        $this->app->booted(function () {
            $this->loadShareViewData();
        });

        $this->loadAdminViewComponents();

        Config::set('filesystems.disks.catalog', [
            'driver' => 'local',
            'root'   => public_path('catalog'),
        ]);

        $this->loadDesignComponents();
    }

    /**
     * 加载后台命令行脚本
     */
    protected function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddCountryContinent::class,
                ChangeRootPassword::class,
                FetchCurrencyRate::class,
                GenerateDatabaseDict::class,
                GenerateSitemap::class,
                MakeRootAdminUser::class,
                MigrateFromOpenCart::class,
                ProcessOrder::class,
            ]);
        }
    }

    /**
     * 注册后台用户 guard
     */
    protected function registerGuard()
    {
        Config::set('auth.guards.' . AdminUser::AUTH_GUARD, [
            'driver'   => 'session',
            'provider' => 'admin_users',
        ]);

        Config::set('auth.providers.admin_users', [
            'driver' => 'eloquent',
            'model'  => AdminUser::class,
        ]);
    }

    /**
     * 加载主题模板, 用于装修预览
     */
    protected function loadThemeViewPath()
    {
        $this->app->singleton('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];
            if ($theme = system_setting('base.theme')) {
                $customTheme[] = base_path("themes/{$theme}");
                $paths         = array_merge($customTheme, $paths);
            }

            return new FileViewFinder($app['files'], $paths);
        });
    }

    /**
     * 后台UI组件
     */
    protected function loadAdminViewComponents()
    {
        $this->loadViewComponentsAs('admin', [
            'header'            => Header::class,
            'sidebar'           => Sidebar::class,
            'filter'            => Filter::class,
            'alert'             => Alert::class,
            'form-input-locale' => InputLocale::class,
            'form-switch'       => SwitchRadio::class,
            'form-input'        => Input::class,
            'form-select'       => Select::class,
            'form-image'        => Image::class,
            'form-textarea'     => Textarea::class,
            'form-rich-text'    => RichText::class,
            'no-data'           => NoData::class,
        ]);
    }

    /**
     * seeder 数据
     */
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
        $viewPath    = base_path() . '/beike/Admin/View';
        $builderPath = $viewPath . '/DesignBuilders/';

        $builders = glob($builderPath . '*');
        foreach ($builders as $builder) {
            $builderName   = basename($builder, '.php');
            $aliasName     = Str::snake($builderName);
            $componentName = Str::studly($builderName);
            $classBaseName = "\\Beike\\Admin\\View\\DesignBuilders\\{$componentName}";

            if (! class_exists($classBaseName)) {
                throw new \Exception("请先定义自定义模板类 {$classBaseName}");
            }
            $this->loadViewComponentsAs('editor', [
                $aliasName => $classBaseName,
            ]);
        }
    }

    /**
     * 后台公共数据
     */
    protected function loadShareViewData()
    {
        View::share('languages', languages());
        View::share('admin_base_url', admin_route('home.index'));
        View::share('shop_base_url', shop_route('home.index'));
    }
}
