{{-- resources/views/auth/add-phone.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>Shop System - Add Phone Number</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="h-screen md:flex bg-gray-100">
        <!-- Left Side - Brand Content -->
        <div class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-emerald-700 to-teal-500 justify-around items-center hidden">
            <img src="{{ asset('images/shop_logo.png') }}" alt="Shop Logo" class="absolute max-w-full scale-75 top-0 max-h-full object-contain rounded-lg hidden">
            <div class="mt-32">
                <h1 class="text-white font-bold text-4xl font-sans">Your Shop Name</h1>
                <p class="text-white mt-1">Please add your phone number to continue.</p>
            </div>
            <!-- Decorative circles -->
            <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
        </div>

        <!-- Right Side - Add Phone Form -->
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h1 class="text-gray-800 font-bold text-2xl mb-1">Add Your Phone Number</h1>
                    <p class="text-sm font-normal text-gray-600 mb-2">
                        You need to add and verify a phone number to continue using the system.
                    </p>
                </div>

                <!-- Add Phone Form -->
                <form action="{{ route('phone.store') }}" method="POST" class="mb-6">
                    @csrf

                    <div class="mb-6">
                        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                                255
                            </span>
                            <input type="text" name="phone" id="phone" 
                                class="block w-full flex-1 rounded-none rounded-r-md class_attrib focus:border-teal-500 focus:ring-teal-500 @error('phone') border-red-500 @enderror" 
                                pattern="[0-9]{9}" maxlength="9" placeholder="712345678" required value="{{ old('phone') }}" />
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Enter 9 digits without country code (e.g., 712345678)</p>
                        
                        @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                        class="block w-full bg-teal-600 hover:bg-teal-700 transition duration-150 ease-in-out py-3 rounded-lg text-white font-semibold mb-4">
                        Add & Verify Phone
                    </button>
                </form>

                <!-- Logout option -->
                <div class="text-center">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-teal-600 hover:text-teal-800 font-semibold">
                            Logout
                        </button>
                    </form>
                </div>
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