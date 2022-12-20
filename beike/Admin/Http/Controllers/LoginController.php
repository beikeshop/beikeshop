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
        $validator =Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (auth(AdminUser::AUTH_GUARD)->attempt($validator->validated())) {
            return redirect(admin_route('home.index'));
        }

        return redirect()->back()->with(['error' => 'Invalid credentials'])->withInput();
    }
}
