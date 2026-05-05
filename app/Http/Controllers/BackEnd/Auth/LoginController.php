<?php

namespace App\Http\Controllers\BackEnd\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return Inertia::render('BackEnd/Auth/Login');
    }

    public function Adminlogin(Request $request)
    {
        $credentials = $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $remember  = $request->boolean('remember');

        if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            if ($remember) {
                $user = Auth::user();
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
            return redirect()->intended('/admin/dashboard')->with('status', 'Login Successful!');
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Logout Successfull!');
    }
}
