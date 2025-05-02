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


    public function EmployeesCounts()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $isDeveloper = $user->email === 'swedyharuny@gmail.com';
        $isDirector = $user->email === 'hawantimizi@gmail.com';
        if (!$user->role && !$isDeveloper && !$isDirector) {
            return redirect()->route('login');
        }
        $male_count = User::where('gender', 'Male')
            ->whereNotNull('role')
            ->where('role', '<>', '')
            ->count();
        $female_count = User::where('gender', 'Female')
            ->whereNotNull('role')
            ->where('role', '<>', '')
            ->count();
        $countEmptyRole = User::whereNull('role')->orWhere('role', '')->count();
        $countNonEmptyRole = User::whereNotNull('role')->where('role', '<>', '')->count();
        $total_employees = User::count();

        return response()->json([
            'male_count' => $male_count,
            'female_count' => $female_count,
            'countEmptyRole' => $countEmptyRole,
            'countNonEmptyRole' => $countNonEmptyRole,
            'total_employees' => $total_employees,
        ]);
    }

    public function fetch_employees(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $isDeveloper = $user->email === 'swedyharuny@gmail.com';
        $isDirector = $user->role === 'Director';
        $isManager = $user->role === 'Manager';
        $isHeadTeacher = $user->role === 'Head Teacher';

        if (!$user->role && !$isDeveloper) {
            return redirect()->route('login');
        }

        $search = $request->get('search');
        $role = $request->get('role');
        $gender = $request->get('gender');

        $mainQuery = User::query();

        // Apply search filters
        if ($search) {
            $mainQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        }

        if ($role) {
            $mainQuery->where(function ($q) use ($role) {
                $q->where('role', 'LIKE', "%{$role}%");
            });
        }

        if ($gender) {
            $mainQuery->where('gender', $gender);
        }

        if ($isDeveloper) {
            // swedyharuny@gmail.com can see all employees
            // No additional filters needed
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
        } else if ($isHeadTeacher) {
            // Head Teachers can see all employees except Directors, Managers and swedyharuny@gmail.com
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
        $query->with('schoolClasses');

        $employees = $query->get();

        $total_employees = $employees->count();
        $employees_male_count = $employees->where('gender', 'Male')->count();
        $employees_female_count = $employees->where('gender', 'Female')->count();

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
            ]);
    
            // Store the phone number in session for verification
            session(['phone_for_verification' => $fullPhone]);
            
            // Generate and send verification code
            $this->sendVerificationCode($fullPhone);
            
            // Redirect to phone verification page
            return redirect()->route('phone.verify')
                ->with('status', 'Account created successfully! Please verify your phone number.');
                
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
        
        if (!$phone) {
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
        
        if (!$phone) {
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
            session()->forget(['phone_for_verification', 'last_verification_sent']);
            
            // Redirect to login page with success message
            return redirect()->route('login')
                ->with('status', 'Your phone number has been verified successfully! You can now login.');
        }
        
        return redirect()->route('register')
            ->with('error', 'We could not find your account. Please register again.');
    }

    public function add_employee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'disability' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'passport_size' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'school_class_id' => 'nullable|exists:school_classes,id',
        ]);

        // Set default password
        $password = Hash::make('12345678');

        // Handle passport size photo upload
        $passportPath = null;
        if ($request->hasFile('passport_size')) {
            // Store directly to public_html
            $file = $request->file('passport_size');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('/home/shamtebi/public_html/storage/images', $fileName);
            $passportPath = 'images/' . $fileName;
        }

        // Handle attachment upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentFile = $request->file('attachment');
            $attachmentName = time() . '_attachment_' . $attachmentFile->getClientOriginalName();
            // Create directory if it doesn't exist
            if (!file_exists('/home/shamtebi/public_html/storage/employees/attachments')) {
                mkdir('/home/shamtebi/public_html/storage/employees/attachments', 0755, true);
            }
            $attachmentFile->move('/home/shamtebi/public_html/storage/employees/attachments', $attachmentName);
            $attachmentPath = 'employees/attachments/' . $attachmentName;
        }

        // Create the employee
        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'disability' => $request->disability,
            'description' => $request->description,
            'passport_size' => $passportPath,
            'attachment' => $attachmentPath,
            'school_class_id' => $request->school_class_id,
        ]);



        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Employee added successfully!',
                'employee' => $employee,
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed! Unable to Add Employee'
            ]);
        }
    }

    public function update_employee(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'disability' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'passport_size' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'school_class_id' => 'nullable|exists:school_classes,id',
        ]);

        // Handle passport size photo upload
        if ($request->hasFile('passport_size')) {
            if ($employee->passport_size) {
                // Delete old file
                if (file_exists('/home/shamtebi/public_html/storage/' . $employee->passport_size)) {
                    unlink('/home/shamtebi/public_html/storage/' . $employee->passport_size);
                }
            }

            // Store new file
            $file = $request->file('passport_size');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('/home/shamtebi/public_html/storage/images', $fileName);
            $employee->passport_size = 'images/' . $fileName;
        }

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            if ($employee->attachment) {
                // Delete old file
                if (file_exists('/home/shamtebi/public_html/storage/' . $employee->attachment)) {
                    unlink('/home/shamtebi/public_html/storage/' . $employee->attachment);
                }
            }

            // Store new file
            $attachmentFile = $request->file('attachment');
            $attachmentName = time() . '_attachment_' . $attachmentFile->getClientOriginalName();
            // Create directory if it doesn't exist
            if (!file_exists('/home/shamtebi/public_html/storage/employees/attachments')) {
                mkdir('/home/shamtebi/public_html/storage/employees/attachments', 0755, true);
            }
            $attachmentFile->move('/home/shamtebi/public_html/storage/employees/attachments', $attachmentName);
            $employee->attachment = 'employees/attachments/' . $attachmentName;
        }

        // Update employee details
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->role = $request->role;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->city = $request->city;
        $employee->country = $request->country;
        $employee->state = $request->state;
        $employee->zipcode = $request->zipcode;
        $employee->birth_date = $request->birth_date;
        $employee->gender = $request->gender;
        $employee->disability = $request->disability;
        $employee->description = $request->description;

        // Track the previous and new school class
        $oldSchoolClassId = $employee->school_class_id;
        $newSchoolClassId = $request->school_class_id;
        $employee->school_class_id = $newSchoolClassId;

        $employee->save();



        if ($employee) {
            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully!',
                'employee' => $employee,
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed! Unable to Update Employee'
            ]);
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

    public function get_UserDetail($id)
    {
        $user = User::with(['schoolClasses'])->find($id);
        return response()->json([
            'user' => $user,
        ]);
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

            // Delete passport size photo if exists
            if ($employee->passport_size && file_exists('/home/shamtebi/public_html/storage/' . $employee->passport_size)) {
                unlink('/home/shamtebi/public_html/storage/' . $employee->passport_size);
            }

            // Delete attachment if exists
            if ($employee->attachment && file_exists('/home/shamtebi/public_html/storage/' . $employee->attachment)) {
                unlink('/home/shamtebi/public_html/storage/' . $employee->attachment);
            }



            $employee->delete();

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
