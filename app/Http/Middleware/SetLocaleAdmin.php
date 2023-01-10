<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $currentLocale = current_user()->locale ?? 'zh_cn';
        if (in_array($currentLocale, admin_languages())) {
            App::setLocale($currentLocale);
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
