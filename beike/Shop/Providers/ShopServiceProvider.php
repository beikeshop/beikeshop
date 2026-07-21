<?php

namespace Beike\Shop\Providers;

use Beike\Libraries\Tax;
use Beike\Mail\Providers\SendCloudServiceProvider;
use Beike\Mail\Providers\SendGridServiceProvider;
use Beike\Models\Customer;
use Beike\Shop\View\Components\AccountSidebar;
use Beike\Shop\View\Components\Alert;
use Beike\Shop\View\Components\Breadcrumb;
use Beike\Shop\View\Components\NoData;
use Beike\Shop\View\Components\SearchPopover;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\FileViewFinder;

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
            return new Tax;
        });

        // 注册邮件服务提供者
        $this->app->register(SendCloudServiceProvider::class);
        $this->app->register(SendGridServiceProvider::class);
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

        $apiRoute = __DIR__ . '/../../ShopAPI/Routes/api.php';
        if (file_exists($apiRoute)) {
            $this->loadRoutesFrom($apiRoute);
        }

        load_settings();

        $this->registerGuard();
        $this->loadMailConfig();
        $this->registerCDNUrl();

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
            'driver'   => 'session',
            'provider' => 'shop_customer',
        ]);

        Config::set('auth.providers.shop_customer', [
            'driver' => 'eloquent',
            'model'  => Customer::class,
        ]);
    }

    /**
     * 注册上传文件系统
     */
    protected function registerFileSystem()
    {
        Config::set('filesystems.disks.upload', [
            'driver'      => 'local',
            'root'        => public_path('upload'),
            'permissions' => [
                'file' => [
                    'public'  => 0755,
                    'private' => 0755,
                ],
                'dir'  => [
                    'public'  => 0755,
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
        $mailEngine = system_setting('base.mail_engine');
        $storeMail  = system_setting('base.email', '');

        if (empty($mailEngine)) {
            return;
        }

        // 验证邮件配置
        $this->validateMailConfig($mailEngine, $storeMail);

        Config::set('mail.default', $mailEngine);
        Config::set('mail.from.address', $storeMail);
        Config::set('mail.from.name', system_setting('base.store_name', \config('app.name')));

        if ($setting = system_setting('base.smtp')) {
            $setting['transport'] = 'smtp';
            // 如果$setting['username']的值为一个合法的email地址
            if (filter_var($setting['username'], FILTER_VALIDATE_EMAIL)) {
                Config::set('mail.from.address', $setting['username']);
            }
            Config::set('mail.mailers.smtp', $setting);
        } elseif ($setting = system_setting('base.mailgun')) {
            Config::set('services.mailgun', $setting);
        } elseif ($setting = system_setting('base.sendmail')) {
            $setting['transport'] = 'sendmail';
            Config::set('mail.mailers.sendmail', $setting);
        } elseif ($setting = system_setting('base.sendcloud')) {
            $setting['transport'] = 'sendcloud';
            Config::set('mail.mailers.sendcloud', $setting);
        } elseif ($setting = system_setting('base.sendgrid')) {
            $setting['transport'] = 'sendgrid';
            Config::set('mail.mailers.sendgrid', $setting);
        }
    }

    /**
     * 验证邮件配置
     *
     * @param string $mailEngine
     * @param string $storeMail
     * @return void
     */
    protected function validateMailConfig(string $mailEngine, string $storeMail): void
    {
        // 验证发件人地址
        if (empty($storeMail)) {
            \Log::warning('邮件配置警告: 发件人地址未设置，请在后台系统设置中配置邮箱地址');

            return;
        }

        if (! filter_var($storeMail, FILTER_VALIDATE_EMAIL)) {
            \Log::warning("邮件配置警告: 发件人地址格式无效 ({$storeMail})，请在后台系统设置中配置正确的邮箱地址");

            return;
        }

        // 检查第三方邮件服务的特殊要求
        if (in_array($mailEngine, ['sendcloud', 'sendgrid', 'mailgun'])) {
            $exampleDomains = ['example.com', 'test.com', 'localhost', '127.0.0.1'];
            $domain         = substr(strrchr($storeMail, '@'), 1);

            if (in_array($domain, $exampleDomains)) {
                \Log::warning("邮件配置警告: {$mailEngine} 不支持示例域名地址 ({$storeMail})，请配置真实的邮箱地址。建议使用已验证的域名，如 noreply@yourdomain.com");
            }
        }
    }

    /**
     * 从后台获取CDN url 并注册
     *
     * @return void
     */
    protected function registerCDNUrl(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $cdnUrl = system_setting('base.cdn_url');

        if (! $cdnUrl || is_admin()) {
            return;
        }

        config(['app.asset_url' => $cdnUrl]);

        if ($this->app->resolved('url')) {
            $this->app->extend('url', function ($url, $app) {
                $newUrl = new \Illuminate\Routing\UrlGenerator(
                    $app['router']->getRoutes(),
                    $app['request']
                );

                $newUrl->forceRootUrl($app['config']['app.asset_url']);

                return $newUrl;
            });
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
                $paths         = array_merge($customTheme, $paths);
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
            'sidebar'        => AccountSidebar::class,
            'no-data'        => NoData::class,
            'alert'          => Alert::class,
            'breadcrumb'     => Breadcrumb::class,
            'search-popover' => SearchPopover::class,
        ]);
    }
}
