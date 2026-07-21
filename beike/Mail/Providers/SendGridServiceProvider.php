<?php

namespace Beike\Mail\Providers;

use Beike\Mail\Transport\SendGridTransport;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class SendGridServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * 启动服务
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->resolving(MailManager::class, function (MailManager $mailManager) {
            $mailManager->extend('sendgrid', function (array $config) {
                return new SendGridTransport(
                    $config['api_key']  ?? '',
                    $config['endpoint'] ?? 'https://api.sendgrid.com'
                );
            });
        });
    }
}
