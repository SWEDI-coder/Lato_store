<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\SmsController;
use App\Models\UserActivity;
use App\Models\UserSession;


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
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->role || in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"])) {
                return redirect()->route('dashboard');
            }
            if (!$user->role && !in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"])) {
                Auth::logout();
                return back()->with('status', 'You are not assigned to any role! Contact your Employer.');
            }


            $request->session()->regenerate();

            // Create user session record
            UserSession::create([
                'user_id' => $user->id,
                'session_id' => session()->getId(),
                'login_at' => now(),
                'last_activity' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Update last activity using DB update to ensure it works
            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_activity' => now()]);

            // Log activity
            UserActivity::log('login', 'User logged in');

            return redirect()->route('dashboard');
        }

        return back()->with('status', 'Invalid login details');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Update session record
            UserSession::where('user_id', $user->id)
                ->where('session_id', session()->getId())
                ->update(['logout_at' => now()]);

            UserActivity::log('logout', 'User logged out');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
