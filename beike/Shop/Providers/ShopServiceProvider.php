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
        $this->loadMailConfig();

        if (Str::startsWith($uri, '/admin')) {
            return;
        }

        $this->registerFileSystem();
        $this->mergeConfigFrom(__DIR__ . '/../../Config/beike.php', 'beike');
        $this->loadThemeViewPath();
        $this->loadComponents();
    }


    /**
     * 注册前端客户
     */
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


    /**
     * 注册上传文件系统
     */
    protected function registerFileSystem()
    {
        Config::set('filesystems.disks.upload', [
            'driver' => 'local',
            'root' => public_path('upload'),
            'permissions' => [
                'file' => [
                    'public' => 0755,
                    'private' => 0755,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0755,
                ],
            ],
        ]);
    }


    /**
     * 加载邮件配置, 从后台 mail 取值, 并覆盖到  config/mail 和 config/services
     */
    protected function loadMailConfig()
    {
        $mailEngine = system_setting('base.mail_engine', 'smtp');
        $storeMail = system_setting('base.email', '');

        Config::set('mail.default', $mailEngine);
        Config::set('mail.from.address', $storeMail);
        Config::set('mail.from.name', \config('app.name'));

        $smtpSetting = system_setting('base.smtp');
        if ($smtpSetting) {
            $smtpSetting['transport'] = 'smtp';
            Config::set('mail.mailers.smtp', $smtpSetting);
        }
    }


    /**
     * 加载主体模板路径
     */
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


    /**
     * 加载视图组件
     */
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
