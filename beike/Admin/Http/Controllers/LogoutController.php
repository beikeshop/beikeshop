<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function index(Request $request)
    {
        Auth::guard(AdminUser::AUTH_GUARD)->logout();

        return redirect(admin_route('login.show'));
    }
}
