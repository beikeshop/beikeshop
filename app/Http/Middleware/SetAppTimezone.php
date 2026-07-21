<?php

namespace App\Http\Middleware;

use Closure;

class SetAppTimezone
{
    public function handle($request, Closure $next)
    {
        $timezone = system_setting('base.app_timezone', 'UTC');
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        return $next($request);
    }
}
