<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PhoneVerificationController extends Controller
{
    protected $smsController;

    public function __construct(SmsController $smsController)
    {
        $this->smsController = $smsController;
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show phone verification form
     */
    public function showVerificationForm(Request $request)
    {
        // Get the phone number from the session
        $phone = session('phone_for_verification');
        
        if (!$phone) {
            return redirect()->route('register')
                ->with('error', 'Phone number not found. Please register again.');
        }
        
        // Check if there's a resend cooldown active
        $resendAvailable = true;
        $resendWaitTime = 0;
        
        $lastSent = Session::get('last_verification_sent');
        if ($lastSent) {
            $cooldownSeconds = 60; // 1 minute cooldown
            $secondsElapsed = Carbon::now()->diffInSeconds(Carbon::parse($lastSent));
            
            if ($secondsElapsed < $cooldownSeconds) {
                $resendAvailable = false;
                $resendWaitTime = $cooldownSeconds - $secondsElapsed;
            }
        }
        
        return view('auth.phone-verify', [
            'phone' => $phone,
            'resendAvailable' => $resendAvailable,
            'resendWaitTime' => $resendWaitTime
        ]);
    }

    /**
     * Send verification code via SMS
     */
    public function sendVerificationCode(Request $request)
    {
        // Get user from session if available, otherwise use request
        $phone = session('phone_for_verification', $request->phone);
        
        if (!$phone) {
            return redirect()->route('register')
                ->with('error', 'Phone number not found. Please register again.');
        }
        
        // Generate a verification code (6 digits)
        $code = random_int(100000, 999999);
        
        // Store the verification code in the verification_codes table
        DB::table('verification_codes')->updateOrInsert(
            ['phone' => $phone],
            [
                'code' => $code,
                'created_at' => Carbon::now()
            ]
        );
        
        // Send SMS with the verification code
        $message = "Your verification code is: $code";
        $this->smsController->sendSingleDestination($phone, $message);
        
        // Store last sent time for cooldown
        Session::put('last_verification_sent', Carbon::now()->toDateTimeString());
        
        // Store the phone number in session if not already there
        if (!session('phone_for_verification')) {
            session(['phone_for_verification' => $phone]);
        }
        
        return redirect()->route('phone.verify')
            ->with('status', 'Verification code has been sent to your phone.');
    }

    /**
     * Resend verification code
     */
    public function resendCode(Request $request)
    {
        $phone = session('phone_for_verification');
        
        if (!$phone) {
            return redirect()->route('register')
                ->with('error', 'Phone number not found. Please register again.');
        }
        
        // Check if there's a cooldown active
        $lastSent = Session::get('last_verification_sent');
        if ($lastSent) {
            $cooldownSeconds = 60; // 1 minute cooldown
            $secondsElapsed = Carbon::now()->diffInSeconds(Carbon::parse($lastSent));
            
            if ($secondsElapsed < $cooldownSeconds) {
                return back()->with('error', 'Please wait ' . ($cooldownSeconds - $secondsElapsed) . ' seconds before requesting a new code.');
            }
        }
        
        // Generate a new verification code and send it
        return $this->sendVerificationCode($request);
    }

    /**
     * Verify the code submitted by user
     */
    public function verifyCode(Request $request)
    {
        $phone = session('phone_for_verification');
        
        if (!$phone) {
            return redirect()->route('register')
                ->with('error', 'Phone number not found. Please register again.');
        }
        
        // Validate the code input
        $request->validate([
            'code' => 'required|array|size:6',
            'code.*' => 'required|numeric|digits:1',
        ]);
        
        // Combine the individual digits into a single code
        $code = implode('', $request->code);
        
        // Check if the code exists and is valid
        $verificationData = DB::table('verification_codes')
            ->where('phone', $phone)
            ->where('code', $code)
            ->first();
        
        if (!$verificationData) {
            return back()->withErrors(['verification_code' => 'The verification code you entered is invalid.']);
        }
        
        // Check if the code is expired (10 minutes)
        if (Carbon::parse($verificationData->created_at)->addMinutes(10)->isPast()) {
            // Delete expired code
            DB::table('verification_codes')->where('phone', $phone)->delete();
            return back()->withErrors(['verification_code' => 'The verification code has expired. Please request a new one.']);
        }
        
        // Mark the user as phone verified
        $user = User::where('phone', $phone)->first();
        
        if ($user) {
            $user->phone_verified_at = Carbon::now();
            $user->save();
            
            // Clean up
            DB::table('verification_codes')->where('phone', $phone)->delete();
            Session::forget('phone_for_verification');
            Session::forget('last_verification_sent');
            
            // Log the user in
            Auth::login($user);
            
            return redirect()->route('home')
                ->with('status', 'Your phone number has been verified successfully!');
        }
        
        return redirect()->route('register')
            ->with('error', 'We could not find your account. Please register again.');
    }
}