<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role || in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"])) {
                return redirect()->route('login');
            }
            if (!$user->role && !in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"])) {
                Auth::logout();
                return back()->with('status', 'You are not assigned to any role! Contact your Employer.');
            }


            return redirect()->route('login');
        }

        return back()->with('status', 'Invalid login details');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
