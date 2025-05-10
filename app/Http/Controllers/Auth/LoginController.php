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

            // Check if user has one of the special emails
            $specialEmails = ["swedyharuny@gmail.com", "Mussatwaha865@gmail.com"];
            $isSpecialUser = in_array($user->email, $specialEmails);

            // Only check for phone verification if NOT a special user
            if (!$isSpecialUser && !$user->phone_verified_at) {
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

            // Check user role (special users can bypass this check too if needed)
            if ($user->role || $isSpecialUser) {
                return redirect()->route('welcome');
            }

            if (!$user->role && !$isSpecialUser) {
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
