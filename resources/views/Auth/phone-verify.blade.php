{{-- resources/views/Auth/phone-verify.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>Shop System - Verify Phone</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="h-screen md:flex bg-gray-300">
        <!-- Left Side - Brand Content -->
        <div class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-emerald-700 to-teal-500 justify-around items-center hidden">
            <img src="{{ asset('images/shop_logo.png') }}" alt="Shop Logo" class="absolute max-w-full scale-75 top-0 max-h-full object-contain rounded-lg hidden">
            <div class="mt-32">
                <h1 class="text-white font-bold text-4xl font-sans">Your Shop Name</h1>
                <p class="text-white mt-1">Verify your phone number to complete registration.</p>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
        </div>

        <!-- Right Side - Verification Code Form -->
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

                <div class="text-center mb-6">
                    <div class="inline-flex p-4 rounded-full bg-teal-100 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-gray-800 font-bold text-2xl mb-1">Verify Your Phone</h1>
                    <p class="text-sm font-normal text-gray-600 mb-2">We've sent a verification code to your phone number</p>
                    <p class="text-teal-600 font-medium">{{ substr($phone, 0, 5) }}•••••{{ substr($phone, -2) }}</p>
                </div>

                <!-- Verification Code Form -->
                <form action="{{ route('phone.verify.submit') }}" method="POST" class="mb-6">
                    @csrf

                    <div class="mb-6">
                        <label for="verification_code" class="block text-gray-700 text-sm font-bold mb-2">Enter Verification Code</label>
                        
                        <!-- OTP Input -->
                        <div class="grid grid-cols-6 gap-2 mb-2">
                            @for ($i = 1; $i <= 6; $i++)
                            <input type="text" 
                                name="code[]" 
                                id="code-{{ $i }}" 
                                maxlength="1" 
                                class="w-full h-12 text-center text-xl font-bold border-2 class_attrib rounded-lg focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 @error('code.'.$i-1) border-red-500 @enderror" 
                                inputmode="numeric"
                                pattern="[0-9]"
                                autocomplete="one-time-code"
                                required
                                oninput="moveToNext(this, {{ $i }})"
                            >
                            @endfor
                        </div>
                        
                        @error('verification_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                        @if ($errors->has('code'))
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('code') }}</p>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="block w-full bg-teal-600 hover:bg-teal-700 transition duration-150 ease-in-out py-3 rounded-lg text-white font-semibold mb-4">
                        Verify Phone
                    </button>
                </form>

                <!-- Resend Code -->
                <div class="text-center">
                    <p class="text-gray-600 text-sm mb-2">Didn't receive the code?</p>
                    
                    @if(isset($resendAvailable) && $resendAvailable)
                        <form action="{{ route('phone.verify.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-teal-600 hover:text-teal-800 font-semibold">
                                Resend code
                            </button>
                        </form>
                    @else
                        <p class="text-gray-500 text-sm">
                            You can request a new code in <span id="countdown">{{ $resendWaitTime ?? 60 }}</span> seconds
                        </p>
                        
                        <form id="resendForm" action="{{ route('phone.verify.resend') }}" method="POST" class="hidden">
                            @csrf
                            <button type="submit" class="text-teal-600 hover:text-teal-800 font-semibold">
                                Resend code
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Javascript for OTP Input and Countdown -->
                <script>
                    // Function to move to next input after filling current one
                    function moveToNext(field, index) {
                        // Only allow numbers
                        field.value = field.value.replace(/[^0-9]/g, '');
                        
                        // If field has a value and there's a next field, focus on it
                        if (field.value && index < 6) {
                            document.getElementById('code-' + (index + 1)).focus();
                        }
                        
                        // Check if all fields are filled and submit if they are
                        if (index === 6 && field.value) {
                            let allFilled = true;
                            for (let i = 1; i <= 6; i++) {
                                if (!document.getElementById('code-' + i).value) {
                                    allFilled = false;
                                    break;
                                }
                            }
                            if (allFilled) {
                                document.querySelector('form').submit();
                            }
                        }
                    }
                    
                    // Focus first input on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('code-1').focus();
                        
                        // Countdown timer for resend button
                        let countdownElement = document.getElementById('countdown');
                        if (countdownElement) {
                            let seconds = parseInt(countdownElement.textContent);
                            let timer = setInterval(function() {
                                seconds--;
                                countdownElement.textContent = seconds;
                                
                                if (seconds <= 0) {
                                    clearInterval(timer);
                                    document.getElementById('resendForm').classList.remove('hidden');
                                    countdownElement.parentElement.classList.add('hidden');
                                }
                            }, 1000);
                        }
                    });
                </script>
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