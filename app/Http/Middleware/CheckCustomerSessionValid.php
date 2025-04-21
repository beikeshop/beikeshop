<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Beike\Models\Customer;
use Illuminate\Support\Str;

class CheckCustomerSessionValid
{
    public function handle($request, Closure $next)
    {
        $customer = current_customer();

        if ($customer) {
            if ($customer->last_password_changed_at && session('login_at') < strtotime($customer->last_password_changed_at)) {
                Auth::guard(Customer::AUTH_GUARD)->logout();

                if (Str::startsWith($request->path(), 'account')) {
                    return redirect(shop_route('login.index'));
                }
            }
        }

        return $next($request);
    }
}

