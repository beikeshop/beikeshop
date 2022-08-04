<?php

namespace Beike\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function update(Request $request)
    {
        $user = current_user();
        $user->update($request->only('email', 'name', 'password'));

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function local(Request $request)
    {
        $user = current_user();
        $user->update($request->only('local'));

        return redirect()->back();
    }
}
