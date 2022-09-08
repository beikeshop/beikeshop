<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Beike\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        if (auth(AdminUser::AUTH_GUARD)->check()) {
            return redirect()->back();
        }
        return view('admin::pages.login.login', \request()->only('admin_email', 'admin_password'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (auth(AdminUser::AUTH_GUARD)->attempt($credentials)) {
            return redirect(admin_route('home.index'));
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }
}
