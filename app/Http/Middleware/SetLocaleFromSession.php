<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromSession
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
        $sessionLocale = session('locale');
        if ($sessionLocale && in_array($sessionLocale, languages()->toArray())) {
            App::setLocale($sessionLocale);
        } else {
            $configLocale = system_setting('base.locale');
            App::setLocale($configLocale);
            session(['locale' => $configLocale]);
        }
        return $next($request);
    }
}
