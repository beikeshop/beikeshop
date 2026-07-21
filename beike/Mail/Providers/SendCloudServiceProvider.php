<?php

namespace Beike\Mail\Providers;

use Beike\Mail\Transport\SendCloudTransport;
use Illuminate\Mail\MailManager;
use Illuminate\Support\ServiceProvider;

class SendCloudServiceProvider extends ServiceProvider
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
            $mailManager->extend('sendcloud', function (array $config) {
                return new SendCloudTransport(
                    $config['api_user'] ?? '',
                    $config['api_key']  ?? '',
                    $config['endpoint'] ?? 'https://api.sendcloud.net'
                );
            });
        });
    }
}
