<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>Shop System - Login</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="h-screen md:flex bg-gray-300">
        <!-- Left Side - Brand Content -->
        <div class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-emerald-700 to-teal-500 justify-around items-center hidden">
            <img src="{{ asset('images/shop_logo.png') }}" alt="Shop Logo" class="absolute hidden max-w-full scale-75 top-0 max-h-full object-contain rounded-lg">
            <div class="mt-32">
                <h1 class="text-white font-bold text-4xl font-sans">Your Shop Name</h1>
                <p class="text-white mt-1">Shopping made easy.</p>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex md:w-1/2 justify-center py-10 items-center">
            <div class="bg-white p-8 shadow-lg rounded-lg w-full max-w-md">

                <!-- Flash Messages -->
                @if (session('status'))
                    @if (str_contains(session('status'), 'verified successfully') || 
                        str_contains(session('status'), 'success') || 
                        str_contains(session('status'), 'reset successfully') || 
                        str_contains(session('status'), 'has been verified'))
                    <div class="bg-green-500 p-4 rounded-lg mb-6 text-white text-center">
                        {{ session('status') }}
                    </div>
                    @else
                    <div class="bg-red-500 p-4 rounded-lg mb-6 text-white text-center">
                        {{ session('status') }}
                    </div>
                    @endif
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    @method('post')

                    <h1 class="text-gray-800 font-bold text-2xl mb-1">Welcome Back!</h1>
                    <p class="text-sm font-normal text-gray-600 mb-7">Sign in to your account</p>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                        <div class="flex items-center border-2 class_attrib py-2 px-3 rounded-lg @error('email') border-red-500 @enderror">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            <input class="pl-2 outline-none border-none w-full" type="email" name="email" id="email"
                                placeholder="your@email.com" value="{{ old('email') }}" required autocomplete="email" autofocus />
                        </div>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <div class="flex items-center border-2 class_attrib py-2 px-3 rounded-lg @error('password') border-red-500 @enderror">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <input class="pl-2 outline-none border-none w-full" type="password" name="password" id="password"
                                placeholder="••••••••" required autocomplete="current-password" />
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between mb-2">


                        <div class="text-sm">
                            <a href="{{ route('password.sms.request') }}" class="text-teal-600 hover:text-teal-800 font-medium">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit"
                        class="block w-full bg-teal-600 hover:bg-teal-700 transition duration-150 ease-in-out py-3 rounded-lg text-white font-semibold mb-4">
                        Login
                    </button>

                    <!-- Divider -->
                    <div class="relative flex items-center my-6">
                        <div class="flex-grow border-t border-gray-400"></div>
                        <span class="flex-shrink mx-4 text-gray-600">or</span>
                        <div class="flex-grow border-t border-gray-400"></div>
                    </div>


                    <!-- Registration Link -->
                    <p class="mt-8 text-center text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-800 font-semibold">
                            Create an account
                        </a>
                    </p>


                </form>
            </div>
        </div>
    </div>

    <script>
        add_classList();

        function add_classList() {
            const dialogs = document.querySelectorAll('.class_attrib');
            dialogs.forEach(dialog => dialog.classList.add('border-gray-300'));
        }
    </script>
</body>

</html>