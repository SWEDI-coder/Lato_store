{{-- resources/views/Auth/reset-password.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>Shop System - Create New Password</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="h-screen md:flex bg-gray-100">
        <!-- Left Side - Brand Content -->
        <div class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-emerald-700 to-teal-500 justify-around items-center hidden">
            <img src="{{ asset('images/shop_logo.png') }}" alt="Shop Logo" class="absolute max-w-full scale-75 top-0 max-h-full object-contain rounded-lg">
            <div class="mt-32">
                <h1 class="text-white font-bold text-4xl font-sans">Your Shop Name</h1>
                <p class="text-white mt-1">Create a new secure password.</p>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
        </div>

        <!-- Right Side - Reset Password Form -->
        <div class="flex md:w-1/2 justify-center py-10 items-center">
            <div class="bg-white p-8 shadow-lg rounded-lg w-full max-w-md">
                <!-- Flash Messages -->
                @if (session('status'))
                <div class="bg-green-500 p-4 rounded-lg mb-6 text-white text-center">
                    {{ session('status') }}
                </div>
                @endif

                @if (session('error'))
                <div class="bg-red-500 p-4 rounded-lg mb-6 text-white text-center">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('password.sms.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <h1 class="text-gray-800 font-bold text-2xl mb-1">Create New Password</h1>
                    <p class="text-sm font-normal text-gray-600 mb-7">Enter a new secure password for your account.</p>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                        <div class="flex items-center border-2 border-gray-300 py-2 px-3 rounded-lg @error('password') border-red-500 @enderror">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <input class="pl-2 outline-none border-none w-full" type="password" name="password" id="password" 
                                placeholder="••••••••" required autocomplete="new-password" />
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Field -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
                        <div class="flex items-center border-2 border-gray-300 py-2 px-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <input class="pl-2 outline-none border-none w-full" type="password" name="password_confirmation" 
                                id="password_confirmation" placeholder="••••••••" required autocomplete="new-password" />
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-bold text-gray-700 mb-2">Password Requirements:</h3>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                At least 8 characters long
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Contains at least one uppercase letter
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Contains at least one number
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="block w-full bg-teal-600 hover:bg-teal-700 transition duration-150 ease-in-out py-3 rounded-lg text-white font-semibold mb-4">
                        Reset Password
                    </button>

                    <!-- Go Back to Login -->
                    <p class="mt-6 text-center text-gray-600">
                        <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-800 font-semibold">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>