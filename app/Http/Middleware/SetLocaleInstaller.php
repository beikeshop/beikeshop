<?php

namespace App\Http\Middleware;

use Beike\Installer\Helpers\EnvironmentManager;
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
        $locale      = $_COOKIE['locale'] ?? 'zh_cn';
        $languageDir = base_path('beike/Installer/Lang');
        $languages   = array_values(array_diff(scandir($languageDir), ['..', '.']));

        (new EnvironmentManager)->getEnvContent();

        if ($locale && in_array($locale, $languages)) {
            App::setLocale($locale);
        } else {
            App::setLocale('zh_cn');
        }

        return $next($request);
    }
}
