<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\SmsController;


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
            if ($user->role || in_array($user->email, ["swedyharuny@gmail.com", "mussatwaha865@gmail.com"])) {
                return redirect()->route('welcome');
            }
            if (!$user->role && !in_array($user->email, ["swedyharuny@gmail.com", "mussatwaha865@gmail.com"])) {
                Auth::logout();
                return back()->with('status', 'You are not assigned to any role! Contact your Employer.');
            }


            return redirect()->route('welcome');
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
