<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SmsPasswordResetController extends Controller
{
    protected $smsController;

    public function __construct(SmsController $smsController)
    {
        $this->smsController = $smsController;
    }

    public function showSendCodeForm()
    {
        return view('Auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|string|regex:/^[0-9]{9}$/',
            ], [
                'phone.regex' => 'The phone number must be 9 digits without country code.',
            ]);
    
            // Add the country code
            $phone = '255' . $request->phone;
    
            // Check if user exists with this phone number
            $user = User::where('phone', $phone)->first();
    
            if (!$user) {
                return back()->withErrors(['phone' => 'We could not find a user with that phone number.']);
            }
    
            // Generate a verification code (6 digits)
            $code = random_int(100000, 999999);
    
            // Store the code in the password_reset_tokens table
            $token = Str::random(60);
    
            DB::table('password_reset_tokens')->updateOrInsert(
                ['phone' => $phone],
                [
                    'token' => $token,
                    'code' => $code,
                    'created_at' => Carbon::now()
                ]
            );
    
            // Send SMS with the verification code
            $message = "Your password reset code is: $code";
            $response = $this->smsController->sendSingleDestination($phone, $message);
            
            // Log the SMS response for debugging (optional)
            Log::info('SMS Response: ' . json_encode($response));
    
            // Store the phone in session
            session(['reset_phone' => $phone]);
            
            // Make sure session is saved before redirect
            session()->save();
    
            // Redirect to verification page
            return redirect()->route('password.sms.verify.form')
                ->with('status', 'We have sent your password reset code to your phone!');
                
        } catch (\Exception $e) {
            // Log the error
            Log::error('Password reset error: ' . $e->getMessage());
            
            // Return with error message
            return back()->withErrors(['error' => 'An error occurred while processing your request: ' . $e->getMessage()]);
        }
    }

    public function showVerifyCodeForm()
    {
        $phone = session('reset_phone');
        
        if (!$phone) {
            return redirect()->route('password.sms.request')
                ->with('error', 'Please enter your phone number first.');
        }
        
        return view('Auth.verify-code', ['phone' => $phone]);
    }

    public function verifyCode(Request $request)
    {
        // Get phone from session
        $phone = session('reset_phone');
        
        if (!$phone) {
            return redirect()->route('password.sms.request')
                ->with('error', 'Please enter your phone number first.');
        }
        
        // Validate code
        $request->validate([
            'code' => 'required|array|size:6',
            'code.*' => 'required|numeric|digits:1',
        ]);
        
        // Combine the individual digits into a single code
        $code = implode('', $request->code);

        // Verify the code
        $resetData = DB::table('password_reset_tokens')
            ->where('phone', $phone)
            ->where('code', $code)
            ->first();

        if (!$resetData) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        // Check if the token is expired (1 hour)
        if (Carbon::parse($resetData->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')->where('phone', $phone)->delete();
            return back()->withErrors(['code' => 'Verification code has expired. Please request a new one.']);
        }

        return redirect()->route('password.sms.reset.form', [
            'token' => $resetData->token
        ]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        $phone = session('reset_phone');
        
        if (!$phone || !$token) {
            return redirect()->route('password.sms.request')
                ->with('error', 'Invalid request. Please start the password reset process again.');
        }

        // Verify token exists and is valid
        $resetData = DB::table('password_reset_tokens')
            ->where('phone', $phone)
            ->where('token', $token)
            ->first();

        if (!$resetData) {
            return redirect()->route('password.sms.request')
                ->withErrors(['phone' => 'Invalid token. Please request a new reset link.']);
        }

        return view('Auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $phone = session('reset_phone');
        $token = $request->token;
        
        if (!$phone) {
            return redirect()->route('password.sms.request')
                ->with('error', 'Password reset session expired. Please start again.');
        }

        // Verify the token
        $resetData = DB::table('password_reset_tokens')
            ->where('phone', $phone)
            ->where('token', $token)
            ->first();

        if (!$resetData) {
            return back()->withErrors(['token' => 'Invalid token.']);
        }

        // Find the user with this phone number
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'We could not find a user with that phone number.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->remember_token = Str::random(60);
        $user->save();

        // Delete the reset token
        DB::table('password_reset_tokens')->where('phone', $phone)->delete();
        
        // Clear the session
        session()->forget('reset_phone');

        return redirect()->route('login')->with('status', 'Your password has been reset successfully!');
    }

}
