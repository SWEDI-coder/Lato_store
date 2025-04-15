<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Duka</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- jQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.2.2/cdn.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .error {
            color: red;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        .tab-button.active {
            background-color: #4F46E5;
            color: white;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button:hover:not(.active) {
            background-color: #E5E7EB;
            transform: translateY(-1px);
        }

        /* Mobile Menu Animation */
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out;
        }

        #mobile-menu.open {
            max-height: 300px;
        }

        /* Responsive Table */
        @media (max-width: 640px) {
            .responsive-table {
                display: block;
                width: 100%;
                overflow-x: auto;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                max-height: 0;
                opacity: 0;
            }

            to {
                max-height: 300px;
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                max-height: 300px;
                opacity: 1;
            }

            to {
                max-height: 0;
                opacity: 0;
            }
        }

        .navbar-brand {
            position: relative;
            overflow: hidden;
        }

        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #fff;
            transition: width 0.3s ease;
        }

        .navbar-brand:hover::after {
            width: 100%;
        }
    </style>
</head>

<body class="bg-gray-400 min-h-screen">
    <!-- Fixed Navbar -->
    <nav class="fixed top-0 left-0 right-0 bg-indigo-700 text-white shadow-lg z-10">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="text-xl font-bold navbar-brand animate__animated animate__fadeIn">TabView Dashboard</div>
                <div class="hidden md:flex space-x-4 animate__animated animate__fadeIn">
                    <a href="#" class="hover:text-indigo-200 transition-all duration-300 transform hover:scale-105">Home</a>
                    <a href="#" class="hover:text-indigo-200 transition-all duration-300 transform hover:scale-105">About</a>
                    <a href="#" class="hover:text-indigo-200 transition-all duration-300 transform hover:scale-105">Contact</a>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="focus:outline-none transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="mobile-menu" class="md:hidden">
                <a href="#" class="block py-2 hover:text-indigo-200 transition-all duration-300 transform hover:translate-x-2">Home</a>
                <a href="#" class="block py-2 hover:text-indigo-200 transition-all duration-300 transform hover:translate-x-2">About</a>
                <a href="#" class="block py-2 hover:text-indigo-200 transition-all duration-300 transform hover:translate-x-2">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Main Content with Tabs -->
    <main class="container mx-auto px-4 pt-24 pb-8">
        <h1 class="text-3xl font-bold mb-6 animate__animated animate__fadeInDown">Dashboard Overview</h1>

        <!-- Tab Navigation -->
        <div class="flex flex-wrap border-b border-gray-200 mb-6 overflow-x-auto whitespace-nowrap pb-2 animate__animated animate__fadeIn">
            <button class="tab-button active px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="sales">
                Sales
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="purchases">
                Purchases
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="customers">
                Parties
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="inventory">
                Inventory
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="reports">
                Reports
            </button>
        </div>

        <!-- Tab Content -->
        <div class="bg-gray-50 rounded-lg shadow-md p-4 sm:p-6 animate__animated animate__fadeIn">
            <!-- Sales Tab Content -->
            <div id="sales" class="tab-content active">
                <div class=" flex justify-between">
                    <h2 class="text-xl font-semibold mb-1">Sales Management</h2>

                    <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-md">
                        <div class="relative group">
                            <svg onclick="show_add_sales_modal()" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart3 scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Sale</span>
                        </div>

                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-printer scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Print</span>
                        </div>

                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Transactions</span>
                        </div>

                    </div>
                </div>
                <!-- Filters -->
                <div class="flex flex-wrap gap-3 mb-1">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="Saleearch" placeholder="Search Customer..."
                            class="w-full px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <fieldset class="border-2 border-gray-500 rounded-lg px-3 py-0.5">
                        <legend class="text-xs font-semibold">Start - End Date </legend>
                        <div class="flex gap-2 text-xs">
                            <input type="date" id="startDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" id="endDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </fieldset>
                </div>


                <div class="responsive-table">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Date</th>
                                <th class="py-3 px-6 text-left">Customer</th>
                                <th class="py-3 px-6 text-right">Amount</th>
                                <th class="py-3 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            <!-- Table rows will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="sales-loading" class=" mt-4 flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-800"></div>
                </div>
            </div>

            <!-- purchases Tab Content -->
            <div id="purchases" class="tab-content">
                <div class=" flex justify-between">
                    <h2 class="text-xl font-semibold mb-1">Purchases Management</h2>

                    <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-md">
                        <div class="relative group">
                            <svg onclick="show_add_purchase_modal()" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart3 scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">purchases</span>
                        </div>

                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-printer scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Print</span>
                        </div>

                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5m-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Transactions</span>
                        </div>

                    </div>
                </div>
                <!-- Filters -->
                <div class="flex flex-wrap gap-3 mb-1">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="Saleearch" placeholder="Search Customer..."
                            class="w-full px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <fieldset class="border-2 border-gray-500 rounded-lg px-3 py-0.5">
                        <legend class="text-xs font-semibold">Start - End Date </legend>
                        <div class="flex gap-2 text-xs">
                            <input type="date" id="startDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" id="endDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </fieldset>
                </div>


                <div class="responsive-table">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Date</th>
                                <th class="py-3 px-6 text-left">Supplier</th>
                                <th class="py-3 px-6 text-right">Amount</th>
                                <th class="py-3 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            <!-- Table rows will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="purchases-loading" class=" mt-4 flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-800"></div>
                </div>
            </div>

            <!-- Customers Tab Content -->
            <div id="customers" class="tab-content">
                <h2 class="text-xl font-semibold mb-4">Parties Database</h2>
                <div class="responsive-table">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-left">Phone</th>
                                <th class="py-3 px-6 text-right">Total Orders</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            <!-- Table rows will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="customers-loading" class=" mt-4 flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-800"></div>
                </div>
            </div>

            <!-- Inventory Tab Content -->
            <div id="inventory" class="tab-content">
                <div class="flex justify-between mb-4">
                    <h2 class="text-xl font-semibold">Inventory</h2>

                    <div class="flex items-center gap-3">
                        <!-- Inventory Stats Cards -->
                        <div class="grid grid-cols-4 gap-3 mr-4">
                            <div class="bg-white p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Total</div>
                                <div id="total_inventory" class="text-lg font-bold">0</div>
                            </div>
                            <div class="bg-green-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Available</div>
                                <div id="Available_count" class="text-lg font-bold text-green-600">0</div>
                            </div>
                            <div class="bg-yellow-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Low Stock</div>
                                <div id="Not_Available_count" class="text-lg font-bold text-yellow-600">0</div>
                            </div>
                            <div class="bg-red-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Sold Out</div>
                                <div id="Sold_Out_count" class="text-lg font-bold text-red-600">0</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-lg">
                            <div class="relative group">
                                <svg onclick="show_add_item_modal()" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-check scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                </svg>
                                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Add</span>
                            </div>
                            <div class="relative group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-bar-graph scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M10 13.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-6a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm-2.5.5a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5zm-3 0a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5z" />
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                                </svg>
                                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Statistics</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <!-- Filter Container with responsive grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <!-- Search Filter -->
                        <div class="w-full">
                            <div class="relative">
                                <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    id="searchTable_items"
                                    placeholder="Search by name, SKU, or description..."
                                    class="w-full px-3 h-10 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full">
                            <select
                                id="item_status_filter"
                                class="w-full px-3 h-10 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                <option value="Available">Available</option>
                                <option value="Not Available">Not Available</option>
                                <option value="Sold Out">Sold Out</option>
                                <option value="Damage">Damaged</option>
                                <option value="Expired">Expired</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Active">Active</option>
                            </select>
                        </div>

                        <!-- Quantity Range Filter -->
                        <div class="w-full">
                            <div class="flex items-center space-x-1">
                                <input
                                    type="number"
                                    id="min_quantity"
                                    placeholder="Min Qty"
                                    min="0"
                                    class="flex-1 px-3 h-10 border-2 border-gray-300 rounded-lg">
                                <span class="text-gray-500">to</span>
                                <input
                                    type="number"
                                    id="max_quantity"
                                    placeholder="Max Qty"
                                    min="0"
                                    class="flex-1 px-3 h-10 border-2 border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <!-- Sort Option -->
                        <div class="w-full flex items-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" id="sort_items_by_Alphabet" class="form-checkbox h-5 w-5 text-blue-600">
                            </label>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="inventory" class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">SKU</th>
                                        <th class="py-3 px-6 text-left">Product</th>
                                        <th class="py-3 px-6 text-left">Category</th>
                                        <th class="py-3 px-6 text-right">Stock</th>
                                        <th class="py-3 px-6 text-right">Price</th>
                                        <th class="py-3 px-6 text-center">Status</th>
                                        <th class="py-3 px-6 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">
                                    <!-- Table rows will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="inventory-loading" class="mt-4 flex justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-800"></div>
                    </div>
                </div>
            </div>
    </main>

    <div id="feedbackModal" class="fixed inset-0 items-center hidden justify-center z-50 p-4 overflow-y-auto">
        <!-- Modal Content -->
        <div class="bg-white w-full max-w-md mx-auto rounded-lg shadow-lg transform transition-all sm:my-8 relative">
            <!-- Close Button -->
            <div class="absolute top-2 right-2 z-10">
                <button id="closeModal" class="p-2 rounded-full hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Close modal">
                    <svg class="fill-current text-gray-700 hover:text-gray-900 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 pt-8">
                <div class="text-center">
                    <div id="modalIcon" class="text-4xl mb-4 inline-flex justify-center"></div>
                    <h2 id="modalTitle" class="text-xl font-semibold break-words"></h2>
                    <p id="modalMessage" class="mt-2 text-gray-600 break-words"></p>
                </div>

                <!-- Button -->
                <div class="mt-6 text-center">
                    <button id="closeModalButton" class="w-full sm:w-auto bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="add_sales_modal" class="fixed inset-0 z-20 overflow-y-auto hidden items-center justify-center p-4">
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-base lg:text-lg font-semibold">New Sales</h1>
                <button onclick="hide_add_sales_modal()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-4" id="sales_form">
                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="w-full sm:w-3/4">
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="Description" class="text-xs sm:text-sm font-medium text-gray-700">Description:</label>
                                <textarea id="Description" name="Description" rows="2" placeholder="Enter description" class="w-full sm:w-auto flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="px-4 py-2 text-white text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto" type="submit">Record Sale</button>
                    </div>

                    <!-- Customer and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="date" class="text-xs sm:text-sm font-medium text-gray-700">Date:</label>
                                <input type="date" id="sale_date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Customer Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="search_Customer" class="text-xs sm:text-sm font-medium text-gray-700">Customer:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-4 h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Customer" name="Customer" id="search_Customer" class="pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="Customerlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" class="Customer_ID" id="Customer_ID" name="Customer_id[]" />
                                </div>
                            </div>
                        </div>

                        <!-- Show Sales Button -->
                        <div class="flex items-center">
                            <div onclick="show_SalesDialog()" class="bg-green-500 hover:bg-green-400 rounded px-3 py-1.5 transition cursor-pointer">
                                <span class="text-white text-xs sm:text-sm">Show Sales</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="table1" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-2 px-2 text-left font-medium">Stock</th>
                                        <th class="py-2 px-2 text-left font-medium">Qtn</th>
                                        <th class="py-2 px-2 text-left font-medium">Item</th>
                                        <th class="py-2 px-2 text-left font-medium">Description</th>
                                        <th class="py-2 px-2 text-left hidden md:table-cell font-medium">Expire Date</th>
                                        <th class="py-2 px-2 text-left font-medium">Unit Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Discount</th>
                                        <th class="py-2 px-2 text-left font-medium">Net Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tbl_id" class="border-b border-gray-300">
                                        <td class="p-1">
                                            <input disabled placeholder="0" class="av_quantity placeholder:text-gray-500 bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text" />
                                        </td>
                                        <td class="p-1 tbl-id">
                                            <input onkeyup="netPriceFn(this)" id="qtn" placeholder="Qty" name="quantity" class="quantity border w-14 px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="number" />
                                        </td>
                                        <td class="p-1">
                                            <div class="relative">
                                                <input placeholder="Item" id="item_id" name="item_name" data-type="item_name" class="item_name border w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
                                                <div id="itemslist" class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                                                <input type="hidden" class="item_id" name="item_id" />
                                            </div>
                                        </td>
                                        <td class="p-1">
                                            <textarea placeholder="Description" rows="1" class="description w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                                        </td>
                                        <td class="hidden md:table-cell p-1">
                                            <input type="date" id="date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 border rounded w-full">
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="Sales_price" placeholder="0.00 TZS" class="unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text" />
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="discount" placeholder="0.00 TZS" class="discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td id="ntl" class="ntl text-center p-1"><span class="">TSh</span>0.00</td>
                                        <td class="p-1">
                                            <div class="flex justify-center gap-2">
                                                <button type="button" onclick="tableManager.addRow(this)" class="text-blue-600 hover:text-blue-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                    </svg>
                                                </button>
                                                <button type="button" onclick="tableManager.delRow(this)" class="text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-400">
                                        <td colspan="2" class="py-2 px-2 text-left font-medium">Paid:</td>
                                        <td class="p-2">
                                            <input name="paid" placeholder="0.00 Tzs" id="pay_amount" class="paid w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs sm:text-sm font-medium">Dept:</span>
                                                <div class="text-red-600">
                                                    <span class="dept text-xs sm:text-sm" id="dept">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="4" class="p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="tl" class="font-bold text-green-700 text-xs sm:text-sm">0.00 <span>Tzs</span></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editSalesModal" class="fixed hidden inset-0 z-20 overflow-y-auto items-center justify-center p-4">
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-base lg:text-lg font-semibold">Edit Sales</h1>
                <button onclick="hide_edit_SalesMODAL()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-4" id="edit_Sale_form">
                    <input type="hidden" id="edit_Sales_id" name="Sales_id">

                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="w-full sm:w-3/4">
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="edit_description" class="text-xs sm:text-sm font-medium text-gray-700">Description:</label>
                                <textarea id="edit_description" name="description" rows="2" placeholder="Enter description" class="w-full sm:w-auto flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="px-4 py-2 text-white text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto" type="submit">Update Sale</button>
                    </div>

                    <!-- Customer and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="edit_date" class="text-xs sm:text-sm font-medium text-gray-700">Date:</label>
                                <input type="date" id="edit_date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Customer Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="edit_Customer" class="text-xs sm:text-sm font-medium text-gray-700">Customer:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-4 h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Customer" name="Customer" id="edit_Customer" class="pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="Customerlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" id="edit_Customer_id" name="Customer_id" />
                                </div>
                            </div>
                        </div>

                        <!-- Show Sales Button -->
                        <div class="flex items-center">
                            <div onclick="show_SalesDialog()" class="bg-green-500 hover:bg-green-400 rounded px-3 py-1.5 transition cursor-pointer">
                                <span class="text-white text-xs sm:text-sm">Show Sales</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="edit_table" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-2 px-2 text-left font-medium">Stock</th>
                                        <th class="py-2 px-2 text-left font-medium">Qtn</th>
                                        <th class="py-2 px-2 text-left font-medium">Item</th>
                                        <th class="py-2 px-2 text-left font-medium">Description</th>
                                        <th class="py-2 px-2 text-left font-medium">Unit Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Discount</th>
                                        <th class="py-2 px-2 text-left font-medium">Net Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="edit_items_container">
                                    <!-- Items will be dynamically added here -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-400">
                                        <td colspan="2" class="py-2 px-2 text-left font-medium">Paid:</td>
                                        <td class="p-2">
                                            <input name="paid" placeholder="0.00 Tzs" id="edit_paid" class="edit_paid w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs sm:text-sm font-medium">Debt:</span>
                                                <div class="text-red-600">
                                                    <span class="edit_debt text-xs sm:text-sm" id="edit_debt">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="3" class="p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="edit_total" class="font-bold text-green-700 text-xs sm:text-sm">0.00 <span>Tzs</span></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="add_item_modal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
        <div class="w-full max-w-md transform transition duration-300 hover:-translate-y-2 border-2 border-green-600 bg-gray-50 shadow-2xl rounded-lg overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="flex justify-between items-center px-4 py-3 bg-gray-100 border-b">
                <h2 class="font-bold text-lg">Add New Item</h2>
                <button onclick="hide_add_item_modal()" class="text-gray-500 cursor-pointer hover:text-gray-800 focus:outline-none">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-4 py-4">
                <form action="" method="POST" id="add_item_form">
                    @csrf
                    <!-- Item Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-800 mb-1">Item name:</label>
                        <input type="text" id="name" name="name" placeholder="Item name" class="w-full px-3 py-2 border-2 border-gray-400 text-gray-700 bg-gray-200 rounded focus:outline-none focus:border-green-500">
                        <div id="Errorname" class="text-red-600 text-sm mt-1"></div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-800 mb-1">Description:</label>
                        <textarea name="description" id="description" rows="2" class="w-full px-3 py-2 bg-gray-100 border-2 border-gray-400 rounded-lg focus:outline-none focus:border-green-500" placeholder="Item description!"></textarea>
                    </div>

                    <!-- Sale Price -->
                    <div class="mb-4">
                        <label for="sale_price" class="block text-sm font-medium text-gray-800 mb-1">Sale Price:</label>
                        <input type="number" step="0.01" id="sale_price" name="sale_price" placeholder="Sale price" class="w-full px-3 py-2 border-2 border-gray-400 text-gray-700 bg-gray-200 rounded focus:outline-none focus:border-green-500">
                        <div id="Errorsale_price" class="text-red-600 text-sm mt-1"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="hide_add_item_modal()" class="px-4 py-2 cursor-pointer bg-gray-300 text-gray-800 rounded hover:bg-gray-400 focus:outline-none transition">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-gray-700 focus:outline-none transition">
                            Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="DELETE_ItemModal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
        <!-- Overlay -->
        <div class="absolute inset-0"></div>

        <!-- Modal Content -->
        <div class="relative w-full max-w-sm bg-white rounded-xl shadow-2xl border border-red-200 overflow-hidden mx-auto">
            <!-- Header -->
            <div class="px-4 sm:px-6 py-4 bg-red-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-8 sm:h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Delete Item</h2>
                    </div>

                    <button onclick="hide_delete_itemDialog()" class="p-2 hover:bg-red-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-5 h-5 text-gray-500 hover:text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-4 sm:px-6 py-4">
                <div class="space-y-2">
                    <p class="text-gray-600">Are you sure you want to delete this item?</p>
                    <p class="text-red-600 font-medium">This action cannot be undone.</p>
                </div>

                <form method="" action="#" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_Item_id">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="hide_delete_itemDialog()" class="w-full sm:flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="delete_Item_btn w-full sm:flex-1 px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Delete Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div id="edit_item_modal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
        <div class="w-full max-w-md transform transition duration-300 hover:-translate-y-2 border-2 border-green-600 bg-gray-50 shadow-2xl rounded-lg overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="flex justify-between items-center px-4 py-3 bg-gray-100 border-b">
                <h2 class="font-bold text-lg">Edit Item</h2>
                <button onclick="hide_EDIT_itemDialog()" class="p-2 rounded-full hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                    <svg class="fill-current text-gray-900 hover:text-white w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-4 py-4">
                <form action="" method="POST" id="edit_item_form">
                    @csrf
                    <input type="hidden" id="edit_item_id">

                    <!-- Item Name -->
                    <div class="mb-4">
                        <label for="Edit_item_name" class="block text-sm font-medium text-gray-800 mb-1">Item name:</label>
                        <input type="text" id="Edit_item_name" name="name" placeholder="Item name" class="w-full px-3 py-2 border-2 border-gray-400 text-gray-700 bg-gray-200 rounded focus:outline-none focus:border-green-500">
                        <div id="Erroritem_name" class="text-red-600 text-sm mt-1"></div>
                    </div>

                    <!-- SKU -->
                    <div class="mb-4">
                        <input type="text" id="Edit_sku" name="sku" placeholder="SKU" class="w-full px-3 py-2 border-2 border-gray-400 text-gray-700 bg-gray-200 rounded focus:outline-none focus:border-green-500 cursor-not-allowed" readonly>
                        <div id="Errorsku" class="text-red-600 text-sm mt-1"></div>
                    </div>

                    <!-- Description -->
                    <div class="mb-1">
                        <label for="Edit_item_description" class="block text-sm font-medium text-gray-800 mb-1">Description:</label>
                        <textarea name="description" id="Edit_item_description" rows="2" class="w-full px-3 py-2 bg-gray-100 border-2 border-gray-400 rounded-lg focus:outline-none focus:border-green-500" placeholder="Item description!"></textarea>
                    </div>

                    <!-- Sale Price -->
                    <div class="mb-1">
                        <label for="Edit_sale_price" class="block text-sm font-medium text-gray-800 mb-1">Sale Price:</label>
                        <input type="number" step="0.01" id="Edit_sale_price" name="sale_price" placeholder="Sale price" class="w-full px-3 py-2 border-2 border-gray-400 text-gray-700 bg-gray-200 rounded focus:outline-none focus:border-green-500">
                        <div id="Errorsale_price" class="text-red-600 text-sm mt-1"></div>
                    </div>

                    <!-- Status -->
                    <div class="mb-1">
                        <select id="Edit_status" disabled name="status" class="w-full px-3 py-2 border-2 border-gray-400 text-gray-700 bg-gray-200 rounded focus:outline-none focus:border-green-500 cursor-not-allowed">
                            <option value="Available">Available</option>
                            <option value="Expired">Expired</option>
                            <option value="Damage">Damage</option>
                            <option value="Sold Out">Sold Out</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="hide_EDIT_itemDialog()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-700 transition-colors">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Redesigned Add Purchase Modal -->
    <div id="add_purchase_modal" class="fixed inset-0 z-20 overflow-y-auto hidden items-center justify-center p-4">
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-base lg:text-lg font-semibold">New Purchase</h1>
                <button onclick="hide_add_purchase_modal()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-4" id="purchases_form">
                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="w-full sm:w-3/4">
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="Description" class="text-xs sm:text-sm font-medium text-gray-700">Description:</label>
                                <textarea id="Description" name="Description" rows="2" placeholder="Enter description" class="w-full sm:w-auto flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="px-4 py-2 text-white text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto" type="submit">Record Purchase</button>
                    </div>

                    <!-- Supplier and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="date" class="text-xs sm:text-sm font-medium text-gray-700">Date:</label>
                                <input type="date" id="date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Supplier Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="search_supplier" class="text-xs sm:text-sm font-medium text-gray-700">Supplier:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-4 h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Supplier" name="supplier" id="search_supplier" class="pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="supplierlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" class="Supplier_ID" id="Supplier_ID" name="Supplier_id[]" />
                                </div>
                            </div>
                        </div>

                        <!-- Show Purchases Button -->
                        <div class="flex items-center">
                            <div onclick="show_purchasesDialog()" class="bg-green-500 hover:bg-green-400 rounded px-3 py-1.5 transition cursor-pointer">
                                <span class="text-white text-xs sm:text-sm">Show Purchases</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="table1" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-2 px-2 text-left font-medium">Stock</th>
                                        <th class="py-2 px-2 text-left font-medium">Qtn</th>
                                        <th class="py-2 px-2 text-left font-medium">Item</th>
                                        <th class="py-2 px-2 text-left font-medium">Description</th>
                                        <th class="py-2 px-2 text-left font-medium">Expire Date</th>
                                        <th class="py-2 px-2 text-left font-medium">Unit Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Discount</th>
                                        <th class="py-2 px-2 text-left font-medium">Net Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tbl_id" class="border-b border-gray-300">
                                        <td class="p-1">
                                            <input disabled placeholder="0" class="av_quantity placeholder:text-gray-500 bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text" />
                                        </td>
                                        <td class="p-1 tbl-id">
                                            <input onkeyup="netPriceFn(this)" placeholder="Qty" name="quantity" class="quantity border w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="number" />
                                        </td>
                                        <td class="p-1">
                                            <div class="relative">
                                                <input placeholder="Item" name="item_name" data-type="item_name" class="item_name border w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
                                                <div class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                                                <input type="hidden" class="item_id" name="item_id" />
                                            </div>
                                        </td>
                                        <td class="p-1">
                                            <textarea placeholder="Description" rows="1" class="description w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                                        </td>
                                        <td class="p-1">
                                            <input type="date" name="expire_date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 border rounded w-full">
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="purchase_price" placeholder="0.00 TZS" class="unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text" />
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="discount" placeholder="0.00 TZS" class="discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td class="ntl text-center p-1"><span>TSh</span>0.00</td>
                                        <td class="p-1">
                                            <div class="flex justify-center gap-2">
                                                <button type="button" onclick="purchaseTableManager.addRow(this)" class="text-blue-600 hover:text-blue-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                    </svg>
                                                </button>
                                                <button type="button" onclick="purchaseTableManager.delRow(this)" class="text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-400">
                                        <td colspan="2" class="py-2 px-2 text-left font-medium">Paid:</td>
                                        <td class="p-2">
                                            <input name="paid" placeholder="0.00 Tzs" id="pay_amount" class="paid w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs sm:text-sm font-medium">Dept:</span>
                                                <div class="text-red-600">
                                                    <span class="dept text-xs sm:text-sm" id="dept">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="4" class="p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="tl" class="font-bold text-green-700 text-xs sm:text-sm">0.00 <span>Tzs</span></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Redesigned Edit Purchase Modal -->
    <div id="editPurchaseModal" class="fixed inset-0 z-20 overflow-y-auto hidden items-center justify-center p-4">
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-base lg:text-lg font-semibold">Edit Purchase</h1>
                <button onclick="hide_edit_purchaseMODAL()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-4" id="edit_purchases_form">
                    <input type="hidden" id="edit_purchase_id" name="purchase_id">

                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="w-full sm:w-3/4">
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="edit_description" class="text-xs sm:text-sm font-medium text-gray-700">Description:</label>
                                <textarea id="edit_description" name="description" rows="2" placeholder="Enter description" class="w-full sm:w-auto flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="px-4 py-2 text-white text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto" type="submit">Update Purchase</button>
                    </div>

                    <!-- Supplier and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="edit_date" class="text-xs sm:text-sm font-medium text-gray-700">Date:</label>
                                <input type="date" id="edit_date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Supplier Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                                <label for="edit_supplier" class="text-xs sm:text-sm font-medium text-gray-700">Supplier:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-4 h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Supplier" name="supplier" id="edit_supplier" class="pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="supplierlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" id="edit_supplier_id" name="supplier_id" />
                                </div>
                            </div>
                        </div>

                        <!-- Show Purchases Button -->
                        <div class="flex items-center">
                            <div onclick="show_purchasesDialog()" class="bg-green-500 hover:bg-green-400 rounded px-3 py-1.5 transition cursor-pointer">
                                <span class="text-white text-xs sm:text-sm">Show Purchases</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="edit_table" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-2 px-2 text-left font-medium">Stock</th>
                                        <th class="py-2 px-2 text-left font-medium">Qtn</th>
                                        <th class="py-2 px-2 text-left font-medium">Item</th>
                                        <th class="py-2 px-2 text-left font-medium">Description</th>
                                        <th class="py-2 px-2 text-left font-medium">Expire Date</th>
                                        <th class="py-2 px-2 text-left font-medium">Unit Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Discount</th>
                                        <th class="py-2 px-2 text-left font-medium">Net Price</th>
                                        <th class="py-2 px-2 text-left font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="edit_items_container">
                                    <!-- Items will be dynamically added here -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-400">
                                        <td colspan="2" class="py-2 px-2 text-left font-medium">Paid:</td>
                                        <td class="p-2">
                                            <input name="paid" id="edit_paid" class="edit_paid w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs sm:text-sm font-medium">Debt:</span>
                                                <div class="text-red-600">
                                                    <span class="edit_debt text-xs sm:text-sm" id="edit_debt">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="4" class="p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="edit_total" class="font-bold text-green-700 text-xs sm:text-sm">0.00 <span>Tzs</span></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showFeedbackModal(type, title, message) {
            const modal = document.getElementById('feedbackModal');
            const modalIcon = document.getElementById('modalIcon');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');

            // Reset previous state
            modalIcon.innerHTML = '';
            modalTitle.textContent = '';
            modalMessage.textContent = '';

            // Update modal based on type
            if (type === 'success') {
                modalIcon.innerHTML = '✔️'; // Success Icon
                modalTitle.textContent = title || 'Success!';
                modalTitle.classList.add('text-green-500');
            } else if (type === 'error') {
                modalIcon.innerHTML = '❌'; // Error Icon
                modalTitle.textContent = title || 'Error!';
                modalTitle.classList.add('text-red-500');
            }

            modalMessage.textContent = message;

            // Show Modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.classList.add('z-50');

            // Close Modal on Button or Background Click
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('closeModalButton').addEventListener('click', closeModal);

            function closeModal() {
                modal.classList.add('hidden');
                modalTitle.classList.remove('text-green-500', 'text-red-500');
            }
        }

        // Add this to your main JavaScript file
        $(document).ready(function() {
            // Global click handler for all buttons including the ADD_studentBTN
            $(document).on('click', 'button[type="submit"], button#ADD_studentBTN', function(e) {
                // Store reference to the clicked button
                const $button = $(this);

                // Skip if button is already in loading state
                if ($button.data('loading')) return;

                // Get button dimensions and styling
                const width = $button.outerWidth();
                const height = $button.outerHeight();
                const btnClass = $button.attr('class');

                // Create and store original button content
                const originalContent = $button.html();
                $button.data('original-content', originalContent);

                // Mark button as loading
                $button.data('loading', true);

                // Create loading spinner that matches button dimensions
                const spinnerSize = Math.min(height * 0.6, 24); // Proportional but capped
                const spinner = `<div class="LoadingState inline-block w-${spinnerSize/4} h-${spinnerSize/4} border-2 border-dashed rounded-full animate-spin dark:border-violet-600 mx-auto"></div>`;

                // Replace button content with spinner
                $button.css({
                    width: width + 'px',
                    height: height + 'px'
                }).html(spinner);

                // If this is not inside a form with AJAX handling, we need to reset it manually after a timeout
                if (!$button.closest('form').data('ajax-bound')) {
                    setTimeout(function() {
                        resetButton($button);
                    }, 10000); // Fallback timeout of 10 seconds
                }
            });

            // Extend the jQuery AJAX setup to automatically handle button states
            const originalAjax = $.ajax;
            $.ajax = function(options) {
                const originalBeforeSend = options.beforeSend;

                options.beforeSend = function(xhr, settings) {
                    // Find the related form and button
                    const $form = $(options.form || $('form:has(button.loading)'));
                    const $button = $form.find('button[type="submit"], button#ADD_studentBTN');

                    // Execute original beforeSend if it exists
                    if (originalBeforeSend) originalBeforeSend(xhr, settings);
                };

                const originalComplete = options.complete;
                options.complete = function(xhr, status) {
                    // Find related buttons and reset them
                    $('button[data-loading="true"]').each(function() {
                        resetButton($(this));
                    });

                    // Execute original complete if it exists
                    if (originalComplete) originalComplete(xhr, status);
                };

                return originalAjax.apply(this, arguments);
            };

            // Modify form validation to mark forms as ajax-bound
            const originalValidate = $.fn.validate;
            $.fn.validate = function(options) {
                $(this).data('ajax-bound', true);
                return originalValidate.apply(this, options);
            };

            // Helper function to reset a button to its original state
            function resetButton($button) {
                if (!$button.data('loading')) return;

                // Restore original content
                $button.html($button.data('original-content'));
                $button.css({
                    width: '',
                    height: ''
                });

                // Mark as not loading
                $button.data('loading', false);
            }

            // Override form submissions to prevent double submits
            $(document).on('submit', 'form', function(e) {
                const $form = $(this);
                if ($form.data('submitting')) {
                    e.preventDefault();
                    return false;
                }
                $form.data('submitting', true);

                // Reset form submitting state after a timeout (safety measure)
                setTimeout(function() {
                    $form.data('submitting', false);
                }, 1000000);
            });
        });

        // Mobile menu toggle with animation
        $(document).ready(function() {
            $('#mobile-menu-button').click(function() {
                $('#mobile-menu').toggleClass('open');
                $(this).toggleClass('rotate-90');
            });

            // Tab switching with animation
            $('.tab-button').click(function() {
                // Hide all tab contents
                $('.tab-content').removeClass('active');

                // Remove active class from all tabs
                $('.tab-button').removeClass('active');

                // Get the tab to show
                const tabId = $(this).data('tab');

                // Add active class to the clicked tab
                $(this).addClass('active');

                // Show the corresponding tab content with animation
                $('#' + tabId).addClass('active animate__animated animate__fadeIn');

                // Load data for the tab if not already loaded
                if ($('#' + tabId + ' tbody').children().length === 0) {
                    loadTabData(tabId);
                }
            });

            // Load initial tab data
            loadTabData('sales');

            // Check for window resize and adjust mobile menu accordingly
            $(window).resize(function() {
                if ($(window).width() >= 768) {
                    $('#mobile-menu').removeClass('open');
                }
            });
        });

        // Function to simulate AJAX data loading
        function loadTabData(tabId) {
            $('#' + tabId + '-loading').show();
            if (tabId === 'sales') {} else if (tabId === 'purchases') {} else if (tabId === 'customers') {} else if (tabId === 'inventory') {
                loadinventory();
            } else if (tabId === 'reports') {
                loadreports();
            } else {
                setTimeout(function() {
                    $('#' + tabId + '-loading').hide();
                }, 1000);
            }
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Helper function to get status color
        function getStatusColor(status) {
            switch (status) {
                case 'Available':
                    return 'green';
                case 'Sold Out':
                    return 'blue';
                case 'Damage':
                    return 'yellow';
                case 'Not Available':
                    return 'gray';
                case 'Inactive':
                    return 'black';
                case 'Expired':
                    return 'red';
                default:
                    return 'gray';
            }
        }

        $('#add_item_form').validate({
            errorPlacement: function($error, $element) {
                $error.appendTo($element.closest("div"));
            },
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 30
                },
                sale_price: {
                    number: true,
                    min: 0
                }
            },
            messages: {
                name: {
                    required: "Name is required",
                    minlength: "Must be at least 3 characters.",
                    maxlength: "Must not be greater than 30 characters"
                },
                sale_price: {
                    number: "Please enter a valid price",
                    min: "Price cannot be negative"
                }
            },
            submitHandler: function(form) {
                const formData = $(form).serializeArray();
                $.ajax({
                    url: "{{ route('store_item') }}",
                    type: "POST",
                    data: formData,
                    beforeSend: function() {
                        // Optionally show a loading spinner
                    },
                    success: function(response) {
                        if (response.success) {
                            showFeedbackModal('success', 'Item Added!', 'Your item has been added successfully.');
                            $("#add_item_form")[0].reset();
                            loadinventory();
                            hide_add_item_modal();

                        } else {
                            showFeedbackModal('error', 'Submission Failed!', 'Failed to add item. Please try again.');
                        }
                    },
                    error: function(error) {
                        const response = error.responseJSON;
                        if (response && response.status === 'error' && response.conflicts) {
                            const message = `${response.message} ( : ${response.conflicts.name})`;
                            showFeedbackModal('error', 'Submission Failed!', message);
                        } else {
                            showFeedbackModal('error', 'Submission Failed!', 'There was an error adding the item. Please try again.');
                        }
                    }
                });
            }
        });

        const filter_inventory_Inputs = [
            'searchTable_items',
            'item_status_filter',
            'min_quantity',
            'max_quantity',
            'sort_items_by_Alphabet',
        ];

        filter_inventory_Inputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.addEventListener('change', function() {
                    loadinventory();
                });

                inputElement.addEventListener('keyup', debounce(function() {
                    loadinventory();
                }, 300));
            }
        });

        function loadinventory() {
            const filters = {
                search: document.getElementById('searchTable_items')?.value || '',
                status: document.getElementById('item_status_filter')?.value || '',
                min: document.getElementById('min_quantity')?.value || '',
                max: document.getElementById('max_quantity')?.value || '',
                sort_alphabetically: document.getElementById('sort_items_by_Alphabet')?.checked ? 1 : 0,
            };

            $('#inventory-loading').show();
            $('#inventory tbody').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('fetch_inventory') }}",
                data: filters,
                dataType: "json",
                success: function(response) {
                    const data = response.items || [];
                    const counts = response.counts || {};

                    // Update counts from data
                    $('#total_inventory').text(counts.total_inventory || '0');
                    $('#Available_count').text(counts.Available_count || '0');
                    $('#Not_Available_count').text(counts.Not_Available_count || '0');
                    $('#Expired_count').text(counts.Expired_count || '0');
                    $('#Damage_count').text(counts.Damage_count || '0');
                    $('#Sold_Out_count').text(counts.Sold_Out_count || '0');
                    $('#Inactive_count').text(counts.Inactive_count || '0');
                    $('#Active_count').text(counts.Active_count || '0');

                    let inventoryHtml = '';

                    if (data.length === 0) {
                        inventoryHtml += `<tr>
                            <td class="py-1 px-6 text-center text-red-600 italic" colspan="6">
                                No items found! Please register inventory.
                            </td>
                        </tr>`;
                    } else {
                        data.forEach(function(item) {
                            // Format the price with currency symbol
                            const formattedPrice = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'TZS'
                            }).format(item.sale_price || 0);

                            inventoryHtml += `
                                <tr class="bg-white border-b border-blue-200 hover:bg-gray-50">
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.sku || 'N/A'}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.name}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.category || 'General'}</td>
                                    <td class="py-2 px-6 text-right whitespace-nowrap">${item.current_stock || 0}</td>
                                    <td class="py-2 px-6 text-right whitespace-nowrap">${formattedPrice}</td>
                                    <td class="py-2 px-6 text-center whitespace-nowrap">
                                        <span class="bg-${getStatusColor(item.status)}-100 text-${getStatusColor(item.status)}-800 py-1 px-3 rounded-full text-xs">
                                            ${item.status || 'N/A'}
                                        </span>
                                    </td>
                                    <td class="px-6 py-2 text-center whitespace-nowrap">
                                        <div class="flex space-x-1 justify-center">
                                            <div class="relative group">
                                                <button value="${item.id}" data-id="${item.id}" onclick="show_EDIT_itemDialog(${item.id})" class="hover:text-green-500 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square scale-125 mt-1" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5.5 0 0 0 2.5 15h11a1.5.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </button>
                                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-green-700 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                                    Edit
                                                </span>
                                            </div>
                                            <span class="font-bold">|</span>
                                            <div class="relative group">
                                                <button value="${item.id}" data-id="${item.id}" onclick="show_delete_itemDialog(${item.id})" class="delete_item hover:text-red-500 text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash scale-125 mt-1 hover:text-red-500 text-red-800" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </button>
                                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-red-600 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                                    Delete
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    $('#inventory tbody').html(inventoryHtml);
                    $('#inventory-loading').hide();
                },
                error: function() {
                    $('#inventory tbody').html('<tr><td colspan="7" class="text-center py-4 text-red-500">Failed to load inventory data</td></tr>');
                    $('#inventory-loading').hide();
                }
            });
        }

        $('#edit_item_form').validate({
            errorPlacement: function($error, $element) {
                $error.appendTo($element.closest("div"));
            },
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 30
                },
                sku: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                sale_price: {
                    number: true,
                    min: 0
                }
            },
            messages: {
                name: {
                    required: "Name is required",
                    minlength: "Must be at least 3 characters.",
                    maxlength: "Must not be greater than 30 characters"
                },
                sku: {
                    required: "SKU is required",
                    minlength: "Must be at least 3 characters.",
                    maxlength: "Must not be greater than 50 characters"
                },
                sale_price: {
                    number: "Please enter a valid price",
                    min: "Price cannot be negative"
                }
            },
            submitHandler: function(form) {
                const itemId = $('#edit_item_id').val();
                const formData = $(form).serializeArray();

                $.ajax({
                    url: `/update_item/${itemId}`,
                    type: "PUT",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showFeedbackModal('success', 'Item Updated!', 'Your item has been updated successfully.');
                            loadinventory();
                            hide_EDIT_itemDialog();
                        } else {
                            showFeedbackModal('error', 'Update Failed!', 'Failed to update item. Please try again.');
                        }
                    },
                    error: function(error) {
                        const response = error.responseJSON;
                        if (response && response.status === 'error') {
                            showFeedbackModal('error', 'Update Failed!', response.message);
                        } else {
                            showFeedbackModal('error', 'Update Failed!', 'There was an error updating the item. Please try again.');
                        }
                    }
                });
            }
        });

        $(document).on('click', '.delete_Item_btn', function(e) {
            e.preventDefault();
            var item_id = $('#delete_Item_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/delete_item/" + item_id,
                success: function(response) {
                    hide_delete_itemDialog();
                    showFeedbackModal('success', 'Item Deleted!', 'Your item has been Deleted successfully.');
                    loadinventory();
                },
                error: function(error) {
                    const response = error.responseJSON;
                    if (response && response.status === 'error') {
                        showFeedbackModal('error', 'Deleting Failed!', 'There was an error Deleting the item. Please try again.');
                    }
                }
            });
        });


















































































































































        function show_add_sales_modal() {
            let dialog = document.getElementById('add_sales_modal');
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');
        }

        function hide_add_sales_modal() {
            let dialog = document.getElementById('add_sales_modal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        function show_add_purchase_modal() {
            let dialog = document.getElementById('add_purchase_modal');
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');
        }

        function hide_add_purchase_modal() {
            let dialog = document.getElementById('add_purchase_modal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }


        function show_add_item_modal() {
            let dialog = document.getElementById('add_item_modal');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling when modal is open
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');

            // Add animation
            const modalContent = dialog.querySelector('div');
            modalContent.classList.add('animate-fadeIn');

            // Add event listener to close modal when clicking outside
            dialog.addEventListener('click', function(event) {
                if (event.target === dialog) {
                    hide_add_item_modal();
                }
            });

            // Add escape key listener
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    hide_add_item_modal();
                }
            });
        }

        function hide_add_item_modal() {
            let dialog = document.getElementById('add_item_modal');
            const modalContent = dialog.querySelector('div');

            // Add fade out animation
            modalContent.classList.add('animate-fadeOut');

            setTimeout(() => {
                dialog.classList.add('hidden');
                dialog.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
                modalContent.classList.remove('animate-fadeOut', 'animate-fadeIn');
            }, 300);
        }

        function show_EDIT_itemDialog(itemId) {
            $.ajax({
                type: "GET",
                url: "/edit_item_details/" + itemId,
                success: function(response) {
                    var item = response.data;
                    $('#Edit_item_name').val(item.name);
                    $('#Edit_sku').val(item.sku);
                    $('#Edit_status').val(item.status);
                    $('#Edit_item_description').val(item.description);
                    $('#Edit_sale_price').val(item.sale_price ? item.sale_price : '0.000');

                    $('#edit_item_id').val(item.id);

                },
                error: function(error) {
                    console.error(error);
                }
            });
            let dialog = document.getElementById('edit_item_modal');
            document.body.classList.add('overflow-hidden'); // Prevent scrolling when modal is open
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');

            // Add animation
            const modalContent = dialog.querySelector('div');
            modalContent.classList.add('animate-fadeIn');

            // Add event listener to close modal when clicking outside
            dialog.addEventListener('click', function(event) {
                if (event.target === dialog) {
                    hide_EDIT_itemDialog();
                }
            });

            // Add escape key listener
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    hide_EDIT_itemDialog();
                }
            });
        }

        function hide_EDIT_itemDialog() {
            let dialog = document.getElementById('edit_item_modal');
            const modalContent = dialog.querySelector('div');

            // Add fade out animation
            modalContent.classList.add('animate-fadeOut');

            setTimeout(() => {
                dialog.classList.add('hidden');
                dialog.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
                modalContent.classList.remove('animate-fadeOut', 'animate-fadeIn');
            }, 300);
        }

        function show_delete_itemDialog(itemId) {
            // Set the item ID to delete
            $('#delete_Item_id').val(itemId);
            // Show the modal
            const modal = document.getElementById('DELETE_ItemModal');
            document.body.classList.add('overflow-hidden'); // Prevent background scrolling

            // Display the modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Add animation
            const modalContent = modal.querySelector('.relative');
            modalContent.classList.add('animate-modal-in');

            // Add event listener to close modal when clicking outside
            modal.addEventListener('click', function(event) {
                if (event.target === modal || event.target === modal.querySelector('.absolute')) {
                    hide_delete_itemDialog();
                }
            });

            // Add escape key listener
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    hide_delete_itemDialog();
                }
            });
        }

        function hide_delete_itemDialog() {
            const modal = document.getElementById('DELETE_ItemModal');
            const modalContent = modal.querySelector('.relative');

            // Add fade out animation
            modalContent.classList.add('animate-modal-out');

            // Delay hiding to allow animation to complete
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
                modalContent.classList.remove('animate-modal-in', 'animate-modal-out');
            }, 200);
        }

        // Add these animation classes to your CSS
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                @keyframes fadeOut {
                    from { opacity: 1; transform: translateY(0); }
                    to { opacity: 0; transform: translateY(-20px); }
                }
                .animate-fadeIn {
                    animation: fadeIn 0.3s ease-out forwards;
                }
                .animate-fadeOut {
                    animation: fadeOut 0.3s ease-out forwards;
                }
            </style>
            `);

        function show_edit_SalesMODAL() {
            let dialog = document.getElementById('editSalesModal');
            dialog.classList.remove('hidden');
            dialog.classList.add('z-30');
            loadSale();
        }

        function hide_edit_SalesMODAL() {
            let dialog = document.getElementById('editSalesModal');
            dialog.classList.add('hidden');
            dialog.classList.remove('z-50');
            loadSale();
        }


        function show_edit_purchaseMODAL() {
            let dialog = document.getElementById('editPurchaseModal');
            dialog.classList.remove('hidden');
            dialog.classList.add('z-30');
            loadSale();
        }

        function hide_edit_purchaseMODAL() {
            let dialog = document.getElementById('editPurchaseModal');
            dialog.classList.add('hidden');
            dialog.classList.remove('z-50');
            loadSale();
        }



        // Unified TableRowManager class for all modals
        class TableRowManager {
            constructor(modalType) {
                this.modalType = modalType; // 'sales' or 'purchase'
                this.bindEvents();
            }

            // Add row for the main modals (regular sales or purchases)
            addRow(button) {
                // Determine template based on modal type
                const newRow = this.getRowTemplate();

                if (button) {
                    // Get the closest tr (table row) element
                    const tr = $(button).closest('tr');
                    // Insert the new row after the current row
                    tr.after(newRow);
                } else {
                    // If no button provided, append to the end of the table
                    $('#table1 tbody').append(newRow);
                }

                // Update totals
                totalFn();
            }

            // Delete row for the main modals (regular sales or purchases)
            delRow(button) {
                const tbody = $('#table1 tbody');
                const rowCount = tbody.children('tr').length;

                // Prevent deleting the last row
                if (rowCount > 1) {
                    $(button).closest('tr').remove();
                    totalFn();
                } else {
                    // Show error message
                    showFeedbackModal('error', 'Error', 'Cannot delete the last row');
                }
            }

            // Add row for edit modals
            addRow_edit(button) {
                // Determine template based on modal type
                const newRow = this.getEditRowTemplate();

                if (button) {
                    $(button).closest('tr').after(newRow);
                } else {
                    $('#edit_items_container').append(newRow);
                }

                // Update totals
                this.updateEditTotals();
            }

            // Delete row for edit modals
            delRow_edit(button) {
                const rowCount = $('#edit_items_container tr').length;

                if (rowCount > 1) {
                    $(button).closest('tr').remove();
                    this.updateEditTotals();
                } else {
                    showFeedbackModal('error', 'Error', 'Cannot delete the last row');
                }
            }

            // Template for regular row
            getRowTemplate() {
                if (this.modalType === 'sales') {
                    return `
            <tr class="border-b border-gray-300">
                <td class="p-1">
                    <input disabled placeholder="0" class="av_quantity placeholder:text-gray-500 bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text" />
                </td>
                <td class="p-1 tbl-id">
                    <input onkeyup="netPriceFn(this)" placeholder="Qty" name="quantity" class="quantity border w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="number" />
                </td>
                <td class="p-1">
                    <div class="relative">
                        <input placeholder="Item" name="item_name" data-type="item_name" class="item_name border w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
                        <div class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                        <input type="hidden" class="item_id" name="item_id" />
                    </div>
                </td>
                <td class="p-1">
                    <textarea placeholder="Description" rows="1" class="description w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                </td>
                <td class="hidden md:table-cell p-1">
                    <input type="date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 border rounded w-full">
                </td>
                <td class="p-1">
                    <input onkeyup="netPriceFn(this)" name="Sales_price" placeholder="0.00 TZS" class="unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text" />
                </td>
                <td class="p-1">
                    <input onkeyup="netPriceFn(this)" name="discount" placeholder="0.00 TZS" class="discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                </td>
                <td class="ntl text-center p-1"><span>TSh</span>0.00</td>
                <td class="p-1">
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="tableManager.addRow(this)" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                            </svg>
                        </button>
                        <button type="button" onclick="tableManager.delRow(this)" class="text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            `;
                } else { // purchase
                    return `
            <tr class="border-b border-gray-300">
                <td class="p-1">
                    <input disabled placeholder="0" class="av_quantity placeholder:text-gray-500 bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text" />
                </td>
                <td class="p-1 tbl-id">
                    <input onkeyup="netPriceFn(this)" placeholder="Qty" name="quantity" class="quantity border w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="number" />
                </td>
                <td class="p-1">
                    <div class="relative">
                        <input placeholder="Item" name="item_name" data-type="item_name" class="item_name border w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
                        <div class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                        <input type="hidden" class="item_id" name="item_id" />
                    </div>
                </td>
                <td class="p-1">
                    <textarea placeholder="Description" rows="1" class="description w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                </td>
                <td class="p-1">
                    <input type="date" name="expire_date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 border rounded w-full">
                </td>
                <td class="p-1">
                    <input onkeyup="netPriceFn(this)" name="purchase_price" placeholder="0.00 TZS" class="unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text" />
                </td>
                <td class="p-1">
                    <input onkeyup="netPriceFn(this)" name="discount" placeholder="0.00 TZS" class="discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                </td>
                <td class="ntl text-center p-1"><span>TSh</span>0.00</td>
                <td class="p-1">
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="purchaseTableManager.addRow(this)" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                            </svg>
                        </button>
                        <button type="button" onclick="purchaseTableManager.delRow(this)" class="text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            `;
                }
            }

            // Template for edit modal row
            getEditRowTemplate() {
                if (this.modalType === 'sales') {
                    return `
            <tr class="border-b border-gray-300">
                <td class="p-1">
                    <input disabled value="0" class="av_quantity bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text">
                </td>
                <td class="p-1">
                    <input value="" name="quantities[]" class="edit_quantity w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" min="1" required>
                </td>
                <td class="p-1">
                    <div class="relative">
                        <input value="" name="edit_item_name[]" class="edit_item_name w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text">
                        <div class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                        <input type="hidden" name="item_ids[]" value="">
                    </div>
                </td>
                <td class="p-1">
                    <textarea row="1" class="w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                </td>
                <td class="p-1">
                    <input value="" name="Sales_prices[]" class="edit_unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01" required>
                </td>
                <td class="p-1">
                    <input value="0" name="discounts[]" class="edit_discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01">
                </td>
                <td class="edit_net_price text-center p-1">TSh 0.00</td>
                <td class="p-1">
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="tableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                            </svg>
                        </button>
                        <button type="button" onclick="tableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            `;
                } else { // purchase
                    return `
            <tr class="border-b border-gray-300">
                <td class="p-1">
                    <input disabled value="0" class="av_quantity bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text">
                </td>
                <td class="p-1">
                    <input value="" name="quantities[]" class="edit_quantity w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" min="1" required>
                </td>
                <td class="p-1">
                    <div class="relative">
                        <input value="" name="edit_item_name[]" class="edit_item_name w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text">
                        <div class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                        <input type="hidden" name="item_ids[]" value="">
                    </div>
                </td>
                <td class="p-1">
                    <textarea row="1" class="w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                </td>
                <td class="p-1">
                    <input type="date" value="" name="expire_dates[]" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border w-full">
                </td>
                <td class="p-1">
                    <input value="" name="purchase_prices[]" class="edit_unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01" required>
                </td>
                <td class="p-1">
                    <input value="0" name="discounts[]" class="edit_discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01">
                </td>
                <td class="edit_net_price text-center p-1">TSh 0.00</td>
                <td class="p-1">
                    <div class="flex justify-center gap-2">
                        <button type="button" onclick="purchaseTableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                            </svg>
                        </button>
                        <button type="button" onclick="purchaseTableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
            `;
                }
            }

            // Update totals for edit modal
            updateEditTotals() {
                let total = 0;
                $('#edit_items_container tr').each(function() {
                    const quantity = parseFloat($(this).find('.edit_quantity').val()) || 0;
                    const price = parseFloat($(this).find('.edit_unit_price').val()) || 0;
                    const discount = parseFloat($(this).find('.edit_discount').val()) || 0;
                    const netPrice = (quantity * price) - discount;

                    $(this).find('.edit_net_price').text(`TSh ${netPrice.toFixed(2)}`);
                    total += netPrice;
                });

                const paid = parseFloat($('#edit_paid').val()) || 0;
                const debt = total - paid;

                $('#edit_total').text(`${total.toFixed(2)} Tzs`);
                $('#edit_debt').text(`${debt.toFixed(2)} Tzs`);
            }

            // Populate edit form with item data
            populateEditItemRow(item) {
                // Calculate current stock if item exists
                const currentStock = item?.item?.current_stock ?? 0;

                if (this.modalType === 'sales') {
                    return `
                <tr class="border-b border-gray-300">
                    <td class="p-1">
                        <input disabled value="${currentStock}" class="av_quantity bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text">
                    </td>
                    <td class="p-1">
                        <input value="${item?.quantity || ''}" name="quantities[]" class="edit_quantity w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" min="1" required>
                    </td>
                    <td class="p-1">
                        <div class="relative">
                            <input value="${item?.item?.name || ''}" name="edit_item_name[]" class="edit_item_name w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text">
                            <div class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                            <input type="hidden" name="item_ids[]" value="${item?.item_id || ''}">
                        </div>
                    </td>
                    <td class="p-1">
                        <textarea row="1" class="w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border">${item?.item?.description || ''}</textarea>
                    </td>
                    <td class="p-1">
                        <input value="${item?.sale_price || ''}" name="Sales_prices[]" class="edit_unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01" required>
                    </td>
                    <td class="p-1">
                        <input value="${item?.discount || '0'}" name="discounts[]" class="edit_discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01">
                    </td>
                    <td class="edit_net_price text-center p-1">TSh ${this.calculateNetPrice(item?.quantity || 0, item?.sale_price || 0, item?.discount || 0)}</td>
                    <td class="p-1">
                        <div class="flex justify-center gap-2">
                            <button type="button" onclick="tableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                            </button>
                            <button type="button" onclick="tableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                               <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
                } else { // purchase
                    return `
                <tr class="border-b border-gray-300">
                    <td class="p-1">
                        <input disabled value="${currentStock}" class="av_quantity bg-green-300 text-blue-800 py-1 w-10 text-center rounded-full" type="text">
                    </td>
                    <td class="p-1">
                        <input value="${item?.quantity || ''}" name="quantities[]" class="edit_quantity w-full px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" min="1" required>
                    </td>
                    <td class="p-1">
                        <div class="relative">
                            <input value="${item?.item?.name || ''}" name="edit_item_name[]" class="edit_item_name w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text">
                            <div class="itemslist absolute z-10 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                            <input type="hidden" name="item_ids[]" value="${item?.item_id || ''}">
                        </div>
                    </td>
                    <td class="p-1">
                        <textarea row="1" class="w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border">${item?.item?.description || ''}</textarea>
                    </td>
                    <td class="p-1">
                        <input type="date" value="${item?.expire_date || ''}" name="expire_dates[]" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border w-full">
                    </td>
                    <td class="p-1">
                        <input value="${item?.purchase_price || ''}" name="purchase_prices[]" class="edit_unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01" required>
                    </td>
                    <td class="p-1">
                        <input value="${item?.discount || '0'}" name="discounts[]" class="edit_discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01">
                    </td>
                    <td class="edit_net_price text-center p-1">TSh ${this.calculateNetPrice(item?.quantity || 0, item?.purchase_price || 0, item?.discount || 0)}</td>
                    <td class="p-1">
                        <div class="flex justify-center gap-2">
                            <button type="button" onclick="purchaseTableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>
                            </button>
                            <button type="button" onclick="purchaseTableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
                }
            }

            // Helper function to calculate net price for a row
            calculateNetPrice(quantity, price, discount) {
                try {
                    const qty = parseFloat(quantity) || 0;
                    const pr = parseFloat(price) || 0;
                    const disc = parseFloat(discount) || 0;
                    return ((qty * pr) - disc).toFixed(2);
                } catch (error) {
                    console.error('Error in calculateNetPrice:', error);
                    return "0.00";
                }
            }

            // Function to populate edit form with data
            populateEditForm(data) {
                if (this.modalType === 'sales') {
                    let formatted_date = '';
                    if (data?.sale_date) {
                        const date = new Date(data.sale_date);
                        formatted_date = date.toISOString().split('T')[0];
                    }

                    $('#edit_Sales_id').val(data.id);
                    $('#edit_date').val(formatted_date);
                    $('#edit_Customer').val(data.customer && data.customer.name ? data.customer.name : '');
                    $('#edit_Customer_id').val(data.customer && data.customer.id ? data.customer.id : '');
                    $('#edit_description').val(data.description);
                    $('#edit_paid').val(data.paid);

                    // Clear existing items
                    $('#edit_items_container').empty();

                    // Add sales items
                    if (data.sale_items && data.sale_items.length > 0) {
                        data.sale_items.forEach(item => {
                            $('#edit_items_container').append(this.populateEditItemRow(item));
                        });
                    } else {
                        // Add an empty row if no items found
                        this.addRow_edit();
                    }
                } else { // purchase
                    let formatted_date = '';
                    if (data?.purchase_date) {
                        const date = new Date(data.purchase_date);
                        formatted_date = date.toISOString().split('T')[0];
                    }

                    $('#edit_purchase_id').val(data.id);
                    $('#edit_date').val(formatted_date);
                    $('#edit_supplier').val(data.supplier && data.supplier.name ? data.supplier.name : '');
                    $('#edit_supplier_id').val(data.supplier && data.supplier.id ? data.supplier.id : '');
                    $('#edit_description').val(data.description);
                    $('#edit_paid').val(data.paid);

                    // Clear existing items
                    $('#edit_items_container').empty();

                    // Add purchase items
                    if (data.purchase_items && data.purchase_items.length > 0) {
                        data.purchase_items.forEach(item => {
                            $('#edit_items_container').append(this.populateEditItemRow(item));
                        });
                    } else {
                        // Add an empty row if no items found
                        this.addRow_edit();
                    }
                }

                // Update totals
                this.updateEditTotals();
            }

            // Bind events
            bindEvents() {
                // Event listeners for real-time updates in edit modal
                $(document).on('input', '.edit_quantity, .edit_unit_price, .edit_discount, #edit_paid', () => {
                    this.updateEditTotals();
                });

                // Event listeners for real-time updates in regular modal
                $(document).on('input', '.quantity, .unit_price, .discount, .paid', function() {
                    try {
                        netPriceFn(this);
                    } catch (error) {
                        console.error('Error in input event handler:', error);
                    }
                });
            }
        }

        // Create instances for sales and purchase modals
        const tableManager = new TableRowManager('sales');
        const purchaseTableManager = new TableRowManager('purchase');
    </script>
</body>

</html>