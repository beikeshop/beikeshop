<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('admin.pages.login.login');
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
