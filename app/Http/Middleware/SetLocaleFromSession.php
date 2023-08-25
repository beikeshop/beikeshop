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
    public function handle(Request $request, Closure $next): mixed
    {
        $requestLocale = $request->header('locale');
        if (empty($requestLocale)) {
            $requestLocale = $request->get('locale');
        }

        $sessionLocale = session('locale');

        $locale = $requestLocale ?: $sessionLocale;
        if ($locale && in_array($locale, languages()->toArray())) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        } else {
            $configLocale = system_setting('base.locale');
            App::setLocale($configLocale);
            session(['locale' => $configLocale]);
        }

        return $next($request);
    }
}
