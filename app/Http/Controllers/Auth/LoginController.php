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
            $user = Auth::user();
            
            // Check if phone is verified
            if (!$user->phone_verified_at) {
                Auth::logout();
                
                // Store the phone number in session for verification
                session(['phone_for_verification' => $user->phone]);
                
                // Generate and send a new verification code
                $smsController = app()->make(SmsController::class);
                $code = random_int(100000, 999999);
                
                // Store the verification code
                DB::table('verification_codes')->updateOrInsert(
                    ['phone' => $user->phone],
                    [
                        'code' => $code,
                        'created_at' => Carbon::now()
                    ]
                );
                
                // Send SMS with the verification code
                $message = "Your verification code is: $code";
                $smsController->sendSingleDestination($user->phone, $message);
                
                return redirect()->route('phone.verify')
                    ->with('status', 'Please verify your phone number before logging in.');
            }
            
            $request->session()->regenerate();
    
            if ($user->role || in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"])) {
                return redirect()->route('welcome');
            }
            
            if (!$user->role && !in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"])) {
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
