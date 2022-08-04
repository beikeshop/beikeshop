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
        $currentLocale = current_user()->locale;
        if (in_array($currentLocale, languages()->toArray())) {
            App::setLocale($currentLocale);
        } else {
            App::setLocale('en');
        }
        return $next($request);
    }
}
