<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Http\RedirectResponse;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
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
