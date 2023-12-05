<?php

namespace App\Http\Middleware;

use Beike\Models\Customer;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopAuthenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     * @param string[] ...$guards
     * @return mixed
     *
     * @throws AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        $customer = current_customer();
        if ($customer->active != 1 || $customer->status != 'approved') {
            Auth::guard(Customer::AUTH_GUARD)->logout();

            return redirect(shop_route('login.index'));
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return shop_route('login.index');
        }
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param Request $request
     * @param array   $guards
     * @return void
     *
     * @throws AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            trans('common.unauthenticated'), $guards, $this->redirectTo($request)
        );
    }
}
