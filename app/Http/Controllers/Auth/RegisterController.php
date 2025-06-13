<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserActivity;
use App\Models\UserSession;

class RegisterController extends Controller
{
    protected $smsController;

    public function __construct(SmsController $smsController)
    {
        $this->smsController = $smsController;
    }

    public function register()
    {
        // Check if user is already logged in using Auth facade
        if (Auth::check()) {
            return redirect()->route('welcome');
        }

        return view('Auth.register');
    }

    public function showAddPhoneForm()
    {
        $user = Auth::user();

        // If user already has a phone, redirect to verification instead
        if ($user->phone) {
            session(['phone_for_verification' => $user->phone]);
            return redirect()->route('phone.verify');
        }

        return view('auth.add-phone');
    }

    public function addPhoneToAccount(Request $request)
    {
        $user = Auth::user();

        // Validate the phone
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^[0-9]{9}$/|unique:users,phone',
        ], [
            'phone.regex' => 'The phone number must be 9 digits without country code.',
            'phone.unique' => 'This phone number is already associated with another account.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Add country code
        $fullPhone = '255' . $request->phone;

        // Update user using update method instead of save
        User::where('id', $user->id)->update([
            'phone' => $fullPhone,
            'status' => 'Not Verified'
        ]);

        // Refresh user from database
        $user = User::find($user->id);

        // Store phone for verification and send code
        session(['phone_for_verification' => $fullPhone]);
        $this->sendVerificationCode($fullPhone);

        // Redirect to verification
        return redirect()->route('phone.verify')
            ->with('status', 'Phone number added. Please verify it now.');
    }

    public function fetch_employees(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $isDeveloper = $user->email === 'swedyharuny@gmail.com';
        $isCEO = $user->role === 'CEO';
        $isDirector = $user->role === 'Director';
        $isManager = $user->role === 'Manager';
        $isSalesman = $user->role === 'Salesman';

        if (!$user->role && !$isDeveloper) {
            return redirect()->route('login');
        }

        $search = $request->get('search');

        $mainQuery = User::query();

        // Apply search filters
        if ($search) {
            $mainQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($isDeveloper) {
            // swedyharuny@gmail.com can see all employees
            // No additional filters needed
        } else if ($isCEO) {
            // CEOs can see all employees except swedyharuny@gmail.com
            $mainQuery->where('email', '!=', 'swedyharuny@gmail.com');
        } else if ($isDirector) {
            // Directors can see all employees except swedyharuny@gmail.com
            $mainQuery->where('email', '!=', 'swedyharuny@gmail.com');
        } else if ($isManager) {
            // Managers can see all employees except Directors and swedyharuny@gmail.com
            $mainQuery->where('email', '!=', 'swedyharuny@gmail.com')
                ->where(function ($q) {
                    $q->where('role', '!=', 'Director')
                        ->orWhereNull('role');
                });
        } else if ($isSalesman) {
            $mainQuery->where('email', '!=', 'swedyharuny@gmail.com')
                ->where(function ($q) {
                    $q->where('role', '!=', 'Director')
                        ->where('role', '!=', 'Manager')
                        ->orWhereNull('role');
                });
        } else {
            // All other users can only see themselves
            $mainQuery->where('id', $user->id);
        }

        $query = clone $mainQuery;

        // Get the employees based on permissions
        $employees = $query->get();

        // Create a separate query for counting that excludes the special admin
        $countQuery = clone $mainQuery;
        $countQuery->where('email', '!=', 'swedyharuny@gmail.com');

        // Get employees for counting (excluding special admin)
        $employeesForCounting = $countQuery->get();

        // Count totals excluding the special admin
        $total_employees = $employeesForCounting->count();
        $employees_male_count = $employeesForCounting->where('gender', 'Male')->count();
        $employees_female_count = $employeesForCounting->where('gender', 'Female')->count();

        return response()->json([
            'items' => $employees,
            'counts' => [
                'employees_male_count' => $employees_male_count,
                'employees_female_count' => $employees_female_count,
                'total_employees' => $total_employees,
            ]
        ]);
    }

    public function store_user(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{9}$/|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.regex' => 'The phone number must be 9 digits without country code.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Add the country code to the phone number
            $fullPhone = '255' . $request->phone;

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $fullPhone,
                'password' => Hash::make($request->password),
                'status' => 'Not Verified',
                'role' => 'Cashier',
                'hire_date' => now(),
                'is_active' => true,  // Add this line
                'last_activity' => now()  // Add this line
            ]);

            // Store the phone number in session for verification
            session(['phone_for_verification' => $fullPhone]);

            // Generate and send verification code
            // $this->sendVerificationCode($fullPhone);

            $specialEmails = ["swedyharuny@gmail.com", "mussatwaha865@gmail.com"];
            $isSpecialUser = in_array($user->email, $specialEmails);

            // Check if this user is one of the admin users or has a role
            if ($isSpecialUser) {
                // Auto-login the user using Auth facade
                Auth::login($user);

                // Create user session record
                UserSession::create([
                    'user_id' => $user->id,
                    'session_id' => session()->getId(),
                    'login_at' => now(),
                    'last_activity' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);

                UserActivity::log('register', 'User registered');
                return redirect()->route('welcome')->with('success', 'Registration successful! Welcome to your account.');
            } else {
                // For regular users, redirect to login page with appropriate message
                return redirect()->route('login')->with('status', 'Registration successful! You are not assigned to any Role yet. Contact your Employer.');
            }

            // Redirect to phone verification page
            // return redirect()->route('phone.verify')
            //     ->with('status', 'Account created successfully! Please verify your phone number.');
        } catch (\Exception $e) {
            // Handle any exceptions that might occur
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. ' . $e->getMessage()]);
        }
    }

    protected function sendVerificationCode($phone)
    {
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

        // Store last sent time for cooldown
        session(['last_verification_sent' => Carbon::now()->toDateTimeString()]);

        // Send SMS with the verification code
        $message = "Your verification code is: $code";
        return $this->smsController->sendSingleDestination($phone, $message);
    }

    public function showVerificationForm()
    {
        // Get the phone number from the session
        $phone = session('phone_for_verification');

        // If no phone in session but user is logged in, check user's phone
        if (!$phone && Auth::check()) {
            $user = Auth::user();

            // If user has no phone, redirect to add phone form
            if (!$user->phone) {
                return redirect()->route('phone.add');
            }

            $phone = $user->phone;
            session(['phone_for_verification' => $phone]);
        } else if (!$phone) {
            return redirect()->route('register')
                ->with('error', 'Phone number not found. Please register again.');
        }

        // Check if there's a resend cooldown active
        $resendAvailable = true;
        $resendWaitTime = 0;

        $lastSent = session('last_verification_sent');
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

    public function resendCode()
    {
        $phone = session('phone_for_verification');

        // If no phone in session but user is logged in, use user's phone
        if (!$phone && Auth::check()) {
            $user = Auth::user();

            // If user has no phone, redirect to add phone form
            if (!$user->phone) {
                return redirect()->route('phone.add')
                    ->with('error', 'You need to add a phone number first.');
            }

            $phone = $user->phone;
            session(['phone_for_verification' => $phone]);
        } else if (!$phone) {
            return redirect()->route('register')
                ->with('error', 'Phone number not found. Please register again.');
        }

        // Check if there's a cooldown active
        $lastSent = session('last_verification_sent');
        if ($lastSent) {
            $cooldownSeconds = 60; // 1 minute cooldown
            $secondsElapsed = Carbon::now()->diffInSeconds(Carbon::parse($lastSent));

            if ($secondsElapsed < $cooldownSeconds) {
                return back()->with('error', 'Please wait ' . ($cooldownSeconds - $secondsElapsed) . ' seconds before requesting a new code.');
            }
        }

        // Generate and send a new verification code
        $this->sendVerificationCode($phone);

        return redirect()->route('phone.verify')
            ->with('status', 'A new verification code has been sent to your phone.');
    }

    public function verifyCode(Request $request)
    {
        $phone = session('phone_for_verification');

        // If no phone in session but user is logged in, use user's phone
        if (!$phone && Auth::check()) {
            $user = Auth::user();

            // If user has no phone, redirect to add phone form
            if (!$user->phone) {
                return redirect()->route('phone.add')
                    ->with('error', 'You need to add a phone number first.');
            }

            $phone = $user->phone;
            session(['phone_for_verification' => $phone]);
        } else if (!$phone) {
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
            // Update verification status - always set status to Verified when phone is verified
            User::where('id', $user->id)->update([
                'phone_verified_at' => Carbon::now(),
                'status' => 'Verified'
            ]);

            // Clean up
            DB::table('verification_codes')->where('phone', $phone)->delete();
            session()->forget(['phone_for_verification', 'last_verification_sent']);

            // If user isn't logged in, log them in
            if (!Auth::check()) {
                Auth::login($user);
            }


            $intended = session('url.intended');
            if ($intended) {
                session()->forget('url.intended');
                return redirect($intended)
                    ->with('status', 'Your phone number has been verified successfully!');
            }

            // Redirect to login page with success message
            return redirect()->route('login')
                ->with('status', 'Your phone number has been verified successfully! You can now login.');
        }

        return redirect()->route('register')
            ->with('error', 'We could not find your account. Please register again.');
    }

    public function update_employee(Request $request, $id)
    {
        // Find the employee
        $employee = User::findOrFail($id);

        // Validate the request data with conditional phone/email validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|regex:/^[0-9]{9}$/|unique:users,phone,' . $id,
            'role' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
            'address' => 'nullable|string|max:255',
        ], [
            'phone.regex' => 'The phone number must be 9 digits without country code.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Handle phone number (if provided)
            $fullPhone = null;
            $phoneChanged = false;

            if ($request->filled('phone')) {
                $fullPhone = '255' . $request->phone;
                $phoneChanged = $employee->phone !== $fullPhone;
            } else if ($employee->phone !== null) {
                // Phone was removed
                $phoneChanged = true;
            }

            // Create an update array instead of modifying the model directly
            $updateData = [
                'name' => $request->name,
                'email' => $request->email ?: null,
                'phone' => $fullPhone,
                'role' => $request->role,
                'gender' => $request->gender,
                'address' => $request->address,
            ];

            // If phone number changed or was added/removed, update verification status
            if ($phoneChanged) {
                $updateData['status'] = $fullPhone ? 'Not Verified' : null;
                $updateData['phone_verified_at'] = null;
            }

            // Update the employee using the update method
            $updated = User::where('id', $id)->update($updateData);
            UserActivity::log('user', "Updated employee: {$employee->name}");

            if ($updated) {
                // Get the updated user
                $employee = User::find($id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Employee updated successfully!',
                    'employee' => $employee,
                    'phone_changed' => $phoneChanged,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No changes were made to the employee.',
                ], 422);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update employee: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function add_employee(Request $request)
    {
        // Validate the request data with conditional phone/email validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{9}$/|unique:users',
            'role' => 'nullable|string',
            'gender' => 'nullable|in:Male,Female',
            'address' => 'nullable|string|max:255',
        ], [
            'phone.regex' => 'The phone number must be 9 digits without country code.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            // Handle phone number (if provided)
            $fullPhone = null;
            if ($request->filled('phone')) {
                $fullPhone = '255' . $request->phone;
            }

            // Create the employee
            $employee = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $fullPhone,
                'password' => Hash::make('12345678'), // Default password
                'role' => $request->role,
                'gender' => $request->gender,
                'address' => $request->address,
                // Only set status to Not Verified if we have a phone number
                'status' => $fullPhone ? 'Not Verified' : null,
                'is_active' => true,  // Add this line
                'last_activity' => now()  // Add this lin
            ]);

            UserActivity::log('user', "Created employee: {$employee->name} ({$employee->role})");
            return response()->json([
                'status' => 'success',
                'message' => 'Employee added successfully! Default password is 12345678',
                'employee' => $employee,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed! Unable to Add Employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $isDeveloper = $user->email === 'swedyharuny@gmail.com';

        if (!$user->role && !$isDeveloper) {
            return redirect()->route('contactUS');
        }
        return response()->json([
            'user' => $user,
        ]);
    }

    public function get_employee($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'user' => $user, // Changed to 'user' to match your existing code
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to find employee: ' . $e->getMessage(),
            ], 404);
        }
    }


    public function delete_employee($id)
    {
        try {
            $employee = User::findOrFail($id);

            $protectedEmails = [
                'swedyharuny@gmail.com',
                'hawantimizi@gmail.com'
            ];

            if (in_array($employee->email, $protectedEmails)) {

                if (Auth::id() !== $employee->id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Your not authorize to delete this user.',
                    ], 403);
                }
            }
            $employeeName = $employee->name;

            $employee->delete();
            UserActivity::log('user', "Deleted employee: {$employeeName}");

            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully!',
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete employee. ' . $e->getMessage(),
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $isDeveloper_Director = in_array($user->email, ["swedyharuny@gmail.com", "hawantimizi@gmail.com"]);

        if (!$user->role && !$isDeveloper_Director) {
            return redirect()->route('website');
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully.',
        ], 200);
    }
}
