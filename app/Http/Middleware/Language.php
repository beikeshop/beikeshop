<?php

namespace App\Http\Middleware;

use Beike\Repositories\LanguageRepo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session()->has('locale') AND array_key_exists(Session()->get('locale'), LanguageRepo::all()->where('status', true)->pluck('code'))) {
            App::setLocale(Session()->get('locale'));
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            App::setLocale(system_setting('base.locale'));
        }
        return $next($request);
    }
}
