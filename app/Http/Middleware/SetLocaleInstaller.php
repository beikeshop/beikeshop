<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleInstaller
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
        $languageDir = base_path('beike/Installer/Lang');
        $languages = array_values(array_diff(scandir($languageDir), array('..', '.')));

        if ($sessionLocale && in_array($sessionLocale, $languages)) {
            App::setLocale($sessionLocale);
        } else {
            App::setLocale('en');
        }
        return $next($request);
    }
}
