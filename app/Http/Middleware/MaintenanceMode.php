<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MaintenanceMode
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
        // route name for ignore
        $ignores = [
            'shop.lang.switch',
        ];
        $routeName = Route::current()->getName();
        if (!in_array($routeName, $ignores)) {
            if (system_setting('base.maintenance_mode') && !current_user()) {
                return response()->view('maintenance', []);
            }
        }

        return $next($request);
    }
}
