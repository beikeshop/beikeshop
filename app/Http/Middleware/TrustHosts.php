<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    protected function shouldSpecifyTrustedHosts()
    {
        // 只是在单元测试不加载可信代理，local环境也要加载，因为php artisan serve的时候也要可信代理
        return ! $this->app->runningUnitTests();
    }
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array
     */
    public function hosts()
    {
        return array_merge([
            $this->allSubdomainsOfApplicationUrl(),
        ], config('app.trusted_hosts', []));
    }
}
