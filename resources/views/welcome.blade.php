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

        /* Add this to your CSS file */
        .LoadingState {
            display: inline-block;
            vertical-align: middle;
            border-width: 2px;
            border-style: dashed;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Different sizes for different button sizes */
        button.sm .LoadingState {
            width: 12px;
            height: 12px;
        }

        button.md .LoadingState,
        button:not(.sm):not(.lg) .LoadingState {
            width: 16px;
            height: 16px;
        }

        button.lg .LoadingState {
            width: 20px;
            height: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Ensure buttons maintain their size during loading state */
        button[data-loading="true"] {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            pointer-events: none !important;
            opacity: 0.8;
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

        /* Common dropdown styles */
        .Customerlist,
        .supplierlist {
            display: none;
            max-height: 250px;
            overflow-y: auto;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 50;
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            margin-top: 0.25rem;
        }

        /* Scrollbar styling */
        .Customerlist::-webkit-scrollbar,
        .supplierlist::-webkit-scrollbar {
            width: 6px;
        }

        .Customerlist::-webkit-scrollbar-track,
        .supplierlist::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .Customerlist::-webkit-scrollbar-thumb,
        .supplierlist::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .Customerlist::-webkit-scrollbar-thumb:hover,
        .supplierlist::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* List items */
        .Customerlist ul,
        .supplierlist ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .Customer_li,
        .supplier_li,
        .items_lists {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
        }

        .Customer_li:last-child,
        .supplier_li:last-child,
        .items_lists:last-child {
            border-bottom: none;
        }

        .Customer_li:hover,
        .supplier_li:hover,
        .items_lists:hover {
            background-color: #edf2f7;
        }

        /* Item specific styles */
        .items_lists {
            padding: 0.75rem;
        }

        .items_lists .item-details {
            display: flex;
            flex-direction: column;
        }

        .items_lists .item-name {
            font-weight: 500;
            color: #1a202c;
        }

        .items_lists .item-stock {
            font-size: 0.75rem;
        }

        .items_lists .item-stock {
            font-weight: 600;
            margin-left: auto;
        }

        .items_lists .stock-low {
            color: #ed8936;
        }

        .items_lists .stock-out {
            color: #e53e3e;
        }

        .items_lists .stock-available {
            color: #38a169;
        }

        /* Animation for dropdowns */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .Customerlist.show,
        .supplierlist.show {
            display: block;
            animation: fadeIn 0.2s ease-out;
        }

        /* Style for empty results */
        .empty-result {
            padding: 1rem;
            text-align: center;
            color: #718096;
            font-style: italic;
        }

        /* Focus styles for input fields */
        input[type="text"]:focus+.Customerlist,
        input[type="text"]:focus+.supplierlist,
        input[type="text"]:focus+.itemslist {
            border-color: #4299e1;
        }

        /* Updated dropdown styles specifically for items in tables */
        .itemslist {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1050;
            /* Higher z-index to appear above table content */
            width: auto;
            min-width: 250px;
            /* Ensure minimum width */
            max-height: 200px;
            overflow-y: auto;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-top: 2px;
        }


        /* Item list styling */
        .item-search-results {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .items_lists {
            padding: 8px 12px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .items_lists:last-child {
            border-bottom: none;
        }

        .items_lists:hover {
            background-color: #f8fafc;
        }

        .item-name {
            font-weight: 500;
            color: #1e293b;
        }

        .no-results {
            padding: 8px 12px;
            color: #64748b;
            text-align: center;
            font-style: italic;
        }

        #transaction_table_container,
        #parties_table_container {
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 auto;
            /* center horizontally if using a set width */
            max-width: 100%;
            /* prevents overflow beyond viewport */
            width: 100%;
            /* makes it full width of parent container */
            max-height: 350px;

            /* Optional for responsiveness */
            box-sizing: border-box;
            padding: 1rem;
            /* add some spacing inside */
        }

        #print_Invoice,
        #print_Transactio,
        #print_Purchase_Invoice {
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 auto;
            /* center horizontally if using a set width */
            max-width: 100%;
            /* prevents overflow beyond viewport */
            width: 100%;
            /* makes it full width of parent container */
            max-height: 480px;

            /* Optional for responsiveness */
            box-sizing: border-box;
            padding: 1rem;
            /* add some spacing inside */
        }

        #stats_container {
            overflow: auto;
            /* Enable both horizontal and vertical scrolling */
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on iOS */
            margin: 0 auto;
            /* Centers the container */
            max-width: 100%;
            /* Ensures the container width does not exceed viewport width */
            max-height: 490px;
            /* Adjust height as needed */
        }
    </style>
</head>

<body class="bg-gray-400 min-h-screen">
    <!-- Fixed Navbar -->
    <nav class="fixed top-0 left-0 right-0 bg-amber-900 text-white shadow-lg z-10">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="text-xl font-bold navbar-brand animate__animated animate__fadeIn transform transition mr-5 hover:-translate-x-2 duration-300 cursor-pointer">
                    <span id="postCount" class=" absolute ml-6 flex h-3 w-3 right-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-600 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                    </span>
                    <a href="#" onclick="showEditpasswordModal()" class="capitalize underline font-bold font-serif px-3">
                        {{ collect(explode(' ', preg_replace('/[^a-zA-Z\s]/', '', auth()->user()->name)))->first() }}
                    </a>
                </div>
                <div class="hidden md:flex space-x-4 animate__animated animate__fadeIn">
                    <a href="#" class="hover:text-indigo-200 transition-all duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe2 mt-1.5 scale-125" viewBox="0 0 16 16">
                            <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855q-.215.403-.395.872c.705.157 1.472.257 2.282.287zM4.249 3.539q.214-.577.481-1.078a7 7 0 0 1 .597-.933A7 7 0 0 0 3.051 3.05q.544.277 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9 9 0 0 1-1.565-.667A6.96 6.96 0 0 0 1.018 7.5zm1.4-2.741a12.3 12.3 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332M8.5 5.09V7.5h2.99a12.3 12.3 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.6 13.6 0 0 1 7.5 10.91V8.5zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741zm-3.282 3.696q.18.469.395.872c.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a7 7 0 0 1-.598-.933 9 9 0 0 1-.481-1.079 8.4 8.4 0 0 0-1.198.49 7 7 0 0 0 2.276 1.522zm-1.383-2.964A13.4 13.4 0 0 1 3.508 8.5h-2.49a6.96 6.96 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667m6.728 2.964a7 7 0 0 0 2.275-1.521 8.4 8.4 0 0 0-1.197-.49 9 9 0 0 1-.481 1.078 7 7 0 0 1-.597.933M8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855q.216-.403.395-.872A12.6 12.6 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.96 6.96 0 0 0 14.982 8.5h-2.49a13.4 13.4 0 0 1-.437 3.008M14.982 7.5a6.96 6.96 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008zM11.27 2.461q.266.502.482 1.078a8.4 8.4 0 0 0 1.196-.49 7 7 0 0 0-2.275-1.52c.218.283.418.597.597.932m-.488 1.343a8 8 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z" />
                        </svg>
                    </a>
                    <a href="{{ route('welcome') }}" class="hover:text-indigo-200 transition-all duration-300 transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise mt-1.5 scale-125" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                        </svg>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="lg:inline-block lg:ml-auto lg:mr-3 py-1 px-6 bg-gray-100 hover:bg-gray-200 text-xs text-gray-900 hover:text-red-600 font-bold rounded-xl transition duration-200">
                            Logout
                        </button>
                    </form>
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
                <a href="#" class="block py-2 hover:text-indigo-200 transition-all duration-300 transform hover:translate-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-globe2 mt-1.5 scale-125 ml-1" viewBox="0 0 16 16">
                        <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855q-.215.403-.395.872c.705.157 1.472.257 2.282.287zM4.249 3.539q.214-.577.481-1.078a7 7 0 0 1 .597-.933A7 7 0 0 0 3.051 3.05q.544.277 1.198.49zM3.509 7.5c.036-1.07.188-2.087.436-3.008a9 9 0 0 1-1.565-.667A6.96 6.96 0 0 0 1.018 7.5zm1.4-2.741a12.3 12.3 0 0 0-.4 2.741H7.5V5.091c-.91-.03-1.783-.145-2.591-.332M8.5 5.09V7.5h2.99a12.3 12.3 0 0 0-.399-2.741c-.808.187-1.681.301-2.591.332zM4.51 8.5c.035.987.176 1.914.399 2.741A13.6 13.6 0 0 1 7.5 10.91V8.5zm3.99 0v2.409c.91.03 1.783.145 2.591.332.223-.827.364-1.754.4-2.741zm-3.282 3.696q.18.469.395.872c.552 1.035 1.218 1.65 1.887 1.855V11.91c-.81.03-1.577.13-2.282.287zm.11 2.276a7 7 0 0 1-.598-.933 9 9 0 0 1-.481-1.079 8.4 8.4 0 0 0-1.198.49 7 7 0 0 0 2.276 1.522zm-1.383-2.964A13.4 13.4 0 0 1 3.508 8.5h-2.49a6.96 6.96 0 0 0 1.362 3.675c.47-.258.995-.482 1.565-.667m6.728 2.964a7 7 0 0 0 2.275-1.521 8.4 8.4 0 0 0-1.197-.49 9 9 0 0 1-.481 1.078 7 7 0 0 1-.597.933M8.5 11.909v3.014c.67-.204 1.335-.82 1.887-1.855q.216-.403.395-.872A12.6 12.6 0 0 0 8.5 11.91zm3.555-.401c.57.185 1.095.409 1.565.667A6.96 6.96 0 0 0 14.982 8.5h-2.49a13.4 13.4 0 0 1-.437 3.008M14.982 7.5a6.96 6.96 0 0 0-1.362-3.675c-.47.258-.995.482-1.565.667.248.92.4 1.938.437 3.008zM11.27 2.461q.266.502.482 1.078a8.4 8.4 0 0 0 1.196-.49 7 7 0 0 0-2.275-1.52c.218.283.418.597.597.932m-.488 1.343a8 8 0 0 0-.395-.872C9.835 1.897 9.17 1.282 8.5 1.077V4.09c.81-.03 1.577-.13 2.282-.287z" />
                    </svg>
                </a>
                <a href="{{ route('welcome') }}" class="block py-2 hover:text-indigo-200 transition-all duration-300 transform hover:translate-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise mt-1.5 scale-125 ml-1" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                    </svg>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mb-1">
                    @csrf
                    <button type="submit" class="lg:inline-block lg:ml-auto lg:mr-3 py-1 px-6 bg-gray-100 hover:bg-gray-200 text-xs text-gray-900 hover:text-red-600 font-bold rounded-xl transition duration-200">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content with Tabs -->
    <main class="container mx-auto px-4 pt-24 pb-8">
        <h1 class="text-3xl font-bold mb-6 animate__animated animate__fadeInDown">Bobu store</h1>

        <!-- Tab Navigation -->
        <div class="flex flex-wrap border-b border-gray-200 mb-6 overflow-x-auto whitespace-nowrap pb-2 animate__animated animate__fadeIn">

            <button class="tab-button active px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="sales">
                Sales
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="purchases">
                Purchases
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="transaction">
                Transactions
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
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="employees">
                Employees
            </button>
        </div>

        <!-- Tab Content -->
        <div class="bg-gray-50 rounded-lg shadow-md p-4 sm:p-6 animate__animated animate__fadeIn">

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

                    </div>
                </div>
                <!-- Filters -->
                <div class="flex flex-wrap gap-3 mb-1">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="Salesearch" placeholder="Search Customer,Item..."
                            class="w-full px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <fieldset class="border-2 border-gray-500 rounded-lg px-3 py-0.5">
                        <legend class="text-xs font-semibold">Start - End Date </legend>
                        <div class="flex gap-2 text-xs">
                            <input type="date" id="sale_startDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" id="sale_endDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </fieldset>
                </div>

                <div class="responsive-table">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">s/n</th>
                                <th class="py-3 px-6 text-left">ref.no</th>
                                <th class="py-3 px-6 text-left">Date</th>
                                <th class="py-3 px-6 text-left">Customer</th>
                                <th class="py-3 px-6 text-left">discount</th>
                                <th class="py-3 px-6 text-left">amount</th>
                                <th class="py-3 px-6 text-left">paid</th>
                                <th class="py-3 px-6 text-left">unpaid</th>
                                <th class="py-3 px-6 text-left">status</th>
                                <th class="py-3 px-6 text-left no_print">tools</th>
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

                    </div>
                </div>
                <!-- Filters -->
                <div class="flex flex-wrap gap-3 mb-1">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="Purchasessearch" placeholder="Search Customer..."
                            class="w-full px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <fieldset class="border-2 border-gray-500 rounded-lg px-3 py-0.5">
                        <legend class="text-xs font-semibold">Start - End Date </legend>
                        <div class="flex gap-2 text-xs">
                            <input type="date" id="PurchasesstartDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" id="PurchasesendDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </fieldset>
                </div>


                <div class="responsive-table">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">s/n</th>
                                <th class="py-3 px-6 text-left">ref.no</th>
                                <th class="py-3 px-6 text-left">Date</th>
                                <th class="py-3 px-6 text-left">Supplier</th>
                                <th class="py-3 px-6 text-left">discount</th>
                                <th class="py-3 px-6 text-left">total</th>
                                <th class="py-3 px-6 text-left">paid</th>
                                <th class="py-3 px-6 text-left">unpaid</th>
                                <th class="py-3 px-6 text-left">status</th>
                                <th class="py-3 px-6 text-left no_print">tools</th>
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

            <div id="transaction" class="tab-content">
                <div class=" flex justify-between">
                    <h2 class="text-xl font-semibold mb-1">Transaction Management</h2>

                    <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-md">
                        <div class="relative group">
                            <svg onclick="show_addTransactionModal()" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-slash-minus scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="m1.854 14.854 13-13a.5.5 0 0 0-.708-.708l-13 13a.5.5 0 0 0 .708.708M4 1a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 4 1m5 11a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 9 12" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Record</span>
                        </div>

                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-printer scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Print</span>
                        </div>

                    </div>
                </div>
                <!-- Filters -->
                <div class="flex flex-wrap gap-3 mb-1">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="transactionsearch" placeholder="Search transaction details..."
                            class="w-full px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <fieldset class="border-2 border-gray-500 rounded-lg px-3 py-0.5">
                        <legend class="text-xs font-semibold">Start - End Date </legend>
                        <div class="flex gap-2 text-xs">
                            <input type="date" id="transactionstartDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" id="transactionendDate"
                                class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </fieldset>
                </div>


                <div class="responsive-table" id="transaction_table_container">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left whitespace-nowrap">s/n</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">ref.no</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">Date</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">name</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">method</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">type</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">amount</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap no_print">tools</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            <!-- Table rows will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="transaction-loading" class=" mt-4 flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-800"></div>
                </div>
            </div>

            <div id="customers" class="tab-content">
                <div class=" flex justify-between">
                    <h2 class="text-xl font-semibold mb-1">Parties Database</h2>
                    <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-md">
                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" onclick="show_addPartyModal()" width="16" height="16" fill="currentColor" class="bi bi-person-add scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                                <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Add</span>
                        </div>
                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-printer scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Print</span>
                        </div>

                    </div>
                </div>
                <div class="p-1 border-b-2 border-gray-400 mb-2">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div>
                            <input type="text" id="partDetails_filterTable" placeholder="Search Name/Details"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                        </div>
                        <div>
                            <select id="filterby_status_inTable" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                                <option value="">Status (All)</option>
                                <option value="Paid">Paid</option>
                                <option value="Partial Paid">Partial Paid</option>
                                <option value="Unpaid">Unpaid</option>
                                <option value="Draft">Draft</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div>
                            <select id="filter_type_inTable" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                                <option value="">Type (All)</option>
                                <option value="Supplier">Supplier</option>
                                <option value="Customer">Customer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="responsive-table" id="parties_table_container">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left whitespace-nowrap">s/n</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">TIN</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">NAME</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">EMAIL</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">PHONE NUMBER</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">TYPE</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">AMOUNT</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">PAID</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">DEPT</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap">STATUS</th>
                                <th class="py-3 px-6 text-left whitespace-nowrap no_print">TOOLS</th>
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
                                <svg id="stock_btn" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up hidden scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
                                </svg>
                                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Stock</span>
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
                                        <th class="no-sort no_print text-cennter bg-green-500 py-3 px-2"><input type="checkbox" id="select_all_items" /></th>
                                        <th class="py-3 px-6 text-left">s/n</th>
                                        <th class="py-3 px-6 text-left">SKU</th>
                                        <th class="py-3 px-6 text-left">Product</th>
                                        <th class="py-3 px-6 text-left">Category</th>
                                        <th class="py-3 px-6 text-left">Stock</th>
                                        <th class="py-3 px-6 text-left">Price</th>
                                        <th class="py-3 px-6 text-left">Status</th>
                                        <th class="py-3 px-6 text-left no_print">Actions</th>
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

            <div id="reports" class="tab-content">
                <div class="flex justify-end mb-1">

                    <div class="flex items-center gap-3">
                        <!-- reports Stats Cards -->
                        <div class="grid grid-cols-4 gap-3 mr-4">
                            <div class="bg-white p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Purchases</div>
                                <div id="total_purchases" class="text-xs font-bold">0</div>
                            </div>
                            <div class="bg-green-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Income</div>
                                <div id="total_income" class="text-xs font-bold text-green-600">0</div>
                            </div>
                            <div class="bg-yellow-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Sales</div>
                                <div id="total_sales" class="text-xs font-bold text-yellow-600">0</div>
                            </div>
                            <div class="bg-red-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Expenses</div>
                                <div id="total_expenses" class="text-xs font-bold text-red-600">0</div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="w-full">
                    <!-- Filter Container with responsive grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <input type="date" id="from_Date"
                            class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <input type="date" id="to_Date"
                            class="px-3 py-1 border-2 border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="overflow-x-auto">
                            <div id="stats_container" class=" p-3 border-gray-500 bg-gray-300 border-2">
                                <!-- Statistics Content -->
                                <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-6">

                                    <!-- Financial Metrics Container -->
                                    <div id="financial_metrics_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Financial Overview</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                            <div class="bg-blue-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Revenue</div>
                                                <div id="fin_revenue" class="text-xs font-bold text-blue-600">0</div>
                                            </div>
                                            <div class="bg-green-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Gross Profit</div>
                                                <div id="fin_gross_profit" class="text-xs font-bold text-green-600">0</div>
                                                <div id="fin_gross_margin" class="text-xs text-gray-600">0%</div>
                                            </div>
                                            <div class="bg-red-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Expenses</div>
                                                <div id="fin_expenses" class="text-xs font-bold text-red-600">0</div>
                                            </div>
                                            <div class="bg-purple-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Net Profit</div>
                                                <div id="fin_net_profit" class="text-xs font-bold text-purple-600">0</div>
                                                <div id="fin_net_margin" class="text-xs text-gray-600">0%</div>
                                            </div>
                                        </div>
                                        <div class="h-64 mb-4">
                                            <canvas id="monthly_profit_chart"></canvas>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-gray-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Outstanding Receivables</div>
                                                <div id="fin_receivables" class="text-xs font-bold text-gray-800">0</div>
                                            </div>
                                            <div class="bg-gray-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Outstanding Payables</div>
                                                <div id="fin_payables" class="text-xs font-bold text-gray-800">0</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sales Analytics Container -->
                                    <div id="sales_analytics_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Sales Analytics</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <div class="h-64 mb-4">
                                                    <canvas id="monthly_sales_chart"></canvas>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="h-64 mb-4">
                                                    <canvas id="sales_by_day_chart"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Best Selling Products</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="best_selling_products_table" class="bg-white divide-y divide-gray-200">
                                                            <!-- Data will be inserted here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Sales by Status</h4>
                                                <div class="h-64">
                                                    <canvas id="sales_by_status_chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Inventory Health Container -->
                                    <div id="inventory_health_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Inventory Health</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="bg-blue-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Total Inventory Value</div>
                                                <div id="total_inventory_value" class="text-xs font-bold text-blue-600">0</div>
                                            </div>
                                            <div class="bg-yellow-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Low Stock Items</div>
                                                <div id="low_stock_count" class="text-xs font-bold text-yellow-600">0</div>
                                            </div>
                                            <div class="bg-red-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Out of Stock Items</div>
                                                <div id="out_of_stock_count" class="text-xs font-bold text-red-600">0</div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Dead Stock Items</h4>
                                                <div class="overflow-x-auto">
                                                    <div id="dead_stock_table" class="min-w-full">
                                                        <p class="text-center text-gray-500 py-4">Loading...</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Slow Moving Items</h4>
                                                <div class="overflow-x-auto">
                                                    <div id="slow_moving_table" class="min-w-full">
                                                        <p class="text-center text-gray-500 py-4">Loading...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6">
                                            <h4 class="text-md font-medium mb-2">Stock Turnover Rate</h4>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sold Qty</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Inventory</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Turnover Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="stock_turnover_table" class="bg-white divide-y divide-gray-200">
                                                        <!-- Data will be inserted here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Insights Container -->
                                    <div id="customer_insights_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Customer Insights</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                            <div class="bg-blue-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Total Customers</div>
                                                <div id="total_customers_count" class="text-xs font-bold text-blue-600">0</div>
                                            </div>
                                            <div class="bg-green-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">New Customers</div>
                                                <div id="new_customers_count" class="text-xs font-bold text-green-600">0</div>
                                            </div>
                                            <div class="bg-purple-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Retention Rate</div>
                                                <div id="retention_rate" class="text-xs font-bold text-purple-600">0%</div>
                                            </div>
                                            <div class="bg-indigo-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Avg Sale/Customer</div>
                                                <div id="avg_sale_per_customer" class="text-xs font-bold text-indigo-600">0</div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Top Customers</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="top_customers_table" class="bg-white divide-y divide-gray-200">
                                                            <!-- Data will be inserted here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Customer Debt</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unpaid Orders</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debt Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="customer_debt_table" class="bg-white divide-y divide-gray-200">
                                                            <!-- Data will be inserted here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Supplier Performance Container -->
                                    <div id="supplier_performance_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Supplier Performance</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Top Suppliers</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="top_suppliers_table" class="bg-white divide-y divide-gray-200">
                                                            <!-- Data will be inserted here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Supplier Debt</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unpaid Orders</th>
                                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debt Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="supplier_debt_table" class="bg-white divide-y divide-gray-200">
                                                            <!-- Data will be inserted here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6">
                                            <h4 class="text-md font-medium mb-2">Average Items Per Purchase</h4>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Count</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Items Count</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Items/Purchase</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="avg_items_table" class="bg-white divide-y divide-gray-200">
                                                        <!-- Data will be inserted here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Performance Container -->
                                    <div id="product_performance_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Product Performance</h3>

                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Highest Margin Products</h4>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 capitalized ">Product</th>
                                                                <th class="px-3 py-1 text-right text-xs font-medium text-gray-500 capitalized ">Price</th>
                                                                <th class="px-3 py-1 text-right text-xs font-medium text-gray-500 capitalized ">Cost</th>
                                                                <th class="px-3 py-1 text-right text-xs font-medium text-gray-500 capitalized ">Margin %</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="highest_margin_table" class="bg-white divide-y divide-gray-200">
                                                            <!-- Data will be inserted here -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Discount Impact</h4>
                                                <div class="h-64">
                                                    <canvas id="discount_impact_chart"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6">
                                            <h4 class="text-md font-medium mb-2">Profit Calculation</h4>
                                            <div class="overflow-x-auto">
                                                <div id="profit_table" class="min-w-full">
                                                    <p class="text-center text-gray-500 py-4">Loading...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Business KPIs Container -->
                                    <div id="business_kpis_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Business Performance KPIs</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="bg-indigo-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Sales Growth</div>
                                                <div id="sales_growth" class="text-xs font-bold">0%</div>
                                            </div>
                                            <div class="bg-green-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Profit Growth</div>
                                                <div id="profit_growth" class="text-xs font-bold">0%</div>
                                            </div>
                                            <div class="bg-purple-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Customer Growth</div>
                                                <div id="customer_growth" class="text-xs font-bold">0%</div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="bg-gray-50 p-4 rounded-md shadow-lg">
                                                <h4 class="text-md font-medium mb-3">Current Period</h4>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <div class="text-xs text-gray-500">Period</div>
                                                        <div id="current_period_dates" class="text-xs font-medium">-</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500">Sales</div>
                                                        <div id="current_period_sales" class="text-xs font-medium">0</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500">Profit</div>
                                                        <div id="current_period_profit" class="text-xs font-medium">0</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500">Customers</div>
                                                        <div id="current_period_customers" class="text-xs font-medium">0</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 p-4 rounded-md shadow-lg">
                                                <h4 class="text-md font-medium mb-3">Previous Period</h4>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <div class="text-xs text-gray-500">Period</div>
                                                        <div id="previous_period_dates" class="text-xs font-medium">-</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500">Sales</div>
                                                        <div id="previous_period_sales" class="text-xs font-medium">0</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500">Profit</div>
                                                        <div id="previous_period_profit" class="text-xs font-medium">0</div>
                                                    </div>
                                                    <div>
                                                        <div class="text-xs text-gray-500">Customers</div>
                                                        <div id="previous_period_customers" class="text-xs font-medium">0</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cash Flow Analysis Container -->
                                    <div id="cash_flow_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                                        <h3 class="text-lg font-medium mb-4 underline">Cash Flow Analysis</h3>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="bg-green-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Total Inflow</div>
                                                <div id="total_inflow" class="text-xs font-bold text-green-600">0</div>
                                            </div>
                                            <div class="bg-red-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Total Outflow</div>
                                                <div id="total_outflow" class="text-xs font-bold text-red-600">0</div>
                                            </div>
                                            <div class="bg-blue-50 p-3 rounded-md shadow-lg">
                                                <div class="text-xs text-gray-500">Net Cash Flow</div>
                                                <div id="net_cash_flow" class="text-xs font-bold">0</div>
                                            </div>
                                        </div>

                                        <div class="mb-6">
                                            <h4 class="text-md font-medium mb-2">Monthly Cash Flow</h4>
                                            <div class="h-64">
                                                <canvas id="monthly_cash_flow_chart"></canvas>
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-md font-medium mb-2">Cash Flow by Payment Method</h4>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Inflow</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Outflow</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="payment_methods_table" class="bg-white divide-y divide-gray-200">
                                                        <!-- Data will be inserted here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="employees" class="tab-content">
                <div class="flex justify-between mb-4">
                    <h2 class="text-xl font-semibold">Employees</h2>

                    <div class="flex items-center gap-3">
                        <!-- employees Stats Cards -->
                        <div class="grid grid-cols-4 gap-3 mr-4">
                            <div class="bg-white p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Total</div>
                                <div id="total_employees" class="text-lg font-bold">0</div>
                            </div>
                            <div class="bg-green-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Male</div>
                                <div id="male_count_employees" class="text-lg font-bold text-green-600">0</div>
                            </div>
                            <div class="bg-yellow-100 p-2 rounded-md shadow-lg text-center">
                                <div class="text-xs text-gray-500">Female</div>
                                <div id="female_count_employees" class="text-lg font-bold text-yellow-600">0</div>
                            </div>

                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-lg">
                            <div class="relative group">
                                <svg onclick="show_add_employee_Dialog()" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus-fill scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
                                </svg>
                                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Add</span>
                            </div>

                            <div class="relative group">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-printer scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                                </svg>
                                <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">print</span>
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
                                    id="search_emlpoyee_inTable"
                                    placeholder="Search by name, other details..."
                                    class="w-full px-3 h-10 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="employees" class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">S/N</th>
                                        <th class="py-3 px-6 text-left">Name</th>
                                        <th class="py-3 px-6 text-left">Phone</th>
                                        <th class="py-3 px-6 text-left">Email</th>
                                        <th class="py-3 px-6 text-left">Gender</th>
                                        <th class="py-3 px-6 text-left">Role</th>
                                        <th class="py-3 px-6 text-left">Status</th>
                                        <th class="py-3 px-6 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">
                                    <!-- Table rows will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="employees-loading" class="mt-4 flex justify-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-4 border-blue-800"></div>
                    </div>
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

    <!-- Sales Modal -->
    <div id="add_sales_modal" class="fixed inset-0 z-20 overflow-y-auto hidden items-center justify-center p-2 sm:p-4">
        <div class="fixed inset-0" onclick="hide_add_sales_modal()"></div>
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-3 sm:px-4 py-2 sm:py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-sm sm:text-base lg:text-lg font-semibold">New Sales</h1>
                <button onclick="hide_add_sales_modal()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-2 sm:p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-3 sm:space-y-4" id="sales_form">
                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-3">
                        <div class="w-full">
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="Description" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Description:</label>
                                <textarea id="add_sale_description" name="Description" rows="2" placeholder="Enter description" class="w-full flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="mt-2 sm:mt-0 px-3 sm:px-4 py-1.5 sm:py-2 text-white text-xs sm:text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto whitespace-nowrap" type="submit">Record Sale</button>
                    </div>

                    <!-- Customer and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="date" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Date:</label>
                                <input type="date" id="add_sale_date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Customer Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="search_Customer" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Customer:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-3 h-3 sm:w-4 sm:h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Customer" name="Customer" id="search_Customer" class="pl-7 sm:pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="Customerlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" class="Customer_ID" id="Customer_ID" name="Customer_id[]" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-3 sm:mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="record_sales_table" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Stock</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Qty</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Item</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Desc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left hidden md:table-cell font-medium">Expire</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Price</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Disc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Net</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-center font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tbl_id" class="border-b border-gray-300">
                                        <td class="p-1">
                                            <input disabled placeholder="0" class="av_quantity placeholder:text-gray-500 bg-green-300 text-blue-800 py-1 w-8 sm:w-10 text-center rounded-full" type="text" />
                                        </td>
                                        <td class="p-1 tbl-id">
                                            <input onkeyup="netPriceFn(this)" id="qtn" placeholder="Qty" name="quantity" class="quantity border w-12 sm:w-14 px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="number" />
                                        </td>
                                        <td class="p-1">
                                            <div class="relative">
                                                <input placeholder="Item" id="sale_item_name" name="item_name" data-type="item_name" class="item_name border w-full min-w-[60px] sm:min-w-[100px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
                                                <div id="itemslist" class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                                                <input type="hidden" class="item_id" name="item_id" />
                                            </div>
                                        </td>
                                        <td class="p-1">
                                            <textarea placeholder="Description" rows="1" class="description w-full min-w-[60px] sm:min-w-[80px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="Sales_price" placeholder="0.00" class="unit_price w-full min-w-[60px] sm:min-w-[80px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border cursor-not-allowed" type="text" readonly />
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="discount" placeholder="0.00" class="discount w-full min-w-[50px] sm:min-w-[70px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td id="ntl" class="ntl text-center p-1 text-xs sm:text-sm"><span class="">TSh</span>0.00</td>
                                        <td class="p-1">
                                            <div class="flex justify-center gap-1 sm:gap-2">
                                                <button type="button" onclick="tableManager.addRow(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                    </svg>
                                                </button>
                                                <button type="button" onclick="tableManager.delRow(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
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
                                        <td colspan="2" class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium text-xs sm:text-sm whitespace-nowrap">Paid:</td>
                                        <td class="p-1 sm:p-2">
                                            <input name="paid" placeholder="0.00 Tzs" id="add_sale_paid" class="paid w-full px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-1 sm:p-2">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <span class="text-xs sm:text-sm font-medium whitespace-nowrap">Debt:</span>
                                                <div class="text-red-600">
                                                    <span class="dept text-xs sm:text-sm" id="add_sale_debt">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="4" class="p-1 sm:p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="add_sale_total" class="font-bold text-green-700 text-xs sm:text-sm total_amount">0.00 <span>Tzs</span></span>
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

    <!-- Edit Sales Modal -->
    <div id="editSalesModal" class="fixed hidden inset-0 z-20 overflow-y-auto items-center justify-center p-2 sm:p-4">
        <div class="fixed inset-0" onclick="hide_edit_SalesMODAL()"></div>
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-3 sm:px-4 py-2 sm:py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-sm sm:text-base lg:text-lg font-semibold">Edit Sales</h1>
                <button onclick="hide_edit_SalesMODAL()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-2 sm:p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-3 sm:space-y-4" id="sales_edit_Sale_form">
                    <input type="hidden" id="sales_edit_Sales_id" name="Sales_id">

                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-3">
                        <div class="w-full">
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="sales_edit_description" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Description:</label>
                                <textarea id="sales_edit_description" name="description" rows="2" placeholder="Enter description" class="w-full flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="mt-2 sm:mt-0 px-3 sm:px-4 py-1.5 sm:py-2 text-white text-xs sm:text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto whitespace-nowrap" type="submit">Update Sale</button>
                    </div>

                    <!-- Customer and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="sales_edit_date" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Date:</label>
                                <input type="date" id="sales_edit_date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Customer Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="sales_edit_Customer" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Customer:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-3 h-3 sm:w-4 sm:h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Customer" name="Customer" id="sales_edit_Customer" class="pl-7 sm:pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="Customerlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" id="sales_edit_Customer_id" name="Customer_id" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-3 sm:mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="sales_edit_table" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Stock</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Qty</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Item</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Desc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Price</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Disc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Net</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-center font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="sales_edit_items_container">
                                    <!-- Items will be dynamically added here -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-400">
                                        <td colspan="2" class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium text-xs sm:text-sm whitespace-nowrap">Paid:</td>
                                        <td class="p-1 sm:p-2">
                                            <input name="paid" placeholder="0.00 Tzs" id="sales_edit_paid" class="edit_paid w-full px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-1 sm:p-2">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <span class="text-xs sm:text-sm font-medium whitespace-nowrap">Debt:</span>
                                                <div class="text-red-600">
                                                    <span class="edit_debt text-xs sm:text-sm" id="sales_edit_debt">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="3" class="p-1 sm:p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="sales_edit_total" class="font-bold text-green-700 text-xs sm:text-sm">0.00 <span>Tzs</span></span>
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

    <!-- Purchase Modal -->
    <div id="add_purchase_modal" class="fixed inset-0 z-20 overflow-y-auto hidden items-center justify-center p-2 sm:p-4">
        <div class="fixed inset-0" onclick="hide_add_purchase_modal()"></div>
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-3 sm:px-4 py-2 sm:py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-sm sm:text-base lg:text-lg font-semibold">New Purchase</h1>
                <button onclick="hide_add_purchase_modal()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-2 sm:p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-3 sm:space-y-4" id="purchases_form" action="">
                    @csrf
                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-3">
                        <div class="w-full">
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="Description" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Description:</label>
                                <textarea id="add_purchase_description" name="Description" rows="2" placeholder="Enter description" class="w-full flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="mt-2 sm:mt-0 px-3 sm:px-4 py-1.5 sm:py-2 text-white text-xs sm:text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto whitespace-nowrap" type="submit">Record Purchase</button>
                    </div>

                    <!-- Supplier and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="date" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Date:</label>
                                <input type="date" id="add_purchase_date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Supplier Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="search_supplier" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Supplier:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-3 h-3 sm:w-4 sm:h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Supplier" name="supplier" id="search_supplier" class="pl-7 sm:pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="supplierlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" class="Supplier_ID" id="Supplier_ID" name="Supplier_id[]" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-3 sm:mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="purchase_record_table" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Stock</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Qty</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Item</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Desc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Expire</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Price</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Disc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Net</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-center font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="tbl_id" class="border-b border-gray-300">
                                        <td class="p-1">
                                            <input disabled placeholder="0" class="av_quantity placeholder:text-gray-500 bg-green-300 text-blue-800 py-1 w-8 sm:w-10 text-center rounded-full" type="text" />
                                        </td>
                                        <td class="p-1 tbl-id">
                                            <input onkeyup="netPriceFn(this)" placeholder="Qty" name="quantity" class="quantity border w-12 sm:w-14 px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="number" />
                                        </td>
                                        <td class="p-1">
                                            <div class="relative">
                                                <input placeholder="Item" name="item_name" id="purchase_item_name" data-type="item_name" class="item_name border w-full min-w-[60px] sm:min-w-[100px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
                                                <div class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
                                                <input type="hidden" class="item_id" name="item_id" />
                                            </div>
                                        </td>
                                        <td class="p-1">
                                            <textarea placeholder="Description" rows="1" class="description w-full min-w-[60px] sm:min-w-[80px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
                                        </td>
                                        <td class="p-1">
                                            <input type="date" name="expire_date" class="px-1 sm:px-2 py-1 text-xs sm:text-sm bg-gray-100 border rounded w-full">
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="purchase_price" placeholder="0.00" class="unit_price w-full min-w-[60px] sm:min-w-[80px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text" />
                                        </td>
                                        <td class="p-1">
                                            <input onkeyup="netPriceFn(this)" name="discount" placeholder="0.00" class="discount w-full min-w-[50px] sm:min-w-[70px] px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td class="ntl text-center p-1 text-xs sm:text-sm"><span>TSh</span>0.00</td>
                                        <td class="p-1">
                                            <div class="flex justify-center gap-1 sm:gap-2">
                                                <button type="button" onclick="purchaseTableManager.addRow(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                                    </svg>
                                                </button>
                                                <button type="button" onclick="purchaseTableManager.delRow(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
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
                                        <td colspan="2" class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium text-xs sm:text-sm whitespace-nowrap">Paid:</td>
                                        <td class="p-1 sm:p-2">
                                            <input name="paid" placeholder="0.00 Tzs" id="add_purchase_paid" class="paid w-full px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-1 sm:p-2">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <span class="text-xs sm:text-sm font-medium whitespace-nowrap">Debt:</span>
                                                <div class="text-red-600">
                                                    <span class="dept text-xs sm:text-sm" id="add_purchase_debt">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="4" class="p-1 sm:p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="add_purchase_total" class="font-bold text-green-700 text-xs sm:text-sm total_amount">0.00 <span>Tzs</span></span>
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

    <!-- Edit Purchase Modal -->
    <div id="editPurchaseModal" class="fixed inset-0 z-20 overflow-y-auto hidden items-center justify-center p-2 sm:p-4">
        <div class="fixed inset-0" onclick="hide_edit_purchaseMODAL()"></div>
        <div class="relative bg-white border-2 border-amber-900 rounded-xl shadow-xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl overflow-hidden">
            <!-- Modal Header with Close Button -->
            <div class="sticky top-0 bg-white z-10 px-3 sm:px-4 py-2 sm:py-3 flex justify-between items-center border-b border-gray-200">
                <h1 class="text-sm sm:text-base lg:text-lg font-semibold">Edit Purchase</h1>
                <button onclick="hide_edit_purchaseMODAL()" class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content with Scrollable Area -->
            <div class="overflow-y-auto p-2 sm:p-4 max-h-[calc(100vh-8rem)]">
                <form method="post" class="space-y-3 sm:space-y-4" id="purchase_edit_purchases_form">
                    <input type="hidden" id="purchase_edit_purchase_id" name="purchase_id">

                    <!-- Description and Submit Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-3">
                        <div class="w-full">
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="purchase_edit_description" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Description:</label>
                                <textarea id="purchase_edit_description" name="description" rows="2" placeholder="Enter description" class="w-full flex-grow py-1 px-2 bg-gray-100 text-xs sm:text-sm rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <button class="mt-2 sm:mt-0 px-3 sm:px-4 py-1.5 sm:py-2 text-white text-xs sm:text-sm bg-gray-800 rounded hover:bg-gray-700 transition w-full sm:w-auto whitespace-nowrap" type="submit">Update Purchase</button>
                    </div>

                    <!-- Supplier and Date Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4">
                        <!-- Date Input -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="purchase_edit_date" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Date:</label>
                                <input type="date" id="purchase_edit_date" name="date" class="px-2 py-1 text-xs sm:text-sm bg-gray-100 rounded border border-gray-300 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Supplier Search -->
                        <div>
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2 items-start sm:items-center">
                                <label for="purchase_edit_supplier" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Supplier:</label>
                                <div class="relative w-full">
                                    <svg class="fill-current text-gray-500 w-3 h-3 sm:w-4 sm:h-4 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                    </svg>
                                    <input placeholder="Supplier" name="supplier" id="purchase_edit_supplier" class="pl-7 sm:pl-8 pr-2 py-1 bg-gray-100 text-xs sm:text-sm w-full rounded border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" type="text" />
                                    <div class="supplierlist absolute w-full bg-white shadow-lg rounded-md mt-1 z-50 max-h-48 overflow-y-auto"></div>
                                    <input type="hidden" id="purchase_edit_supplier_id" name="supplier_id" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="mt-3 sm:mt-4 border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="purchase_edit_table" class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="bg-green-200">
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Stock</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Qty</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Item</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Desc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Expire</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Price</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Disc</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium">Net</th>
                                        <th class="py-1 sm:py-2 px-1 sm:px-2 text-center font-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="purchase_edit_items_container">
                                    <!-- Items will be dynamically added here -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-400">
                                        <td colspan="2" class="py-1 sm:py-2 px-1 sm:px-2 text-left font-medium text-xs sm:text-sm whitespace-nowrap">Paid:</td>
                                        <td class="p-1 sm:p-2">
                                            <input name="paid" placeholder="0.00 Tzs" id="purchase_edit_paid" class="edit_paid w-full px-1 sm:px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
                                        </td>
                                        <td colspan="2" class="p-1 sm:p-2">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <span class="text-xs sm:text-sm font-medium whitespace-nowrap">Debt:</span>
                                                <div class="text-red-600">
                                                    <span class="edit_debt text-xs sm:text-sm" id="purchase_edit_debt">0.00</span>
                                                    <span class="text-xs sm:text-sm">Tzs</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="4" class="p-1 sm:p-2 text-right">
                                            <span class="text-xs sm:text-sm font-medium">Total: </span>
                                            <span id="purchase_edit_total" class="font-bold text-green-700 text-xs sm:text-sm">0.00 <span>Tzs</span></span>
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
                        <input type="text" name="name" placeholder="Item name" class="w-full px-3 py-2 border-2 border-gray-400 text-gray-700 bg-gray-200 rounded focus:outline-none focus:border-green-500">
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

    <div id="Sales_Modal" class="fixed inset-0 z-20 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-xl font-semibold">Sales Invoice</h3>

                <div class=" flex gap-3">
                    <div class="flex gap-16 bg-gray-300 p-2 rounded-md shadow-md">

                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                onclick="printInvoice()" class="bi bi-printer scale-125 hover:text-blue-600 hover:scale-150 duration-500 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Print</span>
                        </div>

                    </div>
                    <button onclick="close_Sales_Modal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="print_Invoice" class=" p-3 border-gray-500 border-2"></div>

        </div>
    </div>

    <div id="purchase_Modal" class="fixed inset-0 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="text-xl font-semibold">Purchase Invoice</h3>

                <div class=" flex gap-3">
                    <div class="flex gap-16 bg-gray-300 p-2 rounded-md shadow-md">

                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                onclick="print_PurchaseInvoice()" class="bi bi-printer scale-125 hover:text-blue-600 hover:scale-150 duration-500 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Print</span>
                        </div>

                    </div>
                    <button onclick="close_purchase_Modal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="print_Purchase_Invoice" class=" p-3 border-gray-500 border-2"></div>

        </div>
    </div>

    <!-- Add Party Modal -->
    <div id="addPartyModal" class="fixed inset-0 z-20 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 transition-opacity close-modal"></div>

            <div class="bg-white border-2 border-blue-700 rounded-lg max-w-md w-full mx-auto z-10 relative">
                <div class="flex justify-between items-center border-b px-4 py-3">
                    <h3 class="text-lg font-medium text-gray-900">Add New Party</h3>
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <form id="addPartyForm" class="px-4 py-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name*</label>
                            <input type="text" name="name" id="part_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="text-sm text-red-600 hidden name-error"></span>
                        </div>

                        <div class="col-span-2">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type*</label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="Supplier">Supplier</option>
                                <option value="Customer">Customer</option>
                            </select>
                            <span class="text-sm text-red-600 hidden type-error"></span>
                        </div>

                        <div class="col-span-2">
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="part_gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="text-sm text-red-600 hidden email-error"></span>
                        </div>

                        <div class="col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="vat_number" class="block text-sm font-medium text-gray-700">VAT Number</label>
                            <input type="text" name="vat_number" id="vat_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="tin_number" class="block text-sm font-medium text-gray-700">TIN Number</label>
                            <input type="text" name="tin_number" id="tin_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t">
                        <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Add Party
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Party Modal -->
    <div id="editPartyModal" class="fixed inset-0 z-20 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 close-modal"></div>

            <div class="bg-white border-green-700 border-2 rounded-lg max-w-md w-full mx-auto z-10 relative">
                <div class="flex justify-between items-center border-b px-4 py-3">
                    <h3 class="text-lg font-medium text-gray-900">Edit Party</h3>
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <form id="editPartyForm" class="px-4 py-5 space-y-4">
                    <input type="hidden" id="edit_party_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label for="edit_name" class="block text-sm font-medium text-gray-700">Name*</label>
                            <input type="text" name="name" id="edit_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="text-sm text-red-600 hidden edit-name-error"></span>
                        </div>

                        <div class="col-span-2">
                            <label for="edit_type" class="block text-sm font-medium text-gray-700">Type*</label>
                            <select name="type" id="edit_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="Supplier">Supplier</option>
                                <option value="Customer">Customer</option>
                            </select>
                            <span class="text-sm text-red-600 hidden edit-type-error"></span>
                        </div>

                        <div class="col-span-2">
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="edit_part_gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div>
                            <label for="edit_phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="edit_phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="edit_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="text-sm text-red-600 hidden edit-email-error"></span>
                        </div>

                        <div class="col-span-2">
                            <label for="edit_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" id="edit_address" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="edit_vat_number" class="block text-sm font-medium text-gray-700">VAT Number</label>
                            <input type="text" name="vat_number" id="edit_vat_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="edit_tin_number" class="block text-sm font-medium text-gray-700">TIN Number</label>
                            <input type="text" name="tin_number" id="edit_tin_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="edit_company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" name="company_name" id="edit_company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="edit_contact_person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                            <input type="text" name="contact_person" id="edit_contact_person" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t">
                        <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Update Party
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Party Modal -->
    <div id="deletePartyModal" class="fixed inset-0 z-20 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 close-modal"></div>

            <div class="bg-white border-2 border-red-700 rounded-lg max-w-md w-full mx-auto z-10 relative">
                <div class="flex justify-between items-center border-b px-4 py-3">
                    <h3 class="text-lg font-medium text-gray-900">Delete Party</h3>
                    <button type="button" class="close-modal text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <div class="px-4 py-5">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <div class="mt-3 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Delete Confirmation</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this party? This action will also delete all related sales, purchases, and transactions. This action cannot be undone.
                            </p>
                        </div>
                        <div class="mt-4 flex justify-center gap-3">
                            <form method="" action="#">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" id="delete_party_id">
                                <button type="button" class="close-modal inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none">
                                    Cancel
                                </button>
                                <button type="button" id="confirmDeleteParty" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addTransactionModal" class="fixed inset-0 hidden z-20 overflow-y-auto">
        <div class="min-h-screen px-4 flex items-center justify-center">
            <!-- Backdrop overlay -->
            <div class="fixed inset-0" onclick="hide_addTransactionModal()"></div>

            <!-- Modal container with compact size -->
            <div class="relative bg-white border-2 border-amber-800 rounded-lg shadow-xl max-w-md w-full mx-auto transform transition-all">
                <!-- Modal header with close button -->
                <div class="flex items-center justify-between border-b border-gray-200 px-3 py-2">
                    <h3 class="text-sm font-bold text-gray-800">Payments Out</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500" onclick="hide_addTransactionModal()">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="p-3">
                    <form method="post" id="payment_out_form">
                        @csrf
                        <!-- Transaction Date -->
                        <div class="mb-3">
                            <div class="sm:flex sm:items-center sm:justify-between">
                                <label for="transaction_date" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Transaction date:</label>
                                <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                    <input type="date" id="transaction_date" name="transaction_date"
                                        class="transaction_date border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                </div>
                            </div>
                        </div>

                        <!-- Other Payment Section -->
                        <fieldset class="border border-gray-500 rounded-lg px-2 py-2 mb-3">
                            <legend class="text-xs font-semibold px-1 flex items-center gap-1">
                                Other Payment
                                <input type="checkbox" class="other_payment h-3 w-3 text-blue-600" />
                            </legend>

                            <!-- Change this part in addTransactionModal -->
                            <div class="flex space-x-2 mb-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_type" value="Payment" class="payment_type_radio h-3 w-3 text-blue-600" checked />
                                    <span class="ml-1 text-xs">Payment</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_type" value="Receipt" class="payment_type_radio h-3 w-3 text-blue-600" />
                                    <span class="ml-1 text-xs">Receipt</span>
                                </label>
                                <input type="hidden" name="type" id="payment_type" value="Payment">
                            </div>

                            <div class="space-y-2">
                                <!-- Pay To -->
                                <div class="sm:flex sm:items-center sm:justify-between">
                                    <label for="parson_name" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Pay To:</label>
                                    <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                        <input type="text" id="parson_name" name="person_name" placeholder="Name"
                                            class="parson_name border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="sm:flex sm:items-center sm:justify-between">
                                    <label for="payment_amount" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Amount:</label>
                                    <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                        <input type="text" id="payment_amount" name="payment_amount" placeholder="0.00 Tzs"
                                            class="payment_amount border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="relative mb-3">
                            <!-- Journal Memo -->
                            <div class="sm:flex sm:items-start sm:justify-between mb-2">
                                <label for="journal_memo" class="block text-xs font-medium text-gray-700 sm:mt-1 mb-1 sm:mb-0">Journal memo:</label>
                                <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                    <textarea id="journal_memo" name="journal_memo" rows="2" placeholder="Payment for..."
                                        class="journal_memo w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 border border-gray-500 rounded"></textarea>
                                </div>
                            </div>

                            <!-- Method -->
                            <div class="sm:flex sm:items-center sm:justify-between mb-2">
                                <label for="method" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Method:</label>
                                <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                    <select id="method" name="method"
                                        class="method border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                        <option value="" selected>Choose Method Here!</option>
                                        <option>Cash</option>
                                        <option>Check</option>
                                        <option>Credit card</option>
                                        <option>Online</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <input type="number" id="dept_paid" name="dept_paid" placeholder="0.00 Tzs"
                                    class="px-2 py-1 w-24 border-2 border-blue-400 text-xs text-gray-800 bg-blue-100 rounded cursor-not-allowed" readonly>
                            </div>
                        </div>

                        <!-- Add Transaction Modal - Part Payment Fieldset -->
                        <fieldset class="border border-gray-500 rounded-lg px-2 py-2 mb-3">
                            <legend class="text-xs font-semibold px-1 flex items-center gap-1">
                                Part Payment
                                <input type="checkbox" class="part_payment h-3 w-3 text-blue-600" />
                            </legend>

                            <!-- Part Type Selection -->
                            <div class="flex space-x-2 mb-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="part_type_radio" value="Supplier" class="part_type_radio h-3 w-3 text-blue-600" checked />
                                    <span class="ml-1 text-xs">Supplier</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="part_type_radio" value="Customer" class="part_type_radio h-3 w-3 text-blue-600" />
                                    <span class="ml-1 text-xs">Customer</span>
                                </label>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <!-- Part Search -->
                                <div class="relative">
                                    <div class="relative">
                                        <svg class="fill-current text-gray-500 w-3 h-3 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                        </svg>
                                        <input placeholder="Search part" name="part" id="search_partFOR_payment"
                                            class="search_part rounded pl-7 pr-2 py-1 bg-gray-200 text-xs w-full sm:w-24"
                                            type="text" data-type="Supplier" />
                                        <div id="partlist" class="partlist absolute z-10 w-full"></div>
                                        <input type="hidden" class="Part_ID" id="Part_ID_payment" name="part_id" />
                                        <input type="hidden" class="Part_Type" id="Part_Type_payment" name="part_type" value="Supplier" />
                                    </div>
                                    {{ csrf_field() }}
                                </div>

                                <!-- Balance -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-exclamation-diamond text-red-600" viewBox="0 0 16 16">
                                            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z" />
                                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                        </svg>
                                    </div>
                                    <input disabled type="number" id="part_balance" name="part_balance" placeholder="0.00 Tzs"
                                        class="part_balance pl-7 pr-2 py-1 w-full sm:w-24 shadow-sm text-xs text-gray-900 bg-gray-300 rounded cursor-not-allowed">
                                </div>

                                <!-- Applied Amount -->
                                <input type="number" id="applied_amount" name="applied_amount" placeholder="0.00 Tzs"
                                    class="px-2 py-1 w-full sm:w-24 border-2 border-green-400 text-xs text-gray-800 bg-green-100 rounded">
                            </div>

                            <!-- Transactions Container -->
                            <div class="transactions-container">
                                <!-- Supplier Transactions Table -->
                                <div id="supplier_transactions_container" class="transactions_table_container overflow-x-auto max-h-32">
                                    <table class="w-full border border-blue-500 text-xs">
                                        <thead>
                                            <tr class="bg-blue-500 text-white">
                                                <th class="py-1 px-2 text-left"><span></span></th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Date</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Total</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Paid</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Unpaid</th>
                                            </tr>
                                        </thead>
                                        <tbody id="purchases_TableBody">
                                            <tr class="bg-white border-b border-blue-500">
                                                <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="6">
                                                    No purchases found! Please search Supplier first.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Customer Transactions Table -->
                                <div id="customer_transactions_container" class="transactions_table_container overflow-x-auto max-h-32 hidden">
                                    <table class="w-full border border-green-500 text-xs">
                                        <thead>
                                            <tr class="bg-green-500 text-white">
                                                <th class="py-1 px-2 text-left"><span></span></th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Date</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Total</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Paid</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Unpaid</th>
                                            </tr>
                                        </thead>
                                        <tbody id="Sale_TableBody">
                                            <tr class="bg-white border-b border-green-500">
                                                <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="6">
                                                    No sales found! Please search Customer first.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </fieldset>

                        <!-- Form Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded focus:outline-none focus:ring-1 focus:ring-green-500">
                                Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="editTransactionModal" class="fixed inset-0 z-20 hidden overflow-y-auto">
        <div class="min-h-screen px-4 flex items-center justify-center">
            <!-- Backdrop overlay -->
            <div class="fixed inset-0" onclick="hide_editTransactionModal()"></div>

            <!-- Modal container with compact size -->
            <div class="relative bg-white border-2 border-teal-700 rounded-lg shadow-xl max-w-md w-full mx-auto transform transition-all">
                <!-- Modal header with close button -->
                <div class="flex items-center justify-between border-b border-gray-200 px-3 py-2">
                    <h3 class="text-sm font-bold text-gray-800">Edit Transaction</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500" onclick="hide_editTransactionModal()">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="p-3">
                    <form id="edit_transaction_form" method="post">
                        @csrf
                        <input type="hidden" id="edit_transaction_id" name="transaction_id">

                        <!-- Transaction Date -->
                        <div class="mb-3">
                            <div class="sm:flex sm:items-center sm:justify-between">
                                <label for="edit_transaction_date" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Transaction date:</label>
                                <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                    <input type="date" id="edit_transaction_date" name="transaction_date"
                                        class="border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                </div>
                            </div>
                        </div>

                        <!-- Other Payment Section -->
                        <fieldset class="border border-gray-500 rounded-lg px-2 py-2 mb-3">
                            <legend class="text-xs font-semibold px-1 flex items-center gap-1">
                                Other Payment
                                <input type="checkbox" id="edit_other_paymentCHECKBOXID" class="edit_other_paymentCHECKBOX h-3 w-3 text-blue-600" />
                            </legend>

                            <!-- Change this part in editTransactionModal -->
                            <div class="flex space-x-2 mb-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_type_edit" value="Payment" class="payment_type_radioEDIT h-3 w-3 text-blue-600" checked />
                                    <span class="ml-1 text-xs">Payment</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_type_edit" value="Receipt" class="payment_type_radioEDIT h-3 w-3 text-blue-600" />
                                    <span class="ml-1 text-xs">Receipt</span>
                                </label>
                                <input type="hidden" name="type" id="payment_type_edit" value="Payment">
                            </div>

                            <div class="space-y-2">
                                <!-- Pay To -->
                                <div class="sm:flex sm:items-center sm:justify-between">
                                    <label for="edit_parson_name" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Pay To:</label>
                                    <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                        <input type="text" id="edit_parson_name" name="person_name" placeholder="Name"
                                            class="border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                    </div>
                                </div>

                                <!-- Amount -->
                                <div class="sm:flex sm:items-center sm:justify-between">
                                    <label for="edit_payment_amount" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Amount:</label>
                                    <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                        <input type="text" id="edit_payment_amount" name="payment_amount" placeholder="0.00 Tzs"
                                            class="border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Journal Memo -->
                        <div class="mb-3">
                            <div class="sm:flex sm:items-start sm:justify-between">
                                <label for="edit_journal_memo" class="block text-xs font-medium text-gray-700 sm:mt-1 mb-1 sm:mb-0">Journal memo:</label>
                                <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                    <textarea id="edit_journal_memo" name="journal_memo" rows="2" placeholder="Payment for..."
                                        class="w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 border border-gray-500 rounded"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Method -->
                        <div class="mb-3">
                            <div class="sm:flex sm:items-center sm:justify-between">
                                <label for="edit_method" class="block text-xs font-medium text-gray-700 sm:mb-0 mb-1">Method:</label>
                                <div class="mt-1 sm:mt-0 sm:ml-2 sm:w-2/3">
                                    <select id="edit_method" name="method"
                                        class="border border-gray-500 w-full px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded">
                                        <option value="" selected>Choose Method Here!</option>
                                        <option>Cash</option>
                                        <option>Check</option>
                                        <option>Credit card</option>
                                        <option>Online</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <!-- Edit Transaction Modal - Part Payment Fieldset -->
                        <fieldset class="border border-gray-500 rounded-lg px-2 py-2 mb-3">
                            <legend class="text-xs font-semibold px-1 flex items-center gap-1">
                                Part Payment
                                <input type="checkbox" id="edit_supplierANDcustomer_paymentCHECKBOXID" class="edit_other_paymentCHECKBOX h-3 w-3 text-blue-600" />
                            </legend>

                            <!-- Part Type Selection -->
                            <div class="flex space-x-2 mb-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="part_type_radioEDIT" value="Supplier" class="part_type_radioEDIT h-3 w-3 text-blue-600" checked />
                                    <span class="ml-1 text-xs">Supplier</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="part_type_radioEDIT" value="Customer" class="part_type_radioEDIT h-3 w-3 text-blue-600" />
                                    <span class="ml-1 text-xs">Customer</span>
                                </label>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <!-- Part Search -->
                                <div class="relative">
                                    <div class="relative">
                                        <svg class="fill-current text-gray-500 w-3 h-3 absolute top-1/2 left-2 transform -translate-y-1/2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                                        </svg>
                                        <input placeholder="Search part" name="part" id="search_partFOR_paymentEDIT"
                                            class="search_part rounded pl-7 pr-2 py-1 bg-gray-200 text-xs w-full sm:w-24"
                                            type="text" data-type="Supplier" />
                                        <div id="partlistEDIT" class="partlist absolute z-10 w-full"></div>
                                        <input type="hidden" class="Part_ID" id="Part_ID_paymentEDIT" name="part_id" />
                                        <input type="hidden" class="Part_Type" id="Part_Type_paymentEDIT" name="part_type" value="Supplier" />
                                    </div>
                                    {{ csrf_field() }}
                                </div>

                                <!-- Balance -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-exclamation-diamond text-red-600" viewBox="0 0 16 16">
                                            <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.48 1.48 0 0 1 0-2.098zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z" />
                                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                        </svg>
                                    </div>
                                    <input disabled type="number" id="part_balanceEDIT" name="part_balance" placeholder="0.00 Tzs"
                                        class="part_balance pl-7 pr-2 py-1 w-full sm:w-24 shadow-sm text-xs text-gray-900 bg-gray-300 rounded cursor-not-allowed">
                                </div>

                                <!-- Payment Amount -->
                                <input type="number" id="paidEDIT" name="dept_paid" placeholder="0.00 Tzs"
                                    class="px-2 py-1 w-full sm:w-24 border-2 border-blue-400 text-xs text-gray-800 bg-blue-100 rounded">
                            </div>

                            <!-- Transactions Container -->
                            <div class="transactions-container">
                                <!-- Supplier Transactions Table -->
                                <div id="supplier_transactions_containerEDIT" class="transactions_table_container overflow-x-auto max-h-32">
                                    <table class="w-full border border-blue-500 text-xs">
                                        <thead>
                                            <tr class="bg-teal-800 text-white">
                                                <th class="py-1 px-2 text-left"><span></span></th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Date</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Total</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Paid</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Unpaid</th>
                                            </tr>
                                        </thead>
                                        <tbody id="purchases_TableBodyEDIT">
                                            <tr class="bg-white border-b border-blue-500">
                                                <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="6">
                                                    No purchases found! Please search Supplier first.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Customer Transactions Table -->
                                <div id="customer_transactions_containerEDIT" class="transactions_table_container overflow-x-auto max-h-32 hidden">
                                    <table class="w-full border border-green-500 text-xs">
                                        <thead>
                                            <tr class="bg-teal-800 text-white">
                                                <th class="py-1 px-2 text-left"><span></span></th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Date</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Total</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Paid</th>
                                                <th class="py-1 px-2 text-left whitespace-nowrap text-xs">Unpaid</th>
                                            </tr>
                                        </thead>
                                        <tbody id="Sale_TableBodyEDIT">
                                            <tr class="bg-white border-b border-green-500">
                                                <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="6">
                                                    No sales found! Please search Customer first.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Form Buttons -->
                        <div class="flex justify-end gap-2 mt-3">
                            <button type="button" onclick="hide_editTransactionModal()"
                                class="px-3 py-1 bg-gray-200 text-gray-800 text-xs rounded hover:bg-gray-300 focus:outline-none focus:ring-1 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                Update Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="delete_sale_Modal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
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
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Delete sale</h2>
                    </div>

                    <button onclick="hide_delete_sale_Dialog()" class="p-2 hover:bg-red-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-5 h-5 text-gray-500 hover:text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-4 sm:px-6 py-4">
                <div class="space-y-2">
                    <p class="text-gray-600">Are you sure you want to delete this sale?</p>
                    <p class="text-red-600 font-medium">This action cannot be undone.</p>
                </div>

                <form method="" action="#" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_sale_id">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="hide_delete_sale_Dialog()" class="w-full sm:flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="delete_sale_btn w-full sm:flex-1 px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Delete sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="delete_purchase_Modal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
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
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Delete purchase</h2>
                    </div>

                    <button onclick="hide_delete_purchase_Dialog()" class="p-2 hover:bg-red-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-5 h-5 text-gray-500 hover:text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-4 sm:px-6 py-4">
                <div class="space-y-2">
                    <p class="text-gray-600">Are you sure you want to delete this purchase?</p>
                    <p class="text-red-600 font-medium">This action cannot be undone.</p>
                </div>

                <form method="" action="#" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_purchase_id">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="hide_delete_purchase_Dialog()" class="w-full sm:flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="delete_purchase_btn w-full sm:flex-1 px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Delete purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="delete_transaction_Modal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
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
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Delete transaction</h2>
                    </div>

                    <button onclick="hide_delete_transaction_Dialog()" class="p-2 hover:bg-red-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-5 h-5 text-gray-500 hover:text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-4 sm:px-6 py-4">
                <div class="space-y-2">
                    <p class="text-gray-600">Are you sure you want to delete this transaction?</p>
                    <p class="text-red-600 font-medium">This action cannot be undone.</p>
                </div>

                <form method="" action="#" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_transaction_id">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="hide_delete_transaction_Dialog()" class="w-full sm:flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="delete_transaction_btn w-full sm:flex-1 px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Delete transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="add_employee_Modal" class="fixed inset-0 z-20 hidden items-center justify-center">
        <div class="w-full max-w-xl rounded-lg bg-white p-6 shadow-xl">
            <div class="flex items-center justify-between pb-1">
                <h3 class="text-lg font-bold text-gray-900">Add New Employee</h3>
                <button onclick="hide_add_employee_Dialog()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div>
                <form id="addEmployeeForm" method="POST" action="/add_employee">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="name" class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                    </div>

                    <!-- Email Field (Optional) -->
                    <div class="mb-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" class="block w-full rounded-md border border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-500">Optional - User can add this later</p>
                    </div>

                    <!-- Phone Field (Optional) -->
                    <div class="mb-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                                255
                            </span>
                            <input type="text" name="phone" id="phone"
                                class="block w-full flex-1 rounded-none border rounded-r-md border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                                pattern="[0-9]{9}" maxlength="9" placeholder="712345678">
                        </div>
                        <p class=" text-xs text-gray-500">Optional - User will be prompted to add this upon login if missing</p>
                    </div>

                    <!-- Password Information Note -->
                    <div class="mb-2 p-3 bg-red-100 rounded-lg">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle text-teal-600 mr-1"></i>
                            Default password will be set to <strong>12345678</strong>
                        </p>
                    </div>

                    <!-- Role Field -->
                    <div class="mb-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" class=" border block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">Select Role</option>
                            @foreach(App\Models\User::User_Roles as $key => $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender Field -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <div class="mt-1 flex space-x-6">
                            <div class="flex items-center">
                                <input id="gender-male" name="gender" type="radio" value="Male" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-500">
                                <label for="gender-male" class="ml-2 block text-sm text-gray-700">Male</label>
                            </div>
                            <div class="flex items-center">
                                <input id="gender-female" name="gender" type="radio" value="Female" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-500">
                                <label for="gender-female" class="ml-2 block text-sm text-gray-700">Female</label>
                            </div>
                        </div>
                    </div>

                    <!-- Address Field -->
                    <div class="mb-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" rows="3" class="border block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hide_add_employee_Dialog()" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            Cancel
                        </button>
                        <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            Add Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div id="edit_employee_Modal" class="fixed inset-0 z-20 hidden items-center justify-center">
        <div class="w-full max-w-xl rounded-lg bg-white p-6 shadow-xl">
            <div class="flex items-center justify-between pb-1">
                <h3 class="text-lg font-bold text-gray-900">Edit Employee</h3>
                <button onclick="hide_edit_employee_Dialog()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div>
                <form id="editEmployeeForm" method="POST">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="employee_id" id="edit_employee_id">

                    <!-- Name Field -->
                    <div class="mb-2">
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="edit_user_name" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                    </div>

                    <!-- Email Field (Optional) -->
                    <div class="mb-2">
                        <label for="edit_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="edit_user_email" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-500">Optional - User can add this later</p>
                    </div>

                    <!-- Phone Field (Optional) -->
                    <div class="mb-2">
                        <label for="edit_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
                                255
                            </span>
                            <input type="text" name="phone" id="edit_phone"
                                class="block w-full flex-1 border rounded-none rounded-r-md border-gray-300 focus:border-teal-500 focus:ring-teal-500"
                                pattern="[0-9]{9}" maxlength="9" placeholder="712345678">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Optional - User will be prompted to add this upon login if missing</p>
                    </div>

                    <!-- Role Field -->
                    <div class="mb-2">
                        <label for="edit_role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="edit_role" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">Select Role</option>
                            @foreach(App\Models\User::User_Roles as $key => $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender Field -->
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <div class="mt-1 flex space-x-6">
                            <div class="flex items-center">
                                <input id="edit_gender_male" name="gender" type="radio" value="Male" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-500">
                                <label for="edit_gender_male" class="ml-2 block text-sm text-gray-700">Male</label>
                            </div>
                            <div class="flex items-center">
                                <input id="edit_gender_female" name="gender" type="radio" value="Female" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-500">
                                <label for="edit_gender_female" class="ml-2 block text-sm text-gray-700">Female</label>
                            </div>
                        </div>
                    </div>

                    <!-- Address Field -->
                    <div class="mb-2">
                        <label for="edit_address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" id="edit_user_address" rows="3" class="mt-1 border block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hide_edit_employee_Dialog()" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            Cancel
                        </button>
                        <button type="submit" class="rounded-md bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="delete_employeeModal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
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
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Delete Employee</h2>
                    </div>

                    <button onclick="hide_delete_employeeDialog()" class="p-2 hover:bg-red-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-5 h-5 text-gray-500 hover:text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-4 sm:px-6 py-4">
                <div class="space-y-2">
                    <p class="text-gray-600 flex">
                        Are you sure you want to delete
                        <span id="delete_employee_name" class="text-blue-700 ml-1 font-bold"></span>?
                    </p>

                    <p class="text-red-600 font-medium">This action cannot be undone.</p>
                </div>

                <form method="" action="#" class="mt-6">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_employee_id">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="hide_delete_employeeDialog()" class="w-full sm:flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="delete_employee_btn w-full sm:flex-1 px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Delete employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Password Modal -->
    <section id="EditpasswordModal" class="fixed inset-0 z-20 hidden items-center justify-center p-4">
        <div class="w-full max-w-md bg-white border-2 border-green-700 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Change Password
                </h2>
                <button onclick="hideEditpasswordModal()" class="text-gray-600 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="changePasswordForm" class="space-y-4">
                @csrf
                @method('post')
                <input type="hidden" id="userId" name="user_id" value="{{ auth()->user()->id }}">

                <div>
                    <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Current Password
                    </label>
                    <input type="password" name="current_password" id="current_password"
                        placeholder="••••••••"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>

                <div>
                    <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        New Password
                    </label>
                    <input type="password" name="new_password" id="new_password"
                        placeholder="••••••••"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>

                <div>
                    <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Confirm Password
                    </label>
                    <input type="password" name="confirm_password" id="confirm_password"
                        placeholder="••••••••"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>

                <button type="submit" class="w-full py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                    Reset Password
                </button>
            </form>
        </div>
    </section>

    <!-- Stock Modal -->
    <div id="stock_Modal" class="fixed inset-0 z-20 hidden overflow-auto items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b p-4 bg-gray-100 rounded-t-lg">
                <h3 class="text-xl font-semibold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-graph-up mr-2" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" />
                    </svg>
                    Stock Details (<span id="modal_selected_count">0</span> Items)
                </h3>
                <button onclick="hide_stock_Modal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Filter Section -->
            <div class="p-4 bg-gray-50 border-b">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="modal_status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
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

                    <!-- Date Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <div class="flex items-center space-x-2">
                            <input type="date" id="from_date" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="From">
                            <span class="text-gray-500">to</span>
                            <input type="date" id="to_date" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="To">
                        </div>
                    </div>

                    <!-- Period Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                        <select id="period_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Custom Period</option>
                            <option value="today">Today</option>
                            <option value="this_week">This Week</option>
                            <option value="last_week">Last Week</option>
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_year">This Year</option>
                            <option value="last_year">Last Year</option>
                        </select>
                    </div>

                    <!-- Quantity Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Range</label>
                        <div class="flex items-center space-x-2">
                            <input type="number" id="modal_min_quantity" class="flex-1 px-3 py-2 border border-gray-300 lg:w-10 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Min" min="0">
                            <span class="text-gray-500">to</span>
                            <input type="number" id="modal_max_quantity" class="flex-1 px-3 py-2 border border-gray-300 lg:w-10 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Max" min="0">
                        </div>
                    </div>

                    <!-- Search Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" id="modal_search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Search by name, SKU...">
                    </div>

                    <!-- Price Calculation Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price Calculation</label>
                        <select id="price_calculation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="latest">Latest Purchase Price</option>
                            <option value="oldest">Oldest Purchase Price</option>
                        </select>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex justify-end mt-4 gap-3">
                    <button id="apply_filters" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel mr-2" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z" />
                        </svg>
                        Apply Filters
                    </button>
                    <button id="reset_filters" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-counterclockwise mr-2" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z" />
                            <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466" />
                        </svg>
                        Reset
                    </button>
                    <button id="print_stock_report" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer mr-2" viewBox="0 0 16 16">
                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2z" />
                        </svg>
                        Print Report
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-4 overflow-auto">
                <!-- Stats Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg shadow">
                        <h4 class="text-sm text-gray-600">Total Items</h4>
                        <p id="modal_total_items" class="text-xl font-bold text-blue-700">0</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-lg shadow">
                        <h4 class="text-sm text-gray-600">Total Stock</h4>
                        <p id="modal_total_stock" class="text-xl font-bold text-green-700">0</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-lg shadow">
                        <h4 class="text-sm text-gray-600">Stock Value (Latest)</h4>
                        <p id="modal_stock_value_latest" class="text-xl font-bold text-purple-700">TZS 0</p>
                    </div>
                    <div class="bg-indigo-100 p-3 rounded-lg shadow">
                        <h4 class="text-sm text-gray-600">Stock Value (Oldest)</h4>
                        <p id="modal_stock_value_oldest" class="text-xl font-bold text-indigo-700">TZS 0</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-lg shadow">
                        <h4 class="text-sm text-gray-600">Avg. Item Price</h4>
                        <p id="modal_avg_price" class="text-xl font-bold text-orange-700">TZS 0</p>
                    </div>
                </div>

                <!-- Hidden input for selected items -->
                <input type="hidden" id="selected_items" name="selected_items" value="">

                <!-- Stock Table -->
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table id="stock_table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/N</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Latest Price</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Oldest Price</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Value</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="stock_table_body">
                            <!-- Stock data will be loaded dynamically via JavaScript -->
                            <tr class="text-center">
                                <td colspan="7" class="px-6 py-4 text-sm text-gray-500">Loading stock data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="border-t p-4 bg-gray-100 rounded-b-lg mt-auto">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Last updated: <span id="last_updated">Now</span>
                    </div>
                    <button onclick="hide_stock_Modal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <iframe id="printFrame_for_items_stocks" style="display: none; height: 0; width: 0;"></iframe>
    <iframe id="printFrame" style="display: none; height: 0; width: 0;"></iframe>

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
            if (tabId === 'sales') {
                loadSale();
            } else if (tabId === 'purchases') {
                loadPurchases();
            } else if (tabId === 'transaction') {
                loadTransactions();
            } else if (tabId === 'customers') {
                loadParties();
            } else if (tabId === 'inventory') {
                loadinventory();
            } else if (tabId === 'reports') {
                loadStatistics();
            } else if (tabId === 'employees') {
                loadEmployees();
            } else {
                setTimeout(function() {
                    $('#' + tabId + '-loading').hide();
                }, 1000);
            }
        }

        const filter_transaction_Inputs = [
            'transactionsearch',
            'transactionstartDate',
            'transactionendDate',
        ];

        filter_transaction_Inputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.addEventListener('change', function() {
                    loadTransactions();
                });

                inputElement.addEventListener('keyup', debounce(function() {
                    loadTransactions();
                }, 300));
            }
        });

        function loadTransactions() {
            const filters = {
                search: document.getElementById('transactionsearch')?.value || '',
                transactionstartDate: document.getElementById('transactionstartDate')?.value || '',
                transactionendDate: document.getElementById('transactionendDate')?.value || '',
            };

            $('#transaction-loading').show();
            $('#transaction tbody').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('fetch_transactions') }}",
                data: filters,
                dataType: "json",
                success: function(response) {
                    const data = response.items || [];

                    let transactionHtml = '';

                    if (data.length === 0) {
                        transactionHtml += `<tr>
                    <td class="py-1 px-6 text-center text-red-600 italic" colspan="10">
                        No transaction found! Please record transaction.
                    </td>
                </tr>`;
                    } else {
                        data.forEach(function(transaction, key) {
                            const date = new Date(transaction.transaction_date).toLocaleDateString();
                            const payment_amount = parseFloat(transaction.payment_amount || 0).toLocaleString('en-TZ', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            // Handle purchase details safely
                            let purchase_paid = " - ";
                            let purchase_debt = " - ";
                            let purchase_status = "-";

                            if (transaction.purchase) {
                                if (transaction.purchase.paid && parseFloat(transaction.purchase.paid) > 0) {
                                    purchase_paid = parseFloat(transaction.purchase.paid || 0).toLocaleString('en-TZ', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }

                                if (transaction.purchase.dept && parseFloat(transaction.purchase.dept) > 0) {
                                    purchase_debt = parseFloat(transaction.purchase.dept || 0).toLocaleString('en-TZ', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }

                                purchase_status = transaction.purchase.status || '-';
                            }

                            // Handle sale details safely
                            let sale_paid = " - ";
                            let sale_debt = " - ";
                            let sale_status = "-";

                            if (transaction.sale) {
                                if (transaction.sale.paid && parseFloat(transaction.sale.paid) > 0) {
                                    sale_paid = parseFloat(transaction.sale.paid || 0).toLocaleString('en-TZ', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }

                                if (transaction.sale.dept && parseFloat(transaction.sale.dept) > 0) {
                                    sale_debt = parseFloat(transaction.sale.dept || 0).toLocaleString('en-TZ', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }

                                sale_status = transaction.sale.status || '-';
                            }

                            // Add conditional styling based on transaction type
                            const typeClass = transaction?.type === 'Payment' ? 'text-red-600 font-medium' :
                                (transaction?.type === 'Receipt' ? 'text-green-600 font-medium' : '');

                            // Add conditional styling for transaction amount
                            const amountClass = transaction?.type === 'Payment' ? 'text-red-600 font-medium' :
                                (transaction?.type === 'Receipt' ? 'text-green-600 font-medium' : '');

                            transactionHtml += `
                <tr class="bg-white border-b border-blue-400 hover:bg-gray-50">
                    <td class="py-1 px-6 text-left whitespace-nowrap">${key + 1}</td>
                    <td class="py-1 px-6 text-left whitespace-nowrap">${transaction.reference_no || ' - '}</td>
                    <td class="py-1 px-6 text-left whitespace-nowrap">${date}</td>
                    <td class="py-1 px-6 text-left whitespace-nowrap">${transaction.part?.name || ' '} ${transaction?.person_name || ' '}</td>
                    <td class="py-1 px-6 text-left whitespace-nowrap">${transaction.method || ' - '}</td>
                    <td class="py-1 px-6 text-left whitespace-nowrap ${typeClass}">${transaction?.type || ' '}</td>
                    <td class="py-1 px-6 text-left whitespace-nowrap ${amountClass}">${payment_amount}/= Tzs</td>
                    <td class="px-6 py-1 text-left whitespace-nowrap no_print">
                        <div class="flex space-x-2">
                            <div class="relative group">
                                <button value="${transaction.id}" data-id="${transaction.id}" onclick="show_transactionPREVIEW(${transaction.id})" class="show_transactionPREVIEW hover:text-blue-300 text-blue-900">
                                    <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-blue-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                    Preview
                                </span>
                            </div>
                            <span class="font-bold">|</span>
                            <div class="relative group">
                                <button value="${transaction.id}" data-id="${transaction.id}" onclick="show_editTransactionModal(${transaction.id})" class="show_edit_transactionMODAL hover:text-green-500 text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square scale-125 mt-1" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5.5 0 0 0 2.5 15h11a1.5.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5.5 0 0 0 1 2.5z"/>
                                    </svg>
                                </button>
                                <!-- Tooltip -->
                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-green-700 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                    Edit
                                </span>
                            </div>

                            <span class="font-bold">|</span>

                            <!-- Delete Button -->
                            <div class="relative group">
                                <button value="${transaction.id}" data-id="${transaction.id}" onclick="show_delete_transaction_Dialog(${transaction.id})" class="show_delete_transaction_Dialog hover:text-red-500 text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash scale-125 mt-1 hover:text-red-500 text-red-800" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
                                </button>
                                <!-- Tooltip -->
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

                    $('#transaction tbody').html(transactionHtml);
                    $('#transaction-loading').hide();
                },
                error: function() {
                    $('#transaction tbody').html('<tr><td colspan="10" class="text-center py-4 text-red-500">Failed to load transaction data</td></tr>');
                    $('#transaction-loading').hide();
                }
            });
        }

        const filter_part_Inputs = [
            'partDetails_filterTable',
            'filterby_status_inTable',
            'filter_type_inTable',
        ];

        filter_part_Inputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.addEventListener('change', function() {
                    loadParties();
                });

                inputElement.addEventListener('keyup', debounce(function() {
                    loadParties();
                }, 300));
            }
        });

        function loadParties() {
            const filters = {
                search: document.getElementById('partDetails_filterTable')?.value || '',
                status: document.getElementById('filterby_status_inTable')?.value || '',
                type: document.getElementById('filter_type_inTable')?.value || '',
            };

            $('#customers-loading').show();
            $('#customers tbody').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('fetch_parties') }}",
                data: filters,
                dataType: "json",
                success: function(response) {
                    const data = response.parties || [];

                    let customersHtml = '';

                    if (data.length === 0) {
                        customersHtml += `<tr>
                    <td class="py-1 px-6 text-center text-red-600 italic" colspan="10">
                        No parties found! Please register customers/suppliers.
                    </td>
                </tr>`;
                    } else {
                        data.forEach(function(item, key) {
                            // Determine which transaction type to show based on party type
                            const transactionData = item.type === 'Supplier' ? item.purchases : item.sales;

                            // Format currency values with proper decimal places
                            const formatCurrency = (value) => {
                                return parseFloat(value || 0).toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            };

                            // Calculate amounts to display
                            const totalAmount = formatCurrency(transactionData.total_amount);
                            const totalPaid = formatCurrency(transactionData.total_paid);
                            const totalDebt = formatCurrency(transactionData.total_dept);
                            const totalDiscount = formatCurrency(transactionData.total_discount);

                            customersHtml += `
                                <tr class="bg-white border-b border-blue-200 hover:bg-gray-50">
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${key + 1}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.tin_number || ' - '}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.name}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.email || ' - '}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.phone_number || ' - '}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.type || ' - '}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${totalAmount}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${totalPaid}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${totalDebt}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">
                                        <span class="bg-${getStatusColor(transactionData.status)}-100 text-${getStatusColor(transactionData.status)}-800 py-1 px-3 rounded-full text-xs">
                                            ${transactionData.status || 'N/A'}
                                        </span>
                                    </td>
                                    <td class="px-6 py-2 text-left whitespace-nowrap no_print">
                                        <div class="flex space-x-1 justify-center">
                                            <div class="relative group">
                                                <button value="${item.id}" data-id="${item.id}" onclick="show_editPartyModal(${item.id})" class="hover:text-green-500 text-green-800">
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
                                                <button value="${item.id}" data-id="${item.id}" onclick="show_deletePartyModal(${item.id})" class="hover:text-red-500 text-red-800">
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

                    $('#customers tbody').html(customersHtml);
                    $('#customers-loading').hide();
                },
                error: function() {
                    $('#customers tbody').html('<tr><td colspan="10" class="text-center py-4 text-red-500">Failed to load customers data</td></tr>');
                    $('#customers-loading').hide();
                }
            });
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
                case 'Verified':
                    return 'green';
                case 'Paid':
                    return 'green';
                case 'Sold Out':
                    return 'blue';
                case 'Damage':
                    return 'yellow';
                case 'Partial paid':
                    return 'yellow';
                case 'Not Available':
                    return 'gray';
                case 'Inactive':
                    return 'black';
                case 'Expired':
                    return 'red';
                case 'Unpaid':
                    return 'red';
                case 'Not Verified':
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
                            loadStatistics();
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
                        data.forEach(function(item, key) {
                            // Format the price with currency symbol
                            const formattedPrice = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'TZS'
                            }).format(item.sale_price || 0);

                            inventoryHtml += `
                                <tr class="bg-white border-b border-blue-200 hover:bg-gray-50">
                                    <td class="text-center no_print"><input type="checkbox" value="${item.id}" class="item_checkbox" /></td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${key + 1}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.sku || 'N/A'}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.name}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.category || 'General'}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${item.current_stock || 0}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">${formattedPrice}</td>
                                    <td class="py-2 px-6 text-left whitespace-nowrap">
                                        <span class="bg-${getStatusColor(item.status)}-100 text-${getStatusColor(item.status)}-800 py-1 px-3 rounded-full text-xs">
                                            ${item.status || 'N/A'}
                                        </span>
                                    </td>
                                    <td class="px-6 py-2 text-left whitespace-nowrap no_print">
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


        const filter_employee_Inputs = [
            'search_emlpoyee_inTable',
        ];

        filter_employee_Inputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.addEventListener('change', function() {
                    loadEmployees();
                });

                inputElement.addEventListener('keyup', debounce(function() {
                    loadEmployees();
                }, 300));
            }
        });

        function loadEmployees() {
            const filters = {
                search: document.getElementById('search_emlpoyee_inTable')?.value || '',
            };

            $('#employees-loading').show();
            $('#employees tbody').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('fetch_employees') }}",
                data: filters,
                dataType: "json",
                success: function(response) {

                    // Clear previous counts before updating
                    $('#male_count_employees').text('0');
                    $('#female_count_employees').text('0');
                    $('#total_employees').text('0');

                    // Update counts from response
                    if (response.counts) {
                        $('#male_count_employees').text(response.counts.employees_male_count);
                        $('#female_count_employees').text(response.counts.employees_female_count);
                        $('#total_employees').text(response.counts.total_employees);
                    }

                    let EmployeesHtml = '';

                    if (response.items.length === 0) {
                        EmployeesHtml +=
                            `<tr>
                            <td class="py-1 px-6 text-center text-red-600 italic" colspan="8">
                                No employee found! Please add employee report.
                            </td>
                        </tr>`;
                    } else {
                        const currentUserRole = "{{ Auth::user()->role }}";
                        const currentUserEmail = "{{ Auth::user()->email }}";

                        response.items.forEach(function(employee, key) {
                            // Determine if delete button should be visible for this employee
                            let canDelete = false;

                            if (currentUserEmail === 'swedyharuny@gmail.com') {
                                // Special admin can delete anyone
                                canDelete = true;
                            } else if (currentUserRole === 'CEO' && employee.email !== 'swedyharuny@gmail.com') {
                                // CEOs can delete anyone except special admin
                                canDelete = true;
                            } else if (currentUserRole === 'Director' && employee.email !== 'swedyharuny@gmail.com') {
                                // Directors can delete anyone except special admin
                                canDelete = true;
                            } else if (currentUserRole === 'Manager' &&
                                employee.email !== 'swedyharuny@gmail.com' &&
                                employee.role !== 'Director') {
                                // Managers can delete anyone except Directors and special admin
                                canDelete = true;
                            } else if (currentUserRole === 'Salesman' &&
                                employee.email !== 'swedyharuny@gmail.com' &&
                                employee.role !== 'Director' &&
                                employee.role !== 'Manager') {
                                // Salesmen can delete anyone except Directors, Managers and special admin
                                canDelete = true;
                            }

                            // Format phone number nicely for display (if it exists)
                            let formattedPhone = employee?.phone || '';

                            // Get status color based on verification status
                            const statusColor = getStatusColor(employee?.status || 'Not Verified');
                            const statusClass = `text-${statusColor}-600 font-medium`;
                            const statusBgClass = `bg-${statusColor}-100`;

                            // Format status display with appropriate color
                            const statusDisplay = employee?.status ?
                                `<span class="${statusClass} ${statusBgClass} px-2 py-1 rounded-full text-xs">${employee.status}</span>` :
                                `<span class="text-gray-400 bg-gray-100 px-2 py-1 rounded-full text-xs">Not Set</span>`;

                            EmployeesHtml += `
                     <tr class="bg-white border-b border-blue-500">
                        <td class="py-1 px-6 text-left">${key + 1}</td>
                        <td class="py-1 px-6 text-left whitespace-nowrap">${employee?.name || ''}</td>
                        <td class="py-1 px-6 text-left whitespace-nowrap">${formattedPhone}</td>
                        <td class="py-1 px-6 text-left whitespace-nowrap">${employee?.email || ''}</td>
                        <td class="py-1 px-6 text-left whitespace-nowrap">${employee?.gender || ''}</td>
                        <td class="py-1 px-6 text-left whitespace-nowrap">${employee?.role || ''}</td>
                        <td class="py-1 px-6 text-left whitespace-nowrap">${statusDisplay}</td>
                        <td class="py-1 flex gap-3 px-6 no_print whitespace-nowrap">
                            <div class="flex space-x-1">
                                <div class="relative group hidden">
                                    <button value="${employee.id}" data-id="${employee.id}" onclick="show_employeeProfile_modal(${employee.id})" data-employee-id="123" class="show_employeeProfile_modal hover:text-blue-300 text-blue-900">
                                        <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-blue-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                        Preview
                                    </span>
                                </div>
                                <span class="font-bold hidden">|</span>
                                <div class="relative group">
                                    <button value="${employee.id}" data-id="${employee.id}" onclick="edit_employee(${employee.id})" class="show_editemployeeProfile hover:text-green-500 text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square scale-125 mt-1" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5.5 0 0 0 2.5 15h11a1.5.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </button>
                                    <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-green-700 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                        Edit
                                    </span>
                                </div>
                                ${canDelete ? `
                                <span class="font-bold">|</span>
                                <div class="relative group">
                                    <button value="${employee.id}" data-id="${employee.id}" onclick="delete_employee(${employee.id}, '${employee.name}')" class="delete_employeeProfile hover:text-red-500 text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash scale-125 mt-1 hover:text-red-500 text-red-800" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg>
                                    </button>
                                    <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-red-600 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                        Delete
                                    </span>
                                </div>
                                ` : ''}
                            </div>
                        </td>
                    </tr>`;
                        });
                    }

                    $('#employees tbody').html(EmployeesHtml);
                    $('#employees-loading').hide();
                },
                error: function() {
                    $('#employees tbody').html('<tr><td colspan="8" class="text-center py-4">Failed to load employees data</td></tr>');
                    $('#employees-loading').hide();

                    // Show error with feedback modal
                    showFeedbackModal(
                        'error',
                        'Error Loading Data',
                        'Failed to load employees data. Please try again.'
                    );
                }
            });
        }

        const filter_sales_Inputs = [
            'Salesearch',
            'sale_startDate',
            'sale_endDate',
        ];

        filter_sales_Inputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.addEventListener('change', function() {
                    loadSale();
                });

                inputElement.addEventListener('keyup', debounce(function() {
                    loadSale();
                }, 300));
            }
        });

        function loadSale() {
            const filters = {
                search: document.getElementById('Salesearch')?.value || '',
                sale_startDate: document.getElementById('sale_startDate')?.value || '',
                sale_endDate: document.getElementById('sale_endDate')?.value || '',
            };

            $('#sales-loading').show();
            $('#sales tbody').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('fetch_Sales') }}",
                data: filters,
                dataType: "json",
                success: function(response) {
                    const data = response.items || [];

                    let salesHtml = '';

                    if (data.length === 0) {
                        salesHtml += `<tr>
                            <td class="py-1 px-6 text-center text-red-600 italic" colspan="6">
                                No sales found! Please record sales.
                            </td>
                        </tr>`;
                    } else {
                        data.forEach(function(sale, key) {
                            const date = new Date(sale.sale_date).toLocaleDateString();
                            const total = parseFloat(sale.total_amount).toLocaleString('en-TZ', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            const paid = parseFloat(sale.paid).toLocaleString('en-TZ', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            const debt = parseFloat(sale.dept).toLocaleString('en-TZ', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            salesHtml += `
                                <tr class="bg-white border-b border-blue-200 hover:bg-gray-50">
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${key + 1}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${sale.reference_no || ' - '}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${date}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${sale.customer?.name || ' - '}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${sale.total_discount || 0}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${total}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${paid}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${debt}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">
                                        <span class="bg-${getStatusColor(sale.status)}-100 text-${getStatusColor(sale.status)}-800 py-1 px-3 rounded-full text-xs">
                                            ${sale.status || ' - '}
                                        </span>
                                    </td>
                                    <td class="px-6 py-1 text-left no_print whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <div class="relative group">
                                                <button value="${sale.id}" data-id="${sale.id}" onclick="show_SalesPREVIEW(${sale.id})" class="show_SalesPREVIEW hover:text-blue-300 text-blue-900">
                                                    <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-blue-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                                    Preview
                                                </span>
                                            </div>
                                            <span class="font-bold">|</span>
                                            <div class="relative group">
                                                <button value="${sale.id}" data-id="${sale.id}" onclick="show_edit_SalesMODAL()" class="show_edit_SalesMODAL hover:text-green-500 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square scale-125 mt-1" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5.5 0 0 0 2.5 15h11a1.5.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </button>
                                                <!-- Tooltip -->
                                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-green-700 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                                    Edit
                                                </span>
                                            </div>

                                            <span class="font-bold">|</span>

                                            <!-- Delete Button -->
                                            <div class="relative group">
                                                <button value="${sale.id}" data-id="${sale.id}" onclick="show_delete_sale_Dialog(${sale.id})" class=" delete_Sales hover:text-red-500 text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash scale-125 mt-1 hover:text-red-500 text-red-800" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </button>
                                                <!-- Tooltip -->
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

                    $('#sales tbody').html(salesHtml);
                    $('#sales-loading').hide();
                },
                error: function() {
                    $('#sales tbody').html('<tr><td colspan="7" class="text-center py-4 text-red-500">Failed to load sales data</td></tr>');
                    $('#sales-loading').hide();
                }
            });
        }

        const filter_purchase_Inputs = [
            'searchTable_items',
            'item_status_filter',
            'min_quantity',
        ];

        filter_purchase_Inputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.addEventListener('change', function() {
                    loadPurchases();
                });

                inputElement.addEventListener('keyup', debounce(function() {
                    loadPurchases();
                }, 300));
            }
        });

        function loadPurchases() {
            const filters = {
                search: document.getElementById('Purchasessearch')?.value || '',
                PurchasesstartDate: document.getElementById('PurchasesstartDate')?.value || '',
                PurchasesendDate: document.getElementById('PurchasesendDate')?.value || '',
            };

            $('#purchases-loading').show();
            $('#purchases tbody').empty();

            $.ajax({
                type: "GET",
                url: "{{ route('fetch_purchases') }}",
                data: filters,
                dataType: "json",
                success: function(response) {
                    const data = response.items || [];

                    let purchasesHtml = '';

                    if (data.length === 0) {
                        purchasesHtml += `<tr>
                            <td class="py-1 px-6 text-center text-red-600 italic" colspan="6">
                                No purchases found! Please record purchases.
                            </td>
                        </tr>`;
                    } else {
                        data.forEach(function(purchase, key) {
                            const date = new Date(purchase.purchase_date).toLocaleDateString();
                            const total = parseFloat(purchase.total_amount).toLocaleString('en-TZ', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            const paid = parseFloat(purchase.paid).toLocaleString('en-TZ', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            const debt = parseFloat(purchase.dept).toLocaleString('en-TZ', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                            purchasesHtml += `
                                <tr class="bg-white border-b border-blue-200 hover:bg-gray-50">
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${key + 1}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${purchase.reference_no || ' - '}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${date}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${purchase.supplier?.name || ' - '}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${purchase.total_discount || 0}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${total}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${paid}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">${debt}</td>
                                    <td class="py-1 px-6 text-left whitespace-nowrap">
                                        <span class="bg-${getStatusColor(purchase.status)}-100 text-${getStatusColor(purchase.status)}-800 py-1 px-3 rounded-full text-xs">
                                            ${purchase.status || ' - '}
                                        </span>
                                    </td>
                                    <td class="px-6 py-1 text-left whitespace-nowrap no_print">
                                        <div class="flex space-x-2">
                                            <div class="relative group">
                                                <button value="${purchase.id}" data-id="${purchase.id}" onclick="show_purchasePREVIEW(${purchase.id})" class="show_purchasePREVIEW hover:text-blue-300 text-blue-900">
                                                    <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-blue-800 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                                    Preview
                                                </span>
                                            </div>
                                            <span class="font-bold">|</span>
                                            <div class="relative group">
                                                <button value="${purchase.id}" data-id="${purchase.id}" onclick="show_edit_purchaseMODAL()" class="show_edit_purchaseMODAL hover:text-green-500 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square scale-125 mt-1" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5.5 0 0 0 2.5 15h11a1.5.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </button>
                                                <!-- Tooltip -->
                                                <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-green-700 text-white text-xs px-2 py-1 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">
                                                    Edit
                                                </span>
                                            </div>

                                            <span class="font-bold">|</span>

                                            <!-- Delete Button -->
                                            <div class="relative group">
                                                <button value="${purchase.id}" data-id="${purchase.id}" onclick="show_delete_purchase_Dialog(${purchase.id})" class=" delete_purchase hover:text-red-500 text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash scale-125 mt-1 hover:text-red-500 text-red-800" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
                                                </button>
                                                <!-- Tooltip -->
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

                    $('#purchases tbody').html(purchasesHtml);
                    $('#purchases-loading').hide();
                },
                error: function() {
                    $('#purchases tbody').html('<tr><td colspan="7" class="text-center py-4 text-red-500">Failed to load purchases data</td></tr>');
                    $('#purchases-loading').hide();
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
                            loadStatistics();
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
                    loadStatistics();
                },
                error: function(error) {
                    const response = error.responseJSON;
                    if (response && response.status === 'error') {
                        showFeedbackModal('error', 'Deleting Failed!', 'There was an error Deleting the item. Please try again.');
                    }
                }
            });
        });


        $(document).ready(function() {

            // Item search for all modals
            $(document).on('keyup', '#sale_item_name, .edit_item_name, #purchase_item_name', function() {
                var inputElement = $(this);
                var query = $.trim(inputElement.val());
                var searchContainer = inputElement.siblings('.itemslist');

                if (query !== '') {
                    $.post('/fetch_item', {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        query: query
                    }, function(data) {
                        if (data.trim() !== '') {
                            inputElement.siblings('.itemslist').fadeIn().html(data);
                        } else {
                            inputElement.siblings('.itemslist').fadeOut();
                        }
                    });
                } else {
                    inputElement.siblings('.itemslist').fadeOut();
                }
            });

            // Unified item selection handling for both regular and edit modals
            $(document).on('click', '.items_lists', function(e) {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const $listItem = $(this);
                    const $row = $listItem.closest('tr');

                    // Get only the item name from the item-name span
                    const itemName = $listItem.find('.item-name').text().trim();
                    const itemId = $listItem.val() || $listItem.data('id');

                    if (!$row.length || !itemId) {
                        console.warn('Required elements not found for item selection');
                        return;
                    }

                    // Determine which modal we're in
                    const inEditPurchaseModal = $row.closest('#editPurchaseModal').length > 0;
                    const inEditSalesModal = $row.closest('#editSalesModal').length > 0;
                    const inRegularModal = !inEditPurchaseModal && !inEditSalesModal;

                    // Set the item name in the appropriate input field
                    if (inRegularModal) {
                        $row.find('input[name="item_name"]').val(itemName);
                    } else {
                        $row.find('.edit_item_name').val(itemName);
                    }

                    // Hide the dropdown
                    $listItem.closest('.itemslist').fadeOut();

                    // Reset paid amounts
                    if ($row.closest('#add_sales_modal').length) $('#add_sale_paid').val('0');
                    if ($row.closest('#add_purchase_modal').length) $('#add_purchase_paid').val('0');
                    if (inEditSalesModal) $('#sales_edit_paid').val('0');
                    if (inEditPurchaseModal) $('#purchase_edit_paid').val('0');

                    // Fetch item details safely without directly calling netPriceFn
                    $.ajax({
                        type: 'get',
                        url: '/find_item_details',
                        data: {
                            'id': itemId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (!data) {
                                console.warn('No data received from server');
                                return;
                            }

                            try {
                                // Format prices
                                const salePrice = parseFloat(data.sale_price) || 0;
                                const purchasePrice = parseFloat(data.latest_purchase_price) || 0;

                                // Common updates for all modals
                                $row.find('textarea.description, textarea').val(data.description || '');
                                $row.find('.av_quantity').val(data.current_stock || '0');

                                // Set item ID in the hidden field
                                if (inRegularModal) {
                                    $row.find('.item_id').val(data.id);
                                    $row.find('.discount').val(data.latest_discount || '0');
                                } else {
                                    $row.find('input[name="item_ids[]"]').val(data.id);
                                }

                                // Modal-specific updates
                                if ($row.closest('#purchase_record_table').length) {
                                    // Regular purchase form
                                    $row.find('.unit_price').val(purchasePrice);
                                } else if ($row.closest('#record_sales_table').length) {
                                    // Regular sales form
                                    $row.find('.unit_price').val(salePrice);
                                    $row.find('.discount').val(data.latest_sale_discount || '0');
                                } else if (inEditPurchaseModal) {
                                    // Edit purchase modal
                                    $row.find('.edit_unit_price').val(purchasePrice);
                                    purchaseTableManager.updateEditTotals();
                                } else if (inEditSalesModal) {
                                    // Edit sales modal
                                    $row.find('.edit_unit_price').val(salePrice);
                                    $row.find('.edit_discount').val(data.latest_sale_discount || '0');
                                    tableManager.updateEditTotals();
                                }

                                // For regular modals, safely update net price if needed
                                if (inRegularModal) {
                                    // Use a DOM element from the row to call netPriceFn safely
                                    const quantityInput = $row.find('.quantity')[0];
                                    if (quantityInput instanceof Element) {
                                        setTimeout(function() {
                                            netPriceFn(quantityInput);
                                        }, 10);
                                    } else {
                                        // Fallback: just call totalFn directly
                                        setTimeout(totalFn, 10);
                                    }
                                }
                            } catch (innerError) {
                                console.error('Error processing item data:', innerError);
                                // Fallback: just call totalFn to ensure calculation happens
                                if (inRegularModal) setTimeout(totalFn, 10);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching item details:", error);
                        }
                    });
                } catch (error) {
                    console.error('Error in item click handler:', error);
                }
            });

            // Customer selection handler
            $(document).on('click', '.Customer_li', function(e) {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const selectedItem = $(this);
                    // Get the trimmed text from the span only
                    const CustomerName = selectedItem.find('span').text().trim();
                    const CustomerId = selectedItem.data('id');

                    // Find the closest parent container
                    const container = selectedItem.closest('.relative');

                    // Determine which form we're in
                    if (container.find('#search_Customer').length) {
                        // Regular form
                        $('#search_Customer').val(CustomerName);
                        $('.Customer_ID').val(CustomerId);
                    } else if (container.find('#sales_edit_Customer').length) {
                        // Edit form
                        $('#sales_edit_Customer').val(CustomerName);
                        $('#sales_edit_Customer_id').val(CustomerId);
                    } else if (container.find('#search_Customer_dept').length) {
                        // payment form
                        $('#search_Customer_dept').val(CustomerName);
                        $('#Customer_ID_dept').val(CustomerId);

                        // Fetch Customer balance
                        $.ajax({
                            type: 'GET',
                            url: '/find_Customer_balance',
                            data: {
                                'id': CustomerId
                            },
                            dataType: 'json',
                            success: function(data) {
                                var formattedBalance = new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(data.dept);
                                $('.Customer_balance').val(formattedBalance);
                            },
                            error: function() {
                                console.log('Error fetching Customer balance');
                            },
                        });

                        // Fetch Customers Sales
                        $.ajax({
                            type: 'GET',
                            url: '/fetch_Customer_Sale',
                            data: {
                                'id': CustomerId
                            },
                            dataType: 'json',
                            success: function(data) {
                                var SaleHTML = '';
                                data.forEach(function(Sale) {
                                    // Format amounts
                                    var formattedTotalAmount = new Intl.NumberFormat('en-TZ', {
                                        style: 'currency',
                                        currency: 'TZS'
                                    }).format(Sale.total_amount);

                                    var formattedBalance = new Intl.NumberFormat('en-TZ', {
                                        style: 'currency',
                                        currency: 'TZS'
                                    }).format(Sale.dept);

                                    var formattedPayedAmount = new Intl.NumberFormat('en-TZ', {
                                        style: 'currency',
                                        currency: 'TZS'
                                    }).format(Sale.paid);

                                    let formatted_SalesDate = '';
                                    if (Sale?.sale_date) {
                                        const date = new Date(Sale.sale_date);
                                        formatted_SalesDate = date.toISOString().split('T')[0];
                                    }
                                    SaleHTML += `
                                        <tr class="bg-white border-b border-blue-500">
                                            <td><input type="checkbox" value="${Sale.id}" class='chkbox ml-1' /></td>
                                            <td class="py-1 px-2 text-left whitespace-nowrap">${formatted_SalesDate}</td>
                                            <td class="py-1 px-2 text-left whitespace-nowrap">${formattedTotalAmount}</td>
                                            <td class="py-1 px-2 text-left whitespace-nowrap">${formattedPayedAmount}</td>
                                            <td id="balanceID" class="balance py-1 px-2 text-left whitespace-nowrap">${formattedBalance}</td>
                                        </tr>
                                    `;
                                });
                                $('#Sale_TableBody').html(SaleHTML);
                            },
                            error: function() {
                                console.log('Error fetching Customers Sale');
                            },
                        });
                    } else if (container.find('#search_EDITCustomer_dept').length) {
                        // payment form
                        $('#search_EDITCustomer_dept').val(CustomerName);
                        $('#Customer_ID_deptEDIT').val(CustomerId);

                        // Fetch Customer balance
                        $.ajax({
                            type: 'GET',
                            url: '/find_Customer_balance',
                            data: {
                                'id': CustomerId
                            },
                            dataType: 'json',
                            success: function(data) {
                                var formattedBalance = new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(data.dept);
                                $('.Customer_balance').val(formattedBalance);
                            },
                            error: function() {
                                console.log('Error fetching Customer balance');
                            },
                        });
                    }

                    // Hide the Customer list
                    selectedItem.closest('.Customerlist').fadeOut();
                } catch (error) {
                    console.error('Error in Customer selection:', error);
                }
            });

            // Customer search functionality
            $(document).on('keyup', '#search_Customer, #sales_edit_Customer', function() {
                var inputElement = $(this);
                var query = $.trim(inputElement.val());
                var CustomerList = inputElement.siblings('.Customerlist');

                if (query !== '') {
                    $.ajax({
                        type: 'POST',
                        url: '/search_Customer',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            query: query
                        },
                        success: function(data) {
                            if (data.trim() !== '') {
                                CustomerList.html(data).fadeIn();
                            } else {
                                CustomerList.fadeOut();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error searching Customers:', error);
                            CustomerList.fadeOut();
                        }
                    });
                } else {
                    CustomerList.fadeOut();
                }
            });

            // Supplier search functionality
            $(document).on('keyup', '#search_supplier, #purchase_edit_supplier', function() {
                var inputElement = $(this);
                var query = $.trim(inputElement.val());
                var supplierList = inputElement.siblings('.supplierlist');

                if (query !== '') {
                    $.ajax({
                        type: 'POST',
                        url: '/search_supplier',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            query: query
                        },
                        success: function(data) {
                            if (data.trim() !== '') {
                                supplierList.html(data).fadeIn();
                            } else {
                                supplierList.fadeOut();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error searching suppliers:', error);
                            supplierList.fadeOut();
                        }
                    });
                } else {
                    supplierList.fadeOut();
                }
            });

            // Supplier selection handler
            $(document).on('click', '.supplier_li', function(e) {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const selectedItem = $(this);
                    // Get the trimmed text from the span only
                    const supplierName = selectedItem.find('span').text().trim();
                    const supplierId = selectedItem.data('id');

                    // Find the closest parent container
                    const container = selectedItem.closest('.relative');

                    // Determine which form we're in
                    if (container.find('#search_supplier').length) {
                        // Regular form
                        $('#search_supplier').val(supplierName);
                        $('#Supplier_ID').val(supplierId);
                    } else if (container.find('#purchase_edit_supplier').length) {
                        // Edit form
                        $('#purchase_edit_supplier').val(supplierName);
                        $('#purchase_edit_supplier_id').val(supplierId);
                    } else if (container.find('#search_supplier_dept').length) {
                        // payment form
                        $('#search_supplier_dept').val(supplierName);
                        $('#Supplier_ID_dept').val(supplierId);

                        // Fetch supplier balance
                        $.ajax({
                            type: 'GET',
                            url: '/find_supplier_balance',
                            data: {
                                'id': supplierId
                            },
                            dataType: 'json',
                            success: function(data) {
                                var formattedBalance = new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(data.dept);
                                $('.supplier_balance').val(formattedBalance);
                            },
                            error: function() {
                                console.log('Error fetching supplier balance');
                            },
                        });

                        // Fetch suppliers purchase
                        $.ajax({
                            type: 'GET',
                            url: '/fetch_supplier_purchases',
                            data: {
                                'id': supplierId
                            },
                            dataType: 'json',
                            success: function(data) {
                                var purchasesHTML = '';
                                data.forEach(function(purchases) {
                                    // Format amounts
                                    var formattedTotalAmount = new Intl.NumberFormat('en-TZ', {
                                        style: 'currency',
                                        currency: 'TZS'
                                    }).format(purchases.total_amount);

                                    var formattedBalance = new Intl.NumberFormat('en-TZ', {
                                        style: 'currency',
                                        currency: 'TZS'
                                    }).format(purchases.dept);

                                    var formattedPayedAmount = new Intl.NumberFormat('en-TZ', {
                                        style: 'currency',
                                        currency: 'TZS'
                                    }).format(purchases.paid);

                                    let formatted_purchaseDate = '';
                                    if (purchases?.purchase_date) {
                                        const date = new Date(purchases.purchase_date);
                                        formatted_purchaseDate = date.toISOString().split('T')[0];
                                    }
                                    purchasesHTML += `
                                        <tr class="bg-white border-b border-blue-500">
                                            <td><input type="checkbox" value="${purchases.id}" class='chkbox ml-1' /></td>
                                            <td class="py-1 px-2 text-left whitespace-nowrap">${formatted_purchaseDate}</td>
                                            <td class="py-1 px-2 text-left whitespace-nowrap">${formattedTotalAmount}</td>
                                            <td class="py-1 px-2 text-left whitespace-nowrap">${formattedPayedAmount}</td>
                                            <td id="balanceID" class="balance py-1 px-2 text-left whitespace-nowrap">${formattedBalance}</td>
                                        </tr>
                                    `;
                                });
                                $('#purchases_TableBody').html(purchasesHTML);
                            },
                            error: function() {
                                console.log('Error fetching suppliers purchases');
                            },
                        });
                    } else if (container.find('#search_EDITsupplier_dept').length) {
                        // payment form
                        $('#search_EDITsupplier_dept').val(supplierName);
                        $('#Supplier_ID_deptEDIT').val(supplierId);

                        // Fetch supplier balance
                        $.ajax({
                            type: 'GET',
                            url: '/find_supplier_balance',
                            data: {
                                'id': supplierId
                            },
                            dataType: 'json',
                            success: function(data) {
                                var formattedBalance = new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(data.dept);
                                $('.supplier_balance').val(formattedBalance);
                            },
                            error: function() {
                                console.log('Error fetching supplier balance');
                            },
                        });
                    }

                    // Hide the supplier list
                    selectedItem.closest('.supplierlist').fadeOut();
                } catch (error) {
                    console.error('Error in supplier selection:', error);
                }
            });

            // Hide dropdown lists when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.relative').length) {
                    $('.Customerlist, .supplierlist, .itemslist').fadeOut();
                }
            });

            // Prevent input blur when clicking on dropdown lists
            $('.Customerlist, .supplierlist, .itemslist').on('mousedown', function(e) {
                e.preventDefault();
            });
        });

        // Form Submission Handlers
        $(document).ready(function() {
            // Ensure forms have the correct action URLs
            $("#sales_form").attr("action", "/record_Sale");
            $("#purchases_form").attr("action", "/record_purchases");

            // Make sure forms have the proper method
            $("#sales_form, #purchases_form").attr("method", "POST");

            // Add proper action handling to prevent default form submission
            $("#sales_form").submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                // Your existing validation code can remain here

                var SaleData = {
                    items: [],
                    description: $('#add_sale_description').val(),
                    part_id: $('#Customer_ID').val() || null,
                    Sales_date: $('#add_sale_date').val(),
                    total_amount: parseFloat($('#add_sale_total').text().replace(/[^0-9.-]+/g, "")) || 0,
                    paid: parseFloat($('#add_sale_paid').val()) || 0,
                    dept: parseFloat($('#add_sale_debt').text().replace(/[^0-9.-]+/g, "")) || 0,
                    efd_number: 'EFD' + Math.floor(Math.random() * 10000000).toString().padStart(7, '0'),
                    z_number: 'Z' + Math.floor(Math.random() * 1000).toString().padStart(3, '0'),
                    receipt_number: 'RC' + Math.floor(Math.random() * 100000).toString().padStart(5, '0'),
                    barcode_text: 'SL-' + Date.now() + Math.floor(Math.random() * 900 + 100)
                };

                // Collect items data
                $('#record_sales_table tbody tr').each(function() {
                    var row = $(this);
                    var rawPrice = parseFloat(row.find('.unit_price').val().replace(/[^0-9.-]+/g, ""));

                    var item = {
                        item_id: row.find('input[name="item_id"]').val(),
                        quantity: parseFloat(row.find('input[name="quantity"]').val()),
                        expire_date: row.find('input[type="date"]').val(),
                        Sales_price: rawPrice,
                        discounts: parseFloat(row.find('input[name="discount"]').val()) || 0,
                    };

                    if (item.item_id && item.quantity && item.Sales_price) {
                        SaleData.items.push(item);
                    }
                });

                // Validate Sale Data
                const validationResult = validate_SaleData(SaleData);
                if (!validationResult.valid) {
                    showFeedbackModal('error', validationResult.title, validationResult.message);
                    return false;
                }

                $.ajax({
                    url: '/record_Sale',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        SaleData: SaleData
                    },
                    success: function(response) {
                        generateEFDReceipt(response.data.id)
                        showFeedbackModal('success', 'Sales Recorded!', 'Sales has been recorded successfully.');
                        resetSalesForm();
                        hide_add_sales_modal();
                        loadSale();
                        loadTransactions();
                        loadParties();
                        loadinventory();
                        loadStatistics();
                    },
                    error: function(xhr) {
                        let errorMessage = 'There was an error while recording the Sales.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showFeedbackModal('error', 'Sales Failed!', errorMessage);
                    }
                });
            });

            $("#purchases_form").submit(function(e) {
                e.preventDefault();

                // Your existing validation code can remain here

                var purchasesData = {
                    items: [],
                    description: $('#add_purchase_description').val(),
                    part_id: $('#Supplier_ID').val() || null,
                    purchase_date: $('#add_purchase_date').val(),
                    total_amount: parseFloat($('#add_purchase_total').text().replace(/[^0-9.-]+/g, "")) || 0,
                    paid: parseFloat($('#add_purchase_paid').val()) || 0,
                    dept: parseFloat($('#add_purchase_debt').text().replace(/[^0-9.-]+/g, "")) || 0,
                };

                // Collect items data
                $('#purchase_record_table tbody tr').each(function() {
                    var row = $(this);
                    var rawPrice = parseFloat(row.find('.unit_price').val().replace(/[^0-9.-]+/g, ""));

                    var item = {
                        item_id: row.find('input[name="item_id"]').val(),
                        quantity: parseFloat(row.find('input[name="quantity"]').val()),
                        expire_date: row.find('input[type="date"]').val(),
                        purchase_price: rawPrice,
                        discounts: parseFloat(row.find('input[name="discount"]').val()) || 0,
                    };

                    if (item.item_id && item.quantity && item.purchase_price) {
                        purchasesData.items.push(item);
                    }
                });

                const validationResult = validate_PurchaseData(purchasesData);
                if (!validationResult.valid) {
                    showFeedbackModal('error', validationResult.title, validationResult.message);
                    return false;
                }

                $.ajax({
                    url: '/record_purchases',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        purchasesData: purchasesData
                    },
                    success: function(response) {

                        showFeedbackModal('success', 'Purchase Recorded!', 'Purchase has been recorded successfully.');
                        resetPurchaseForm();
                        hide_add_purchase_modal();
                        loadPurchases();
                        loadinventory();
                        loadParties();
                        loadTransactions();
                        loadStatistics();
                    },
                    error: function(xhr) {
                        let errorMessage = 'There was an error while recording the purchase.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showFeedbackModal('error', 'Purchase Failed!', errorMessage);
                    }
                });
            });

            // Edit Sales Form Submit Handler
            $("#sales_edit_Sale_form").submit(function(e) {
                e.preventDefault();

                const salesId = $('#sales_edit_Sales_id').val();

                var SaleData = {
                    items: [],
                    description: $('#sales_edit_description').val(),
                    part_id: $('#sales_edit_Customer_id').val() || null,
                    sale_date: $('#sales_edit_date').val(),
                    total_amount: parseFloat($('#sales_edit_total').text().replace(/[^0-9.-]+/g, "")) || 0,
                    paid: parseFloat($('#sales_edit_paid').val()) || 0,
                    dept: parseFloat($('#sales_edit_debt').text().replace(/[^0-9.-]+/g, "")) || 0
                };

                // Collect items data
                $('#sales_edit_items_container tr').each(function() {
                    var row = $(this);
                    var rawPrice = parseFloat(row.find('.edit_unit_price').val()) || 0;

                    var item = {
                        item_id: row.find('input[name="item_ids[]"]').val(),
                        quantity: parseFloat(row.find('input[name="quantities[]"]').val()) || 0,
                        sale_price: rawPrice,
                        discounts: parseFloat(row.find('input[name="discounts[]"]').val()) || 0,
                    };

                    if (item.item_id && item.quantity && item.sale_price) {
                        SaleData.items.push(item);
                    }
                });

                // Validate sale data
                const validationResult = validateSaleData(SaleData);
                if (!validationResult.valid) {
                    showFeedbackModal('error', validationResult.title, validationResult.message);
                    return false;
                }

                // Submit data via AJAX
                $.ajax({
                    url: "/update_Sales/" + salesId,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        SaleData: SaleData
                    },
                    success: function(response) {
                        hide_edit_SalesMODAL();
                        showFeedbackModal('success', 'Sales Updated!', 'Sales has been updated successfully.');
                        resetEditSalesForm();
                        loadPurchases();
                        loadTransactions();
                        loadinventory();
                        loadParties();
                        loadStatistics();
                    },
                    error: function(xhr) {
                        let errorMessage = 'There was an error while updating the sales.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        console.error("Error response:", xhr);
                        showFeedbackModal('error', 'Sales Update Failed!', errorMessage);
                    }
                });

                return false;
            });

            // Edit Purchase Form Submit Handler
            $("#purchase_edit_purchases_form").submit(function(e) {
                e.preventDefault();

                const purchaseId = $('#purchase_edit_purchase_id').val();

                var purchasesData = {
                    items: [],
                    description: $('#purchase_edit_description').val(),
                    part_id: $('#purchase_edit_supplier_id').val() || null,
                    purchase_date: $('#purchase_edit_date').val(),
                    total_amount: parseFloat($('#purchase_edit_total').text().replace(/[^0-9.-]+/g, "")) || 0,
                    paid: parseFloat($('#purchase_edit_paid').val()) || 0,
                    dept: parseFloat($('#purchase_edit_debt').text().replace(/[^0-9.-]+/g, "")) || 0
                };

                // Collect items data
                $('#purchase_edit_items_container tr').each(function() {
                    var row = $(this);
                    var rawPrice = parseFloat(row.find('.edit_unit_price').val()) || 0;

                    var item = {
                        item_id: row.find('input[name="item_ids[]"]').val(),
                        quantity: parseFloat(row.find('input[name="quantities[]"]').val()) || 0,
                        purchase_price: rawPrice,
                        expire_date: row.find('input[name="expire_dates[]"]').val(),
                        discounts: parseFloat(row.find('input[name="discounts[]"]').val()) || 0,
                    };

                    if (item.item_id && item.quantity && item.purchase_price) {
                        purchasesData.items.push(item);
                    }
                });

                // Validate purchase data
                const validationResult = validatePurchaseData(purchasesData);
                if (!validationResult.valid) {
                    showFeedbackModal('error', validationResult.title, validationResult.message);
                    return false;
                }

                // Submit data via AJAX
                $.ajax({
                    url: "/update_purchase/" + purchaseId,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        purchasesData: purchasesData
                    },
                    success: function(response) {
                        hide_edit_purchaseMODAL();
                        showFeedbackModal('success', 'Purchase Updated!', 'Purchase has been updated successfully.');
                        resetEditPurchaseForm();
                        loadPurchases();
                        loadParties();
                        loadinventory();
                        loadSale();
                        loadStatistics();

                        // Reload data if these functions exist
                        if (typeof loadTransactions === 'function') loadTransactions();
                        if (typeof loadPurchases === 'function') loadPurchases();
                        if (typeof loadinventory === 'function') loadinventory();
                    },
                    error: function(xhr) {
                        let errorMessage = 'There was an error while updating the purchase.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        console.error("Error response:", xhr);
                        showFeedbackModal('error', 'Purchase Update Failed!', errorMessage);
                    }
                });

                return false;
            });

        });

        // Validation Functions
        function validate_SaleData(SaleData) {
            if (SaleData.items.length === 0) {
                return {
                    valid: false,
                    title: 'Validation Error!',
                    message: 'Please add at least one item to the Sales.'
                };
            }

            if (SaleData.paid > SaleData.total_amount) {
                return {
                    valid: false,
                    title: 'Sale record failed!',
                    message: 'Paid amount exceeds Total amount.'
                };
            }

            // Modified to handle nullable part_id correctly
            if (SaleData.paid < SaleData.total_amount) {
                if (!SaleData.part_id) {
                    return {
                        valid: false,
                        title: 'Sale record failed!',
                        message: "You can't record sale debt if there is no Customer."
                    };
                }
            }

            return {
                valid: true
            };
        }

        function validateSaleData(SaleData) {
            if (SaleData.items.length === 0) {
                return {
                    valid: false,
                    title: 'Validation Error!',
                    message: 'Please add at least one item to the Sales.'
                };
            }

            if (SaleData.paid > SaleData.total_amount) {
                return {
                    valid: false,
                    title: 'Sale update failed!',
                    message: 'Paid amount exceeds Total amount.'
                };
            }

            // Modified to handle nullable part_id correctly
            if (SaleData.paid < SaleData.total_amount) {
                if (!SaleData.part_id) {
                    return {
                        valid: false,
                        title: 'Sale update failed!',
                        message: "You can't update sale debt if there is no Customer."
                    };
                }
            }

            return {
                valid: true
            };
        }

        function validate_PurchaseData(purchasesData) {
            if (purchasesData.items.length === 0) {
                return {
                    valid: false,
                    title: 'Validation Error!',
                    message: 'Please add at least one item to the purchase.'
                };
            }

            if (purchasesData.paid > purchasesData.total_amount) {
                return {
                    valid: false,
                    title: 'Purchase record failed!',
                    message: 'Paid amount exceeds Total amount.'
                };
            }

            // Modified to handle nullable part_id correctly
            if (purchasesData.paid < purchasesData.total_amount) {
                if (!purchasesData.part_id) {
                    return {
                        valid: false,
                        title: 'Purchase record failed!',
                        message: "You can't record purchase debt if there is no Supplier."
                    };
                }
            }

            return {
                valid: true
            };
        }

        function validatePurchaseData(purchasesData) {
            if (purchasesData.items.length === 0) {
                return {
                    valid: false,
                    title: 'Validation Error!',
                    message: 'Please add at least one item to the purchase.'
                };
            }

            if (purchasesData.paid > purchasesData.total_amount) {
                return {
                    valid: false,
                    title: 'Purchase update failed!',
                    message: 'Paid amount exceeds Total amount.'
                };
            }

            // Modified to handle nullable part_id correctly
            if (purchasesData.paid < purchasesData.total_amount) {
                if (!purchasesData.part_id) {
                    return {
                        valid: false,
                        title: 'Purchase update failed!',
                        message: "You can't update purchase debt if there is no Supplier."
                    };
                }
            }

            return {
                valid: true
            };
        }

        // Form Reset Functions
        function resetSalesForm() {
            // Clear input fields
            const fieldsToClear = ["search_Customer", "add_sale_description", "add_sale_paid"];
            fieldsToClear.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.value = "";
                }
            });

            // Reset hidden fields
            $('#Customer_ID').val('');

            // Reset calculated values
            $('#add_sale_total').html('0.00 <span>Tzs</span>');
            $('#add_sale_debt').text('0.00');
            $('.ntl').html('<span>TSh</span>0.00');

            // Remove all rows except the first one
            var tbody = $('#record_sales_table tbody');
            var firstRow = tbody.find('tr:first').clone(true);
            tbody.empty().append(firstRow);

            // Clear the first row values
            firstRow.find('input').val('');
            firstRow.find('textarea').val('');
            firstRow.find('.av_quantity').val('0');
            firstRow.find('.ntl').html('<span>TSh</span>0.00');

            // Remove any validation classes
            $('.border-red-500').removeClass('border-2 border-red-500');

            // Hide modal
            hide_add_sales_modal();
        }

        function resetEditSalesForm() {
            // Clear input fields
            $('#edit_description').val('');
            $('#edit_Customer').val('');
            $('#edit_Customer_id').val('');
            $('#edit_paid').val('');

            // Reset calculated values
            $('#edit_total').text('0.00 Tzs');
            $('#edit_debt').text('0.00 Tzs');

            // Clear the items table
            $('#edit_items_container').empty();

            // Add a single empty row
            tableManager.addRow_edit();

            // Remove any validation classes
            $('.border-red-500').removeClass('border-2 border-red-500');
        }

        function resetPurchaseForm() {
            // Clear input fields
            const fieldsToClear = ["search_supplier", "add_purchase_description", "add_purchase_paid"];
            fieldsToClear.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.value = "";
                }
            });

            // Reset hidden fields
            $('#Supplier_ID').val('');

            // Reset calculated values
            $('#add_purchase_total').html('0.00 <span>Tzs</span>');
            $('#add_purchase_debt').text('0.00');
            $('.ntl').html('<span>TSh</span>0.00');

            // Remove all rows except the first one
            var tbody = $('#purchase_record_table tbody');
            var firstRow = tbody.find('tr:first').clone(true);
            tbody.empty().append(firstRow);

            // Clear the first row values
            firstRow.find('input').val('');
            firstRow.find('textarea').val('');
            firstRow.find('.av_quantity').val('0');
            firstRow.find('.ntl').html('<span>TSh</span>0.00');

            // Remove any validation classes
            $('.border-red-500').removeClass('border-2 border-red-500');

            // Hide modal
            hide_add_purchase_modal();
        }

        function resetEditPurchaseForm() {
            // Clear input fields
            $('#edit_description').val('');
            $('#edit_supplier').val('');
            $('#edit_supplier_id').val('');
            $('#edit_paid').val('');

            // Reset calculated values
            $('#edit_total').text('0.00 Tzs');
            $('#edit_debt').text('0.00 Tzs');

            // Clear the items table
            $('#edit_items_container').empty();

            // Add a single empty row
            purchaseTableManager.addRow_edit();

            // Remove any validation classes
            $('.border-red-500').removeClass('border-2 border-red-500');
        }

        // Mathematics and Calculation Functions
        function formatCurrency(amount) {
            try {
                if (typeof amount !== 'number') {
                    amount = parseFloat(amount) || 0;
                }
                return "TSh " + amount.toLocaleString('en-TZ', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } catch (error) {
                console.error('Error formatting currency:', error);
                return "TSh 0.00";
            }
        }

        function totalFn() {
            try {
                // Find the active modal (either sales or purchase)
                const activeModal = $('#add_sales_modal:visible, #add_purchase_modal:visible');
                if (!activeModal.length) {
                    console.warn('No active modal found for total calculation');
                    return;
                }

                // Use the correct total element based on which modal is active
                let totalSelector;
                if (activeModal.attr('id') === 'add_sales_modal') {
                    totalSelector = '#add_sale_total';
                } else {
                    totalSelector = '#add_purchase_total';
                }

                var tl = $(totalSelector);
                var ntlElements = activeModal.find(".ntl");

                if (!tl.length) {
                    console.warn('Total element not found in active modal');
                    return;
                }

                var total = 0;

                ntlElements.each(function() {
                    const text = $(this).text().replace(/[^0-9.-]+/g, "");
                    const value = parseFloat(text) || 0;
                    total += value;
                });

                tl.html(`${total.toFixed(2)} <span>Tzs</span>`);

                // Call calculateDebt with the updated total
                calculateDebt(total);
            } catch (error) {
                console.error('Error in totalFn:', error);
            }
        }

        function calculateDebt(total) {
            try {
                // Find the active modal
                const activeModal = $('#add_sales_modal:visible, #add_purchase_modal:visible');
                if (!activeModal.length) {
                    console.warn('No active modal found for debt calculation');
                    return;
                }

                // Use the correct debt and paid elements based on which modal is active
                let paidSelector, debtSelector;
                if (activeModal.attr('id') === 'add_sales_modal') {
                    paidSelector = '#add_sale_paid';
                    debtSelector = '#add_sale_debt';
                } else {
                    paidSelector = '#add_purchase_paid';
                    debtSelector = '#add_purchase_debt';
                }

                var paidInput = $(paidSelector);
                var deptSpan = $(debtSelector);

                if (!paidInput.length || !deptSpan.length) {
                    console.warn('Paid input or dept span not found in active modal');
                    return;
                }

                var paidAmount = parseFloat(paidInput.val()) || 0;
                var deptAmount = total - paidAmount;

                // Ensure debt is never negative
                deptAmount = Math.max(0, deptAmount);

                // Update the debt display
                deptSpan.text(deptAmount.toFixed(2));
            } catch (error) {
                console.error('Error in calculateDebt:', error);
            }
        }


        $(document).ready(function() {
            // For new sales/purchases
            $('#add_sale_paid, #add_purchase_paid').on('input', function() {
                const activeModal = $(this).closest('#add_sales_modal, #add_purchase_modal');
                let totalSelector;

                if (activeModal.attr('id') === 'add_sales_modal') {
                    totalSelector = '#add_sale_total';
                } else {
                    totalSelector = '#add_purchase_total';
                }

                const totalText = $(totalSelector).text();
                const totalAmount = parseFloat(totalText.replace(/[^0-9.-]+/g, "")) || 0;
                calculateDebt(totalAmount);
            });

            // For edit modals
            $('#sales_edit_paid, #purchase_edit_paid').on('input', function() {
                if ($(this).closest('#editSalesModal').length) {
                    const totalText = $('#sales_edit_total').text();
                    const totalAmount = parseFloat(totalText.replace(/[^0-9.-]+/g, "")) || 0;
                    const paidAmount = parseFloat($(this).val()) || 0;
                    const debtAmount = Math.max(0, totalAmount - paidAmount);
                    $('#sales_edit_debt').text(debtAmount.toFixed(2) + ' Tzs');
                } else {
                    const totalText = $('#purchase_edit_total').text();
                    const totalAmount = parseFloat(totalText.replace(/[^0-9.-]+/g, "")) || 0;
                    const paidAmount = parseFloat($(this).val()) || 0;
                    const debtAmount = Math.max(0, totalAmount - paidAmount);
                    $('#purchase_edit_debt').text(debtAmount.toFixed(2) + ' Tzs');
                }
            });
        });

        function netPriceFn(element) {
            try {
                // Validate input element
                if (!element || !(element instanceof Element)) {
                    console.warn('Invalid element passed to netPriceFn:', element);
                    return;
                }

                // If the element is a payment input, just call calculateDebt and return
                if (element.id === 'add_sale_paid' || element.id === 'add_purchase_paid') {
                    const activeModal = $(element).closest('#add_sales_modal, #add_purchase_modal');
                    const totalElement = activeModal.attr('id') === 'add_sales_modal' ?
                        $('#add_sale_total') :
                        $('#add_purchase_total');

                    if (totalElement.length) {
                        const totalValue = parseFloat(totalElement.text().replace(/[^0-9.-]+/g, "")) || 0;
                        calculateDebt(totalValue);
                    }
                    return;
                }

                // Get the row using jQuery for better compatibility
                const $row = $(element).closest('tr');
                if (!$row.length) {
                    console.warn('Could not find parent row for element:', element);
                    return;
                }

                // Safely get values with proper type conversion
                const $quantity = $row.find('.quantity');
                const $unitPrice = $row.find('.unit_price');
                const $discount = $row.find('.discount');

                // Check if all required elements exist
                if (!$quantity.length || !$unitPrice.length) {
                    console.warn('Required quantity or unit price elements not found in row');
                    return;
                }

                // Parse values safely
                const valQtn = parseFloat($quantity.val()) || 0;
                const valPrice = parseFloat($unitPrice.val().replace(/[^0-9.-]+/g, "")) || 0;
                const valDisc = $discount.length ? (parseFloat($discount.val().replace(/[^0-9.-]+/g, "")) || 0) : 0;

                // Calculate net price
                const netPrice = (valQtn * valPrice) - valDisc;

                // Update net price cell
                const $netPriceCell = $row.find('.ntl');
                if ($netPriceCell.length) {
                    $netPriceCell.html(`<span>TSh</span>${netPrice.toFixed(2)}`);

                    // Call totalFn to update the overall total
                    setTimeout(function() {
                        totalFn();
                    }, 0);
                } else {
                    console.warn('Net price cell not found');
                }
            } catch (error) {
                console.error('Error in netPriceFn:', error);
            }
        }

        // Event listeners for real-time calculation updates
        $(document).ready(function() {
            // For regular forms
            $(document).on('input', '.quantity, .unit_price, .discount, .paid', function() {
                try {
                    netPriceFn(this);
                } catch (error) {
                    console.error('Error in input event handler:', error);
                }
            });

            // For edit forms
            $(document).on('input', '.edit_quantity, .edit_unit_price, .edit_discount, #edit_paid', function() {
                // Determine which manager to use based on the form
                if ($(this).closest('#editPurchaseModal').length) {
                    purchaseTableManager.updateEditTotals();
                } else {
                    tableManager.updateEditTotals();
                }
            });

            // Set today's date as default for all date inputs
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('add_sale_date').value = today;
            document.getElementById('sales_edit_date').value = today;
            document.getElementById('add_purchase_date').value = today;
            document.getElementById('purchase_edit_date').value = today;
            document.getElementById('transaction_date').value = today;
            document.getElementById('edit_transaction_date').value = today;
        });

        // Initialize on page load
        $(document).ready(function() {
            // Initialize TableRowManager if not already done
            if (typeof tableManager === 'undefined') {
                window.tableManager = new TableRowManager('sales');
            }

            if (typeof purchaseTableManager === 'undefined') {
                window.purchaseTableManager = new TableRowManager('purchase');
            }

            // Make sure both modals have their events bound correctly
            $('#add_sales_modal, #add_purchase_modal').each(function() {
                const $modal = $(this);

                // Ensure all quantity, unit_price, discount inputs have proper event handlers
                $modal.find('.quantity, .unit_price, .discount').on('input', function() {
                    netPriceFn(this);
                });

                // Ensure paid input has proper event handler
                $modal.find('.paid').on('input', function() {
                    const totalValue = parseFloat($modal.find('#tl').text().replace(/[^0-9.-]+/g, "")) || 0;
                    calculateDebt(totalValue);
                });
            });
        });

        // Show and fetch data for edit modals
        $(document).on('click', '.show_edit_SalesMODAL', function(e) {
            e.preventDefault();
            var Sales_id = $(this).val();

            $.ajax({
                type: "GET",
                url: "/getSales/" + Sales_id,
                success: function(response) {
                    if (response.status === 'success') {
                        tableManager.populateEditForm(response.data);
                        show_edit_SalesMODAL();
                    } else {
                        showFeedbackModal('error', 'Error', 'Failed to load Sales data');
                    }
                },
                error: function() {
                    showFeedbackModal('error', 'Error', 'Failed to load Sales data');
                }
            });
        });

        $(document).on('click', '.show_edit_purchaseMODAL', function(e) {
            e.preventDefault();
            var purchase_id = $(this).val();

            $.ajax({
                type: "GET",
                url: "/getPurchase/" + purchase_id,
                success: function(response) {
                    if (response.status === 'success') {
                        purchaseTableManager.populateEditForm(response.data);
                        show_edit_purchaseMODAL();
                    } else {
                        showFeedbackModal('error', 'Error', 'Failed to load purchase data');
                    }
                },
                error: function() {
                    showFeedbackModal('error', 'Error', 'Failed to load purchase data');
                }
            });
        });

        // Initialize on page load
        $(document).ready(function() {
            // Initialize TableRowManager if not already done
            if (typeof tableManager === 'undefined') {
                window.tableManager = new TableRowManager('sales');
            }

            if (typeof purchaseTableManager === 'undefined') {
                window.purchaseTableManager = new TableRowManager('purchase');
            }

            // Bind events for both modals
            $('#editSalesModal, #editPurchaseModal').each(function() {
                const $modal = $(this);
                const modalType = $modal.attr('id').includes('Sales') ? 'sales' : 'purchase';

                // Update totals when inputs change
                $modal.find('.edit_quantity, .edit_unit_price, .edit_discount').on('input', function() {
                    if (modalType === 'sales') {
                        tableManager.updateEditTotals();
                    } else {
                        purchaseTableManager.updateEditTotals();
                    }
                });

                // Update debt when paid amount changes
                $modal.find('#sales_edit_paid, #purchase_edit_paid').on('input', function() {
                    if (modalType === 'sales') {
                        tableManager.updateEditTotals();
                    } else {
                        purchaseTableManager.updateEditTotals();
                    }
                });
            });
        });

        function show_SalesPREVIEW() {
            const SalesId = event.target.closest('button').dataset.id;

            // Show the modal
            document.getElementById('Sales_Modal').classList.remove('hidden');
            document.getElementById('print_Invoice').innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-800"></div></div>';

            fetch(`/getSales/${SalesId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const Sales = data.data;

                        // Create optimized invoice HTML
                        const invoiceHTML = `
                        <div class="text-center mb-2">
                            <h1 class="text-lg font-bold">SALES INVOICE</h1>
                            <p class="text-sm">Your Company Name, 123 Business St, City</p>
                            <p class="text-xs">Phone: +123-456-7890 | Email: info@yourcompany.com</p>
                        </div>

                        <div class="grid grid-cols-2 text-xs mb-2 border-b pb-2">
                            <div>
                                <p class="font-semibold">Bill To:</p>
                                <p class="font-bold">${Sales.customer?.name || 'General Customer'}</p>
                                <p>${Sales.customer?.company_name || ''}</p>
                                <p>${Sales.customer?.address || ''}</p>
                                <p>${Sales.customer?.contact_person ? `Attn: ${Sales.customer?.contact_person}` : ''}</p>
                                <p>Phone: ${Sales.customer?.phone_number || ''}</p>
                                <p>Email: ${Sales.customer?.email || ''}</p>
                            </div>
                            <div class="text-right">
                                <p><span class="font-semibold">Invoice No:</span> ${Sales.reference_no}</p>
                                <p><span class="font-semibold">Date:</span> ${new Date(Sales.sale_date).toLocaleDateString()}</p>
                                <p><span class="font-semibold">Status:</span> 
                                    <span class="px-1 py-0.5 rounded text-xs ${
                                        Sales.dept > 0 
                                            ? 'bg-yellow-100 text-yellow-800' 
                                            : (Sales.paid > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')
                                    }">
                                        ${Sales.dept > 0 ? 'Partial Payment' : (Sales.paid > 0 ? 'Paid' : 'Unpaid')}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <table class="w-full text-xs border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-1 text-left">Item</th>
                                    <th class="border p-1 text-right">Qty</th>
                                    <th class="border p-1 text-right">Unit Price</th>
                                    <th class="border p-1 text-right">Discount</th>
                                    <th class="border p-1 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${Sales.sale_items.map(item => `
                                    <tr class="border">
                                        <td class="p-1">${item.item.name}</td>
                                        <td class="p-1 text-right">${item.quantity}</td>
                                        <td class="p-1 text-right">Tsh ${parseFloat(item.sale_price).toFixed(2)}</td>
                                        <td class="p-1 text-right">Tsh ${parseFloat(item.discount).toFixed(2)}</td>
                                        <td class="p-1 text-right">Tsh ${((item.quantity * item.sale_price) - item.discount).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="p-1 text-right font-semibold">Total Discount:</td>
                                    <td class="p-1 text-right">Tsh ${parseFloat(Sales.total_discount).toFixed(2)}</td>
                                </tr>
                                <tr class="font-bold">
                                    <td colspan="4" class="p-1 text-right">Total Amount:</td>
                                    <td class="p-1 text-right">Tsh ${parseFloat(Sales.total_amount).toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="grid grid-cols-2 text-xs mt-2">
                            <div class="border p-2">
                                <h3 class="font-semibold mb-1">Payment Info</h3>
                                <p>Status: ${Sales.dept > 0 ? 'Partial Payment' : (Sales.paid > 0 ? 'Paid' : 'Unpaid')}</p>
                                <p>Paid: Tsh ${parseFloat(Sales.paid).toFixed(2)}</p>
                                <p>Due: Tsh ${parseFloat(Sales.dept).toFixed(2)}</p>
                            </div>
                            <div class="border p-2">
                                <h3 class="font-semibold mb-1">Terms & Conditions</h3>
                                <p>1. Payment due within 30 days.</p>
                                <p>2. Goods remain property of seller until paid.</p>
                                <p>3. Include invoice number with payment.</p>
                            </div>
                        </div>

                        <div class="mt-2 text-center text-xs text-gray-500">
                            <p>Thank you for your business!</p>
                        </div>

                        <div class="mt-2 flex justify-center hidegenerateEFDReceipt">
                            <button onclick="generateEFDReceipt('${Sales.id}')" class="px-3 py-1 bg-blue-600 text-white rounded text-xs">
                                Generate EFD Receipt
                            </button>
                        </div>
                    `;

                        // Update the print_Invoice div with the invoice content
                        document.getElementById('print_Invoice').innerHTML = invoiceHTML;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('print_Invoice').innerHTML = '<div class="text-red-500 text-xs">Error loading invoice data</div>';
                });
        }

        function printInvoice() {
            const printContent = document.getElementById('print_Invoice').innerHTML;

            const printDocument = `
            <!DOCTYPE html>
                <html>
                <head>
                    <title>Sales Invoice</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 12px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 4px; }
                        th { background: #f8f8f8; }
                        .text-right { text-align: right; }
                        .text-center { text-align: center; }
                        @media print {
                        .hidegenerateEFDReceipt {
                            display: none !important;
                        }
                    }
                    </style>
                </head>
                <body>
                    ${printContent.replace(/<button[^>]*>.*?<\/button>/g, '')}
                </body>
            </html>`;

            const printFrame = document.createElement('iframe');
            printFrame.style.position = 'absolute';
            printFrame.style.width = '0px';
            printFrame.style.height = '0px';
            document.body.appendChild(printFrame);

            const doc = printFrame.contentWindow.document;
            doc.open();
            doc.write(printDocument);
            doc.close();

            printFrame.contentWindow.print();
            document.body.removeChild(printFrame);
        }

        function generateEFDReceipt(saleID) {
            let efdModal = document.getElementById('efd_receipt');
            if (!efdModal) {
                efdModal = document.createElement('div');
                efdModal.id = 'efd_receipt';
                efdModal.className = 'fixed inset-0 z-20 flex items-center justify-center hidden';
                efdModal.innerHTML = `
            <div class="bg-white p-3 w-80 rounded shadow border-black border">
                <div class="flex justify-between border-b-2 border-gray-400 pb-2">
                    <h3 class="text-lg font-semibold">EFD Receipt</h3>
                    <button onclick="closeEFDReceipt()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="efd_content" class="p-2 text-sm text-center">
                    <div class="animate-spin h-10 w-10 border-b-2 border-blue-800 mx-auto"></div>
                </div>
                <button onclick="printEFDReceipt()" class="w-full mt-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Print
                </button>
            </div>
        `;
                document.body.appendChild(efdModal);
            }
            efdModal.classList.remove('hidden');

            fetch(`/getSales/${saleID}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status !== 'success') {
                        return document.getElementById('efd_content').innerHTML = '<div class="text-red-500">Error loading sales data</div>';
                    }
                    const sale = data.data;
                    const discountDetails = calculateDiscountPercentage(sale);
                    const efdContent = `
                <div class="font-mono text-xs">
                    <div class="text-center font-bold">YOUR COMPANY NAME</div>
                    <div class="text-center">123 Business St, City</div>
                    <div class="text-center">TIN: 123-456-789 | VRN: V12345678</div>
                    <div class="text-center border-t-2 border-gray-400 border-dashed mt-2 pt-1">
                        <div>Receipt No: ${sale.receipt_number}</div>
                        <div>${new Date().toLocaleString()}</div>
                        <div>EFD: ${sale.efd_number} | Z No: ${sale.z_number}</div>
                    </div>
                    <table class="w-full mt-2">
                        <thead class="border-b-2 border-black border-dashed">
                            <tr><th class="text-left">Item</th><th class="text-right">Qty</th><th class="text-right">Amt</th></tr>
                        </thead>
                        <tbody>
                            ${sale.sale_items.map(item => `
                                <tr>
                                    <td>${item.item.name.substring(0, 14)}</td>
                                    <td class="text-right">${item.quantity}</td>
                                    <td class="text-right">${((item.quantity * item.sale_price) - item.discount).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    <div class="border-t-2 border-gray-400 border-dashed mt-2 pt-1 text-right">
                        <div>Sub: TSH ${(parseFloat(sale.total_amount) + parseFloat(sale.total_discount)).toFixed(2)}</div>
                        <div>Disc(${discountDetails.discountPercentage.toFixed(2)}%): TSH ${parseFloat(sale.total_discount).toFixed(2)}</div>
                        <div class="font-bold">Total: TSH ${parseFloat(sale.total_amount).toFixed(2)}</div>
                    </div>
                    <div class="border-t-2 border-gray-400 border-dashed mt-2 pt-1 text-right">
                        <div>Cash: TSH ${parseFloat(sale.paid).toFixed(2)}</div>
                        ${sale.dept > 0 ? `<div>Debt: TSH ${parseFloat(sale.dept).toFixed(2)}</div>` : ''}
                    </div>
                    <div class="text-center mt-2">
                        <div>Customer: ${sale.customer?.name || 'General Customer'}</div>
                        <div>Ref: ${sale.reference_no}</div>
                        <div class="font-bold mt-1">THANK YOU!</div>
                        <div class="text-xs">*Item Quote Receipt*</div>
                    </div>
                    <div class="mt-2 flex justify-center"><svg id="barcode"></svg></div>
                </div>
            `;
                    document.getElementById('efd_content').innerHTML = efdContent;
                    generateBarcode(sale.barcode_text || sale.reference_no);
                })
                .catch(() => {
                    document.getElementById('efd_content').innerHTML = '<div class="text-red-500">Error generating EFD receipt</div>';
                });
        }

        // Function to generate barcode
        function generateBarcode(text) {
            const script = document.createElement('script');
            script.src = "https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js";
            script.onload = function() {
                JsBarcode("#barcode", text, {
                    format: "CODE128",
                    width: 1.5,
                    height: 30,
                    displayValue: false
                });
            };
            document.head.appendChild(script);
        }

        function calculateDiscountPercentage(sale) {
            const totalBeforeDiscount = parseFloat(sale.total_amount) + parseFloat(sale.total_discount);
            const discountPercentage = (sale.total_discount / totalBeforeDiscount) * 100;

            return {
                totalBeforeDiscount,
                discountPercentage: isNaN(discountPercentage) ? 0 : discountPercentage
            };
        }

        // Function to print the EFD receipt
        function printEFDReceipt() {
            const printContent = document.getElementById('efd_content').innerHTML;

            // Create a hidden iframe
            const printIframe = document.createElement('iframe');
            printIframe.style.position = 'absolute';
            printIframe.style.width = '0';
            printIframe.style.height = '0';
            printIframe.style.left = '-9999px';
            document.body.appendChild(printIframe);

            // Write the content to the iframe
            const iframeDoc = printIframe.contentDocument || printIframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>EFD Receipt</title>
            <style>
                html, body {
                    height: 100%;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                .receipt-container {
                    width: 80mm;
                    font-family: monospace;
                    padding: 10px;
                    border: 1px dashed #ccc;
                    background-color: white;
                }
                @media print {
                    @page {
                        size: 80mm auto;
                        margin: 0;
                    }
                    html, body {
                        height: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                    .receipt-container {
                        border: none;
                        width: 100%;
                        max-width: 80mm;
                    }
                }
            </style>
        </head>
        <body>
            <div class="receipt-container">
                ${printContent}
            </div>
        </body>
        </html>
    `);
            iframeDoc.close();

            // Wait for iframe to fully load before printing
            printIframe.onload = function() {
                try {
                    // Focus the iframe for better printing experience
                    printIframe.contentWindow.focus();

                    // Print the iframe
                    printIframe.contentWindow.print();

                    // Remove the iframe after printing (use setTimeout to ensure print dialog has time to appear)
                    setTimeout(function() {
                        document.body.removeChild(printIframe);
                    }, 1000);
                } catch (e) {
                    console.error('Printing failed:', e);
                    document.body.removeChild(printIframe);
                }
            };
        }

        // Function to reattach event handlers after printing
        function attachEventHandlers() {
            // Re-initialize form validation
            $("#sales_form").validate({
                // ... ( validation code)
            });

            // Add other event handlers here
            document.querySelector('#efd_receipt button').onclick = closeEFDReceipt;
            document.querySelector('.flex.justify-center.mt-4 button').onclick = printEFDReceipt;
        }

        function show_purchasePREVIEW() {
            const purchaseId = event.target.closest('button').dataset.id;

            // Show the modal
            document.getElementById('purchase_Modal').classList.remove('hidden');
            document.getElementById('print_Purchase_Invoice').innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-800"></div></div>';

            // Fetch purchase details
            fetch(`/getPurchase/${purchaseId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const purchase = data.data;

                        // Check if supplier exists, use empty object if null to avoid errors
                        const supplier = purchase.supplier || {};

                        // Format date
                        const purchaseDate = new Date(purchase.purchase_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        // Calculate subtotal (before discount)
                        const subtotal = parseFloat(purchase.total_amount) + parseFloat(purchase.total_discount);

                        // Create invoice HTML with improved layout
                        const invoiceHTML = `
                <div class="p-4 bg-white">
                    <!-- Header Section -->
                    <div class="border-b border-gray-300 pb-3 mb-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-lg font-bold text-gray-800">PURCHASE INVOICE</h1>
                                <p class="text-xs text-gray-600 mt-1">Reference: ${purchase.reference_no}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-base font-semibold text-gray-800">YOUR COMPANY NAME</div>
                                <p class="text-xs text-gray-600">123 Business Street, City</p>
                                <p class="text-xs text-gray-600">Phone: +255 123 456 789</p>
                                <p class="text-xs text-gray-600">Email: info@yourcompany.com</p>
                                <p class="text-xs text-gray-600">TIN: xxx-xxx-xxx</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info Section -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <!-- Supplier Information -->
                        <div class="bg-gray-50 p-2 rounded">
                            <h2 class="font-semibold text-xs text-gray-700 border-b border-gray-300 pb-1 mb-1">SUPPLIER INFORMATION</h2>
                            <div class="text-xs text-gray-700">
                                ${supplier.name ? `<p class="font-medium">${supplier.name}</p>` : '<p class="italic text-gray-500">No supplier specified</p>'}
                                ${supplier.company_name ? `<p>${supplier.company_name}</p>` : ''}
                                ${supplier.address ? `<p>${supplier.address}</p>` : ''}
                                ${supplier.contact_person ? `<p>Contact: ${supplier.contact_person}</p>` : ''}
                                ${supplier.phone_number ? `<p>Phone: ${supplier.phone_number}</p>` : ''}
                                ${supplier.email ? `<p>Email: ${supplier.email}</p>` : ''}
                                ${supplier.vat_number ? `<p>VAT: ${supplier.vat_number}</p>` : ''}
                                ${supplier.tin_number ? `<p>TIN: ${supplier.tin_number}</p>` : ''}
                            </div>
                        </div>
                        
                        <!-- Purchase Details -->
                        <div class="bg-gray-50 p-2 rounded">
                            <h2 class="font-semibold text-xs text-gray-700 border-b border-gray-300 pb-1 mb-1">PURCHASE DETAILS</h2>
                            <div class="grid grid-cols-2 gap-1 text-xs text-gray-700">
                                <div class="font-medium">Date:</div>
                                <div>${purchaseDate}</div>
                                
                                <div class="font-medium">Status:</div>
                                <div><span class="px-1 py-0.5 rounded text-xs ${
                                    purchase.status === 'Confirmed' ? 'bg-green-100 text-green-800' : 
                                    purchase.status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-gray-100 text-gray-800'
                                }">${purchase.status}</span></div>
                                
                                <div class="font-medium">Payment Status:</div>
                                <div><span class="px-1 py-0.5 rounded text-xs ${
                                    purchase.dept > 0 ? 'bg-yellow-100 text-yellow-800' : 
                                    (purchase.paid > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')
                                }">${purchase.dept > 0 ? 'Partial' : (purchase.paid > 0 ? 'Paid' : 'Unpaid')}</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="mb-3">
                        <h2 class="font-semibold text-xs text-gray-700 mb-1">PURCHASED ITEMS</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-1 px-1 border-b text-left text-xs font-medium text-gray-600 uppercase tracking-wider">#</th>
                                        <th class="py-1 px-1 border-b text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Item Description</th>
                                        <th class="py-1 px-1 border-b text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Qty</th>
                                        <th class="py-1 px-1 border-b text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Unit</th>
                                        <th class="py-1 px-1 border-b text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Disc</th>
                                        <th class="py-1 px-1 border-b text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Exp Date</th>
                                        <th class="py-1 px-1 border-b text-right text-xs font-medium text-gray-600 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${purchase.purchase_items.map((item, index) => `
                                        <tr class="${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                                            <td class="py-1 px-1 border-b border-gray-200 text-xs">${index + 1}</td>
                                            <td class="py-1 px-1 border-b border-gray-200 text-xs">
                                                <div class="font-medium">${item.item ? item.item.name : 'Unknown Item'}</div>
                                                <div class="text-xs text-gray-500">SKU: ${item.item ? item.item.sku : 'N/A'}</div>
                                                ${item.item && item.item.description ? `<div class="text-xs text-gray-500">${item.item.description}</div>` : ''}
                                            </td>
                                            <td class="py-1 px-1 border-b border-gray-200 text-xs text-right">${item.quantity}</td>
                                            <td class="py-1 px-1 border-b border-gray-200 text-xs text-right">Tsh ${parseFloat(item.purchase_price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                            <td class="py-1 px-1 border-b border-gray-200 text-xs text-right">Tsh ${parseFloat(item.discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                            <td class="py-1 px-1 border-b border-gray-200 text-xs text-right">${item.expire_date ? new Date(item.expire_date).toLocaleDateString() : '-'}</td>
                                            <td class="py-1 px-1 border-b border-gray-200 text-xs text-right font-medium">Tsh ${((item.quantity * item.purchase_price) - item.discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Summary Section -->
                    <div class="flex justify-end mb-3">
                        <div class="w-1/2 bg-gray-50 rounded p-2">
                            <div class="flex justify-between border-b border-gray-200 py-1">
                                <div class="text-xs text-gray-600">Subtotal:</div>
                                <div class="text-xs font-medium">Tsh ${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 py-1">
                                <div class="text-xs text-gray-600">Discount:</div>
                                <div class="text-xs font-medium">Tsh ${parseFloat(purchase.total_discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 py-1">
                                <div class="text-xs text-gray-600">Total Amount:</div>
                                <div class="text-xs font-medium">Tsh ${parseFloat(purchase.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 py-1">
                                <div class="text-xs text-gray-600">Paid Amount:</div>
                                <div class="text-xs font-medium">Tsh ${parseFloat(purchase.paid).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                            </div>
                            <div class="flex justify-between py-1">
                                <div class="text-xs font-semibold">Outstanding Balance:</div>
                                <div class="text-xs font-bold ${parseFloat(purchase.dept) > 0 ? 'text-red-600' : 'text-green-600'}">
                                    Tsh ${parseFloat(purchase.dept).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Section -->
                    <div class="border-t border-gray-300 pt-2 mt-2 text-center text-gray-600 text-xs">
                        <p>This is a computer generated invoice and does not require a signature.</p>
                        <p>For any queries regarding this invoice, please contact our accounts department.</p>
                        <p class="mt-1 text-xs">Purchase ID: ${purchase.id} | Generated on: ${new Date().toLocaleString()}</p>
                    </div>
                    
                    <!-- Print Button -->
                    <div class="mt-3 flex justify-end no_print">
                        <button onclick="print_PurchaseInvoice()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-xs">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Invoice
                        </button>
                    </div>
                </div>
                `;

                        // Update the print_Purchase_Invoice div with the invoice content
                        document.getElementById('print_Purchase_Invoice').innerHTML = invoiceHTML;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('print_Purchase_Invoice').innerHTML = '<div class="text-red-500">Error loading invoice data</div>';
                });
        }


        // Function to print the purchase invoice - preserves exact preview style
        function print_PurchaseInvoice() {
            const printContent = document.getElementById('print_Purchase_Invoice').innerHTML;

            // Create a hidden iframe for printing
            const printIframe = document.createElement('iframe');
            printIframe.style.position = 'absolute';
            printIframe.style.width = '0';
            printIframe.style.height = '0';
            printIframe.style.left = '-9999px';
            document.body.appendChild(printIframe);

            // Write the content to the iframe with styles matching preview exactly
            const iframeDoc = printIframe.contentDocument || printIframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Purchase Invoice</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <!-- Include Tailwind CSS - same as in the main document -->
                    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                    <style>
                        @page {
                            margin: 10mm;
                        }
                        
                        /* Remove shadows for printing */
                        @media print {
                            .shadow, .shadow-md, .shadow-lg, .shadow-xl {
                                box-shadow: none !important;
                            }

                            .no_print {
                                display: none !important;
                            }
                            
                            body {
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                        }
                    </style>
                </head>
                <body class="bg-white">
                    ${printContent}
                </body>
                </html>
             `);
            iframeDoc.close();

            // Wait for iframe to fully load before printing
            printIframe.onload = function() {
                try {
                    // Focus the iframe for better printing experience
                    printIframe.contentWindow.focus();

                    // Print the iframe
                    printIframe.contentWindow.print();

                    // Remove the iframe after printing (use setTimeout to ensure print dialog has time to appear)
                    setTimeout(function() {
                        document.body.removeChild(printIframe);
                    }, 1000);
                } catch (e) {
                    console.error('Printing failed:', e);
                    document.body.removeChild(printIframe);
                }
            };
        }


        $(document).ready(function() {
            // CSRF Token setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#addPartyForm').submit(function(e) {
                e.preventDefault();

                $('.text-red-600').addClass('hidden');

                const formData = {
                    name: $('#part_name').val(),
                    type: $('#type').val(),
                    gender: $('#part_gender').val(),
                    address: $('#address').val(),
                    vat_number: $('#vat_number').val(),
                    tin_number: $('#tin_number').val(),
                    phone_number: $('#phone_number').val(),
                    email: $('#email').val(),
                    company_name: $('#company_name').val(),
                    contact_person: $('#contact_person').val()
                };

                $.ajax({
                    url: '/store_party',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#addPartyModal').addClass('hidden');
                            showFeedbackModal('success', 'Success!', response.message);
                            loadParties();
                            loadStatistics();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            for (const field in errors) {
                                $(`.${field}-error`).removeClass('hidden').text(errors[field][0]);
                            }
                        } else {
                            showFeedbackModal('error', 'Error!', xhr.responseJSON.message || 'Failed to add party');
                        }
                    }
                });
            });

            // Edit Party Form Submit
            $('#editPartyForm').submit(function(e) {
                e.preventDefault();

                // Reset error messages
                $('.text-red-600').addClass('hidden');

                const partyId = $('#edit_party_id').val();

                // Get form data
                const formData = {
                    name: $('#edit_name').val(),
                    type: $('#edit_type').val(),
                    address: $('#edit_address').val(),
                    vat_number: $('#edit_vat_number').val(),
                    tin_number: $('#edit_tin_number').val(),
                    phone_number: $('#edit_phone_number').val(),
                    email: $('#edit_email').val(),
                    company_name: $('#edit_company_name').val(),
                    contact_person: $('#edit_contact_person').val()
                };

                // Send AJAX request
                $.ajax({
                    url: `/update_part/${partyId}`,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            // Close modal
                            $('#editPartyModal').addClass('hidden');

                            // Show success message
                            showFeedbackModal('success', 'Success!', response.message);
                            loadParties();
                            loadStatistics();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            // Display validation errors
                            for (const field in errors) {
                                $(`.edit-${field}-error`).removeClass('hidden').text(errors[field][0]);
                            }
                        } else {
                            // Show error message
                            showFeedbackModal('error', 'Error!', xhr.responseJSON.message || 'Failed to update party');
                        }
                    }
                });
            });

            $('#confirmDeleteParty').click(function() {

                const partyId = $('#delete_party_id').val();

                $.ajax({
                    url: `/delete_part/${partyId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Close modal
                            $('#deletePartyModal').addClass('hidden');
                            loadParties();
                            loadStatistics();
                            // Show success message
                            showFeedbackModal('success', 'Success!', response.message);

                        }
                    },
                    error: function(xhr) {
                        // Close modal
                        $('#deletePartyModal').addClass('hidden');

                        // Show error message
                        showFeedbackModal('error', 'Error!', xhr.responseJSON.message || 'Failed to delete party');
                    }
                });
            });

        });





        // Unified part selection handler
        $(document).on('click', '.part_li', function(e) {
            e.preventDefault();
            e.stopPropagation();

            try {
                const selectedItem = $(this);
                // Get the trimmed text from the span only
                const partName = selectedItem.find('span:first').text().trim();
                const partId = selectedItem.data('id');
                const partType = selectedItem.data('type');

                // Find the closest parent container
                const container = selectedItem.closest('.relative');
                const searchInput = container.find('.search_part');

                // Determine which form we're in based on input ID
                const inputId = searchInput.attr('id');
                let partIdField, partTypeField, partBalanceField;

                if (inputId === 'search_partFOR_payment') {
                    // Add transaction modal
                    partIdField = $('#Part_ID_payment');
                    partTypeField = $('#Part_Type_payment');
                    partBalanceField = $('#part_balance');

                    // Update radio buttons to match part type
                    $(`.part_type_radio[value="${partType}"]`).prop('checked', true);

                    // Toggle visibility of transaction tables
                    if (partType === 'Supplier') {
                        $('#supplier_transactions_container').removeClass('hidden');
                        $('#customer_transactions_container').addClass('hidden');

                        // Fetch supplier transactions
                        fetchPartPurchases(partId, 'purchases_TableBody');
                    } else if (partType === 'Customer') {
                        $('#supplier_transactions_container').addClass('hidden');
                        $('#customer_transactions_container').removeClass('hidden');

                        // Fetch customer transactions
                        fetchPartSales(partId, 'Sale_TableBody');
                    }
                } else if (inputId === 'search_partFOR_paymentEDIT') {
                    // Edit transaction modal
                    partIdField = $('#Part_ID_paymentEDIT');
                    partTypeField = $('#Part_Type_paymentEDIT');
                    partBalanceField = $('#part_balanceEDIT');

                    // Update radio buttons to match part type
                    $(`.part_type_radioEDIT[value="${partType}"]`).prop('checked', true);

                    // Toggle visibility of transaction tables
                    if (partType === 'Supplier') {
                        $('#supplier_transactions_containerEDIT').removeClass('hidden');
                        $('#customer_transactions_containerEDIT').addClass('hidden');

                        // Fetch supplier transactions
                        fetchPartPurchases(partId, 'purchases_TableBodyEDIT');
                    } else if (partType === 'Customer') {
                        $('#supplier_transactions_containerEDIT').addClass('hidden');
                        $('#customer_transactions_containerEDIT').removeClass('hidden');

                        // Fetch customer transactions
                        fetchPartSales(partId, 'Sale_TableBodyEDIT');
                    }
                }

                // Update input fields
                searchInput.val(partName);
                partIdField.val(partId);
                partTypeField.val(partType);

                // Fetch part balance
                fetchPartBalance(partId);

                // Hide the part list
                selectedItem.closest('.partlist').fadeOut();
            } catch (error) {
                console.error('Error in part selection:', error);
            }
        }); // Unified part search functionality


        // Unified part selection handler
        $(document).on('click', '.part_li', function(e) {
            e.preventDefault();
            e.stopPropagation();

            try {
                const selectedItem = $(this);
                // Get the trimmed text from the span only
                const partName = selectedItem.find('span:first').text().trim();
                const partId = selectedItem.data('id');
                const partType = selectedItem.data('type');

                // Find the closest parent container
                const container = selectedItem.closest('.relative');
                const searchInput = container.find('.search_part');

                // Determine which form we're in based on input ID
                const inputId = searchInput.attr('id');
                let partIdField, partTypeField, partBalanceField;

                if (inputId === 'search_partFOR_payment') {
                    // Add transaction modal
                    partIdField = $('#Part_ID_payment');
                    partTypeField = $('#Part_Type_payment');
                    partBalanceField = $('#part_balance');

                    // Set appropriate table ID based on part type
                    const tableId = partType === 'Supplier' ? 'purchases_TableBody' : 'Sale_TableBody';

                    // Fetch transactions based on part type
                    if (partType === 'Supplier') {
                        fetchPartPurchases(partId, tableId);
                    } else if (partType === 'Customer') {
                        fetchPartSales(partId, tableId);
                    }
                } else if (inputId === 'search_partFOR_paymentEDIT') {
                    // Edit transaction modal
                    partIdField = $('#Part_ID_paymentEDIT');
                    partTypeField = $('#Part_Type_paymentEDIT');
                    partBalanceField = $('#part_balanceEDIT');

                    // Set appropriate table ID based on part type
                    const tableId = partType === 'Supplier' ? 'purchases_TableBodyEDIT' : 'Sale_TableBodyEDIT';

                    // Fetch transactions based on part type
                    if (partType === 'Supplier') {
                        fetchPartPurchases(partId, tableId);
                    } else if (partType === 'Customer') {
                        fetchPartSales(partId, tableId);
                    }
                }

                // Update input fields
                searchInput.val(partName);
                partIdField.val(partId);
                partTypeField.val(partType);

                // Fetch part balance
                fetchPartBalance(partId);

                // Hide the part list
                selectedItem.closest('.partlist').fadeOut();
            } catch (error) {
                console.error('Error in part selection:', error);
            }
        });

        // Fetch part balance with unified approach
        function fetchPartBalance(partId) {
            $.ajax({
                type: 'GET',
                url: '/find_part_balance',
                data: {
                    'id': partId
                },
                dataType: 'json',
                success: function(data) {
                    $('.part_balance').val(parseFloat(data.dept).toFixed(2));
                },
                error: function() {
                    console.log('Error fetching part balance');
                }
            });
        }

        // Fetch part purchases (for suppliers)
        function fetchPartPurchases(partId, tableId) {
            $.ajax({
                type: 'GET',
                url: '/fetch_part_transactions',
                data: {
                    'id': partId,
                    'type': 'Supplier'
                },
                dataType: 'json',
                success: function(data) {
                    var purchasesHTML = '';
                    data.forEach(function(purchase) {
                        // Format amounts
                        var formattedTotalAmount = new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        }).format(purchase.total_amount);

                        var formattedBalance = new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        }).format(purchase.dept);

                        var formattedPayedAmount = new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        }).format(purchase.paid);

                        let formatted_purchaseDate = '';
                        if (purchase?.purchase_date) {
                            const date = new Date(purchase.purchase_date);
                            formatted_purchaseDate = date.toISOString().split('T')[0];
                        }
                        purchasesHTML += `
                    <tr class="bg-white border-b border-blue-500 ${tableId.includes('EDIT') ? 'edit_purchaseID' : ''}" ${tableId.includes('EDIT') ? 'data-id="' + purchase.id + '"' : ''}>
                        <td>${tableId.includes('EDIT') ? '' : '<input type="checkbox" value="' + purchase.id + '" class="chkbox ml-1" />'}</td>
                        <td class="py-1 px-2 text-left whitespace-nowrap">${formatted_purchaseDate}</td>
                        <td class="py-1 px-2 text-left whitespace-nowrap">${formattedTotalAmount}</td>
                        <td class="py-1 px-2 text-left whitespace-nowrap">${formattedPayedAmount}</td>
                        <td id="balanceID" class="${tableId.includes('EDIT') ? 'balanceEDIT' : 'balance'} py-1 px-2 text-left whitespace-nowrap">${formattedBalance}</td>
                    </tr>
                `;
                    });
                    $('#' + tableId).html(purchasesHTML);
                },
                error: function() {
                    console.log('Error fetching part transactions');
                }
            });
        }

        // Fetch part sales (for customers)
        function fetchPartSales(partId, tableId) {
            $.ajax({
                type: 'GET',
                url: '/fetch_part_transactions',
                data: {
                    'id': partId,
                    'type': 'Customer'
                },
                dataType: 'json',
                success: function(data) {
                    var salesHTML = '';
                    data.forEach(function(sale) {
                        // Format amounts
                        var formattedTotalAmount = new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        }).format(sale.total_amount);

                        var formattedBalance = new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        }).format(sale.dept);

                        var formattedPayedAmount = new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        }).format(sale.paid);

                        let formatted_SaleDate = '';
                        if (sale?.sale_date) {
                            const date = new Date(sale.sale_date);
                            formatted_SaleDate = date.toISOString().split('T')[0];
                        }
                        salesHTML += `
                    <tr class="bg-white border-b border-blue-500 ${tableId.includes('EDIT') ? 'edit_saleID' : ''}" ${tableId.includes('EDIT') ? 'data-id="' + sale.id + '"' : ''}>
                        <td>${tableId.includes('EDIT') ? '' : '<input type="checkbox" value="' + sale.id + '" class="chkbox ml-1" />'}</td>
                        <td class="py-1 px-2 text-left whitespace-nowrap">${formatted_SaleDate}</td>
                        <td class="py-1 px-2 text-left whitespace-nowrap">${formattedTotalAmount}</td>
                        <td class="py-1 px-2 text-left whitespace-nowrap">${formattedPayedAmount}</td>
                        <td id="balanceID" class="${tableId.includes('EDIT') ? 'balanceEDIT' : 'balance'} py-1 px-2 text-left whitespace-nowrap">${formattedBalance}</td>
                    </tr>
                `;
                    });
                    $('#' + tableId).html(salesHTML);
                },
                error: function() {
                    console.log('Error fetching part transactions');
                }
            });
        }

        // Updated function to handle supplier and customer payment sections
        function handlePartPaymentSection(isChecked) {
            const $partSearchInput = $('#search_partFOR_payment');
            const $appliedAmount = $('#applied_amount');
            const $deptPaid = $('#dept_paid');
            const $partIdInput = $('#Part_ID_payment');
            const $partTypeInput = $('#Part_Type_payment');
            const $otherPayment = $('.other_payment');

            if (isChecked) {
                // Enable part inputs
                $partSearchInput.prop('disabled', false);
                $appliedAmount.prop('disabled', false);
                $deptPaid.prop('disabled', false);

                // Disable and clear other payment inputs
                $('#parson_name').prop('disabled', true);
                $('#payment_amount').prop('disabled', true);
                $('#parson_name').val('');
                $('#payment_amount').val('');

                // Uncheck other payment checkbox
                $otherPayment.prop('checked', false);
            } else {
                // Reset form state if neither payment type is checked
                if (!$otherPayment.is(':checked')) {
                    $partSearchInput.prop('disabled', true);
                    $appliedAmount.prop('disabled', true);
                    $deptPaid.prop('disabled', true);

                    // Important: Clear the part IDs
                    $partIdInput.val('');
                    $partTypeInput.val('');

                    // Clear tables
                    $('#purchases_TableBody, #Sale_TableBody').html(`
                <tr class="bg-white border-b border-blue-500">
                    <td class="py-1 px-2 text-center text-red-600 italic" colspan="6">
                        No transactions found! Please search for a part first.
                    </td>
                </tr>
            `);
                }
            }
        }

        // Update the payment type radio button handler
        $(document).on('change', '.payment_type_radio', function() {
            const paymentType = $(this).val();
            // Store the payment type in the hidden input field
            $('#payment_type').val(paymentType);

            // If part payment is selected, check if we need to adjust part type
            if ($('.part_payment').is(':checked')) {
                const currentPartType = $('#Part_Type_payment').val();

                // If payment type and part type are incompatible, show warning
                if ((paymentType === 'Payment' && currentPartType === 'Customer') ||
                    (paymentType === 'Receipt' && currentPartType === 'Supplier')) {
                    showFeedbackModal('warning', 'Warning',
                        'The selected payment type is not typical for this part type. ' +
                        'Suppliers usually receive payments, and customers usually provide receipts.');
                }
            }
        });

        // Same for edit modal
        $(document).on('change', '.payment_type_radioEDIT', function() {
            const paymentType = $(this).val();
            // Store the payment type in the hidden input field
            $('#payment_type_edit').val(paymentType);

            // If part payment is selected, check if we need to adjust part type
            if ($('#edit_supplierANDcustomer_paymentCHECKBOXID').is(':checked')) {
                const currentPartType = $('#Part_Type_paymentEDIT').val();

                // If payment type and part type are incompatible, show warning
                if ((paymentType === 'Payment' && currentPartType === 'Customer') ||
                    (paymentType === 'Receipt' && currentPartType === 'Supplier')) {
                    showFeedbackModal('warning', 'Warning',
                        'The selected payment type is not typical for this part type. ' +
                        'Suppliers usually receive payments, and customers usually provide receipts.');
                }
            }
        });

        // Update form submission to include payment type
        $("#payment_out_form").validate({
            rules: {
                transaction_date: {
                    required: true
                },
                method: {
                    required: true
                },
                journal_memo: {
                    required: true
                },
                person_name: {
                    required: function() {
                        return $('.other_payment').is(':checked');
                    }
                },
                payment_amount: {
                    required: function() {
                        return $('.other_payment').is(':checked');
                    },
                    number: true
                },
                dept_paid: {
                    required: function() {
                        return $('.part_payment').is(':checked');
                    },
                    number: true
                }
            },
            errorPlacement: function(error, element) {
                return true;
            },
            highlight: function(element) {
                $(element).addClass("border-2 border-red-500");
            },
            unhighlight: function(element) {
                $(element).removeClass("border-2 border-red-500");
            },
            submitHandler: async function(form) {
                event.preventDefault();
                try {
                    const formData = new FormData(form);

                    // Determine which payment type we're dealing with
                    const isOtherPayment = $('.other_payment').is(':checked');
                    const isPartPayment = $('.part_payment').is(':checked');

                    if (!isOtherPayment && !isPartPayment) {
                        showFeedbackModal('error', 'Payment Failed!', 'Please select either Other Payment or Part Payment.');
                        return;
                    }

                    const paymentType = $('input[name="payment_type"]:checked').val() || 'Payment';

                    // Start with common data
                    let transactionData = {
                        transaction_date: formData.get('transaction_date'),
                        journal_memo: formData.get('journal_memo'),
                        method: formData.get('method'),
                        type: paymentType, // Add the payment type
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    // Add payment type specific data
                    if (isOtherPayment) {
                        // Handle other payment (use person_name)
                        transactionData = {
                            ...transactionData,
                            person_name: $('#parson_name').val(),
                            payment_amount: $('#payment_amount').val()
                        };

                        // Use record_payment_out for Payment type and record_payment_in for Receipt type
                        const endpoint = paymentType === 'Payment' ? '/record_payment_out' : '/record_payment_in';

                        const response = await $.ajax({
                            url: endpoint,
                            type: 'POST',
                            data: transactionData
                        });

                        // Check if the response indicates success
                        if (response && response.success === true) {
                            showFeedbackModal('success', 'Payment Recorded!', 'Payment has been recorded successfully.');

                            // Reset form
                            resetPaymentForm();
                            hide_addTransactionModal();
                            // Reload data if needed
                            loadTransactions();
                            loadParties();
                            loadPurchases();
                            loadSale();
                            loadinventory();
                            loadStatistics();
                        } else {
                            // Handle unexpected successful response format
                            showFeedbackModal('error', 'Unexpected Response', 'Received successful response but in unexpected format.');
                            console.log('Unexpected response format:', response);
                        }
                    } else if (isPartPayment) {
                        // Handle part payment
                        const partId = $('#Part_ID_payment').val();
                        const partType = $('#Part_Type_payment').val();

                        if (!partId) {
                            showFeedbackModal('error', 'Payment Failed!', 'Please select a supplier or customer for part payment.');
                            return;
                        }

                        // Determine which table to use based on part type
                        const tableSelector = partType === 'Supplier' ? '#purchases_TableBody' : '#Sale_TableBody';

                        // Get checked transactions
                        const checkedTransactions = $(`${tableSelector} .chkbox:checked`);

                        if (checkedTransactions.length === 0) {
                            showFeedbackModal('error', 'Payment Failed!', 'Please select at least one transaction to pay.');
                            return;
                        }

                        // Replace this part in your payment_out_form submitHandler:
                        const transactionsData = [];
                        checkedTransactions.each(function() {
                            const $row = $(this).closest('tr');
                            const $balanceCell = $row.find('.balance');

                            // Get original balance from data attribute
                            const originalBalance = $balanceCell.data('original-balance') ||
                                parseFloat($balanceCell.text().replace(/[^\d.-]/g, ''));
                            // Get current displayed balance
                            const newBalance = parseFloat($balanceCell.text().replace(/[^\d.-]/g, ''));

                            transactionsData.push({
                                id: $(this).val(),
                                newBalance: newBalance
                            });
                        });

                        transactionData = {
                            ...transactionData,
                            part_id: partId,
                            part_type: partType,
                            transactions_data: JSON.stringify(transactionsData),
                            dept_paid: $('#dept_paid').val()
                        };

                        // Determine endpoint based on part type and payment type
                        // For Supplier + Payment = payment_out, Customer + Receipt = payment_in
                        let endpoint;
                        if (partType === 'Supplier' && paymentType === 'Payment') {
                            endpoint = '/record_payment_out';
                        } else if (partType === 'Customer' && paymentType === 'Receipt') {
                            endpoint = '/record_payment_in';
                        } else {
                            showFeedbackModal('error', 'Invalid Combination', 'The combination of part type and payment type is invalid.');
                            return;
                        }

                        const response = await $.ajax({
                            url: endpoint,
                            type: 'POST',
                            data: transactionData
                        });

                        // Check if the response indicates success
                        if (response && response.success === true) {
                            showFeedbackModal('success', 'Payment Recorded!', 'Payment has been recorded successfully.');

                            // Reset form
                            resetPaymentForm();
                            hide_addTransactionModal();

                            // Reload data if needed
                            loadTransactions();
                            loadSale();
                            loadPurchases();
                            loadParties();
                            loadinventory();
                            loadStatistics();
                        } else {
                            // Handle unexpected successful response format
                            showFeedbackModal('error', 'Unexpected Response', 'Received successful response but in unexpected format.');
                            console.log('Unexpected response format:', response);
                        }
                    }

                } catch (error) {
                    console.error('Error submitting form:', error);
                    const errorMessage = error.responseJSON?.message || 'There was an error while recording the payment.';
                    const detailedErrors = error.responseJSON?.errors ? Object.values(error.responseJSON.errors).flat().join('<br>') : '';

                    showFeedbackModal('error', 'Payment Failed!', errorMessage + (detailedErrors ? '<br><br>' + detailedErrors : ''));
                }
            }
        });

        // Update the payment type handlers for the radio buttons
        function handlePaymentTypeSelection() {
            // Default payment type based on part type
            if ($('.part_payment').is(':checked')) {
                const partType = $('#Part_Type_payment').val();

                if (partType === 'Supplier') {
                    // For supplier, default to Payment type
                    $('input[name="Payment"][value="Payment"]').prop('checked', true);
                } else if (partType === 'Customer') {
                    // For customer, default to Receipt type
                    $('input[name="Receipt"][value="Receipt"]').prop('checked', true);
                }
            }
        }


        // Helper function to handle successful response
        function handleSuccessResponse(response) {
            showFeedbackModal('success', 'Payment Recorded!', 'Payment has been recorded successfully.');

            // Reset form
            resetPaymentForm();

        }

        // Function to reset the payment form
        function resetPaymentForm() {
            // Clear input fields
            $('#parson_name').val('');
            $('#payment_amount').val('');
            $('#journal_memo').val('');
            $('#method').val('');
            $('#applied_amount').val('');
            $('#dept_paid').val('');
            $('#search_partFOR_payment').val('');
            $('#part_balance').val('');
            $('#Part_ID_payment').val('');
            $('#part_balance').val('');

            // Uncheck checkboxes
            $('.other_payment').prop('checked', false);
            $('.part_payment').prop('checked', false);

            // Reinitialize form state
            initializeFormState();

            // Clear tables
            $('#purchases_TableBody').html(`
        <tr class="bg-white border-b border-blue-500">
            <td class="py-1 px-2 text-center text-red-600 italic" colspan="6">
                No purchases found! Please search Supplier first.
            </td>
        </tr>
    `);

            $('#Sale_TableBody').html(`
        <tr class="bg-white border-b border-green-500">
            <td class="py-1 px-2 text-center text-red-600 italic" colspan="6">
                No sales found! Please search Customer first.
            </td>
        </tr>
    `);
        }

        // Handle other payment checkbox
        $('.other_payment').on('change', function() {
            if ($(this).is(':checked')) {
                // Enable other payment inputs
                $('#parson_name').prop('disabled', false);
                $('#payment_amount').prop('disabled', false);
                $('.payment_type_radio').prop('disabled', false);


                // Disable part payment inputs
                disablePartPaymentInputs();

                // Uncheck part payment checkbox
                $('.part_payment').prop('checked', false);
            } else {
                // Disable other payment inputs if part payment is not checked
                if (!$('.part_payment').is(':checked')) {
                    $('#parson_name').prop('disabled', true);
                    $('#payment_amount').prop('disabled', true);
                    $('.payment_type_radio').prop('disabled', true);

                }
            }
        });

        // Handle part payment checkbox
        $('.part_payment').on('change', function() {
            if ($(this).is(':checked')) {
                // Enable part payment inputs
                enablePartPaymentInputs();

                // Disable other payment inputs
                $('#parson_name').prop('disabled', true);
                $('#payment_amount').prop('disabled', true);
                $('#parson_name').val('');
                $('#payment_amount').val('');
                $('.payment_type_radio').prop('disabled', true);


                // Uncheck other payment checkbox
                $('.other_payment').prop('checked', false);
            } else {
                // Disable part payment inputs if other payment is not checked
                if (!$('.other_payment').is(':checked')) {
                    disablePartPaymentInputs();
                }
            }
        });

        // Function to enable part payment inputs
        function enablePartPaymentInputs() {
            $('#search_partFOR_payment').prop('disabled', false);
            $('#applied_amount').prop('disabled', false);
            $('#dept_paid').prop('disabled', false);
            $('.part_type_radio').prop('disabled', false);
        }

        // Function to disable part payment inputs
        function disablePartPaymentInputs() {
            $('#search_partFOR_payment').prop('disabled', true);
            $('#applied_amount').prop('disabled', true);
            $('#dept_paid').prop('disabled', true);
            $('.part_type_radio').prop('disabled', true);

            // Clear part payment fields
            $('#search_partFOR_payment').val('');
            $('#applied_amount').val('');
            $('#part_balance').val('');
            $('#dept_paid').val('');
            $('#Part_ID_payment').val('');

            // Clear part tables
            $('#purchases_TableBody').html(`
        <tr class="bg-white border-b border-blue-500">
            <td class="py-1 px-2 text-center text-red-600 italic" colspan="6">
                Part payment disabled. Enable part payment to search for parts.
            </td>
        </tr>
    `);

            $('#Sale_TableBody').html(`
        <tr class="bg-white border-b border-green-500">
            <td class="py-1 px-2 text-center text-red-600 italic" colspan="6">
                Part payment disabled. Enable part payment to search for parts.
            </td>
        </tr>
    `);
        }

        // Initialize form state
        function initializeFormState() {
            // By default, disable all inputs
            $('#parson_name').prop('disabled', true);
            $('#payment_amount').prop('disabled', true);
            disablePartPaymentInputs();
            $('.payment_type_radio').prop('disabled', true);


            // If other payment is checked, enable those inputs
            if ($('.other_payment').is(':checked')) {
                $('#parson_name').prop('disabled', false);
                $('#payment_amount').prop('disabled', false);
                $('.payment_type_radio').prop('disabled', false);

            }

            // If part payment is checked, enable those inputs
            if ($('.part_payment').is(':checked')) {
                enablePartPaymentInputs();
            }
        }

        // Call this function when the page loads
        $(document).ready(function() {
            initializeFormState();
        });

        // Update part type selection to also set the appropriate payment type
        $(document).on('change', '.part_type_radio', function() {
            const partType = $(this).val();

            // Update the search input's data-type attribute
            $('#search_partFOR_payment').data('type', partType);
            $('#search_partFOR_payment').attr('data-type', partType);

            // Update the hidden part type field
            $('#Part_Type_payment').val(partType);

            // Set appropriate payment type based on part type
            if (partType === 'Supplier') {
                $('input[name="payment_type"][value="Payment"]').prop('checked', true);
                $('#payment_type').val('Payment');
            } else if (partType === 'Customer') {
                $('input[name="payment_type"][value="Receipt"]').prop('checked', true);
                $('#payment_type').val('Receipt');
            }


            // Clear search and results
            $('#search_partFOR_payment').val('');
            $('#Part_ID_payment').val('');
            $('#part_balance').val('');

            // Toggle visibility of transaction tables
            if (partType === 'Supplier') {
                $('#supplier_transactions_container').removeClass('hidden');
                $('#customer_transactions_container').addClass('hidden');
            } else {
                $('#supplier_transactions_container').addClass('hidden');
                $('#customer_transactions_container').removeClass('hidden');
            }

            // Clear transaction tables
            $('#purchases_TableBody').html(`
        <tr class="bg-white border-b border-blue-500">
            <td class="py-1 px-2 text-center text-red-600 italic" colspan="6">
                No purchases found! Please search ${partType} first.
            </td>
        </tr>
    `);

            $('#Sale_TableBody').html(`
        <tr class="bg-white border-b border-green-500">
            <td class="py-1 px-2 text-center text-red-600 italic" colspan="6">
                No sales found! Please search ${partType} first.
            </td>
        </tr>
    `);
        });

        // Update the part search functionality
        $(document).on('keyup', '.search_part', function() {
            var inputElement = $(this);
            var query = $.trim(inputElement.val());
            var partList = inputElement.siblings('.partlist');

            // Get the part type from the data attribute
            var partType = inputElement.data('type') || '';

            if (query !== '') {
                $.ajax({
                    type: 'POST',
                    url: '/search_part',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        query: query,
                        type: partType
                    },
                    success: function(data) {
                        if (data.trim() !== '') {
                            partList.html(data).fadeIn();
                        } else {
                            partList.fadeOut();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error searching parts:', error);
                        partList.fadeOut();
                    }
                });
            } else {
                partList.fadeOut();
            }
        });

        // Initialize the search type when the page loads
        $(document).ready(function() {
            // Set the data-type attribute based on the selected radio button
            const selectedPartType = $('input[name="part_type_radio"]:checked').val() || 'Supplier';
            $('#search_partFOR_payment').data('type', selectedPartType);
            $('#search_partFOR_payment').attr('data-type', selectedPartType);
            $('#Part_Type_payment').val(selectedPartType);

            // Ensure the correct transaction container is shown
            if (selectedPartType === 'Supplier') {
                $('#supplier_transactions_container').removeClass('hidden');
                $('#customer_transactions_container').addClass('hidden');
            } else {
                $('#supplier_transactions_container').addClass('hidden');
                $('#customer_transactions_container').removeClass('hidden');
            }
        });

        // Hide dropdown lists when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.relative').length) {
                $('.partlist').fadeOut();
            }
        });

        // Prevent input blur when clicking on dropdown lists
        $('.partlist').on('mousedown', function(e) {
            e.preventDefault();
        });

        // Setup event listeners for supplier/customer payment checkbox
        $('.supplier_payment').on('change', function() {
            handlePartPaymentSection($(this).is(':checked'));

            // Set the type based on which checkbox is checked
            const $search_part = $('#search_partFOR_payment');
            $search_part.data('type', 'Supplier');
        });

        $('.customer_payment').on('change', function() {
            handlePartPaymentSection($(this).is(':checked'));

            // Set the type based on which checkbox is checked
            const $search_part = $('#search_partFOR_payment');
            $search_part.data('type', 'Customer');
        });

        let originalBalances = {};

        // Function to restore balances to their original state
        function restoreBalances() {
            $('.balance').each(function() {
                let $balanceCell = $(this);
                let originalBalance = $balanceCell.data('original-balance');
                if (originalBalance !== undefined) {
                    $balanceCell.text(
                        originalBalance.toLocaleString('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        })
                    );
                }
            });
        }

        // Function to format number as TZS currency
        function formatAsTZS(number) {
            return number.toLocaleString('en-TZ', {
                style: 'currency',
                currency: 'TZS'
            });
        }

        // Handle applied amount changes
        $('#applied_amount').on('input', function() {
            // Restore all balances to original state first
            restoreBalances();

            let appliedAmount = parseFloat($(this).val()) || 0;
            let remainingAmount = appliedAmount;
            let totalDeducted = 0;

            // Get all checked rows and their original balances
            let checkedRows = $('.chkbox:checked').map(function() {
                let $row = $(this).closest('tr');
                let $balanceCell = $row.find('.balance');

                // Store original balance if not already stored
                if (!$balanceCell.data('original-balance')) {
                    let originalBalance = parseFloat($balanceCell.text().replace(/[^\d.-]/g, ''));
                    $balanceCell.data('original-balance', originalBalance);
                }

                return {
                    $balanceCell: $balanceCell,
                    originalBalance: $balanceCell.data('original-balance')
                };
            }).get();

            // Process each checked row
            checkedRows.forEach(function(row) {
                if (remainingAmount > 0) {
                    let amountToSubtract = Math.min(remainingAmount, row.originalBalance);
                    let newBalance = row.originalBalance - amountToSubtract;

                    row.$balanceCell.text(formatAsTZS(newBalance));

                    remainingAmount -= amountToSubtract;
                    totalDeducted += amountToSubtract;
                }
            });

            // Update the dept_paid input with the total deducted amount
            $('#dept_paid').val(totalDeducted.toFixed(2));
        });

        // Handle checkbox clicks
        $(document).on('change', '.chkbox', function() {
            $('#applied_amount').trigger('input');
        });

        // Optional: Add validation to prevent negative values
        $('#applied_amount').on('change', function() {
            let value = parseFloat($(this).val()) || 0;
            if (value < 0) {
                $(this).val(0);
            }
        });

        // Similar updates for the edit form
        $("#edit_transaction_form").validate({
            rules: {
                transaction_date: {
                    required: true
                },
                method: {
                    required: true
                },
                person_name: {
                    required: function() {
                        return $("#edit_other_paymentCHECKBOXID").is(":checked");
                    }
                },
                payment_amount: {
                    required: function() {
                        return $("#edit_other_paymentCHECKBOXID").is(":checked");
                    }
                },
                dept_paid: {
                    required: function() {
                        return $("#edit_supplierANDcustomer_paymentCHECKBOXID").is(":checked");
                    }
                }
            },
            errorPlacement: function(error, element) {
                return true;
            },
            highlight: function(element) {
                $(element).addClass("border-2 border-red-500");
            },
            unhighlight: function(element) {
                $(element).removeClass("border-2 border-red-500");
            },
            submitHandler: async function(form) {
                event.preventDefault();
                const transaction_id = $('#edit_transaction_id').val();

                try {

                    const paymentType = $('input[name="payment_type_edit"]:checked').val() || 'Payment';
                    let formData = {
                        transaction_date: $('#edit_transaction_date').val(),
                        method: $('#edit_method').val(),
                        journal_memo: $('#edit_journal_memo').val(),
                        type: paymentType, // Add the payment type
                        _token: $('input[name="_token"]').val()
                    };

                    // Check which payment type is being handled
                    const isPartPayment = $("#edit_supplierANDcustomer_paymentCHECKBOXID").is(":checked");

                    // Handle part payment (supplier or customer)
                    if (isPartPayment) {
                        const partType = $('#Part_Type_paymentEDIT').val();
                        const tableSelector = partType === 'Supplier' ? '#purchases_TableBodyEDIT' : '#Sale_TableBodyEDIT';
                        const rowClass = partType === 'Supplier' ? '.edit_purchaseID' : '.edit_saleID';

                        const transactionsData = [];
                        $(`${tableSelector} ${rowClass}`).each(function() {
                            const transactionId = $(this).data('id');
                            if (transactionId) {
                                transactionsData.push({
                                    id: transactionId,
                                    unPAIDdept: parseFloat($(this).find('.balanceEDIT').text().replace(/[^0-9.-]+/g, ''))
                                });
                            }
                        });

                        formData = {
                            ...formData,
                            part_id: $('#Part_ID_paymentEDIT').val(),
                            part_type: partType,
                            dept_paid: $('#paidEDIT').val(),
                            transactions_data: JSON.stringify(transactionsData),
                        };
                    }

                    // Handle other payment
                    if ($("#edit_other_paymentCHECKBOXID").is(":checked")) {
                        formData = {
                            ...formData,
                            person_name: $('#edit_parson_name').val(),
                            payment_amount: $('#edit_payment_amount').val()
                        };
                    }

                    const response = await $.ajax({
                        url: `/transaction_update/${transaction_id}`,
                        type: 'PUT',
                        data: formData
                    });

                    if (response.success) {
                        showFeedbackModal('success', 'Success', 'Transaction updated successfully');
                        hide_editTransactionModal();
                        loadTransactions();
                        loadPurchases();
                        loadParties();
                        loadinventory();
                        loadSale();
                        loadStatistics();
                    } else {
                        throw new Error(response.message);
                    }
                } catch (error) {
                    const errorMessage = error.responseJSON?.message || 'Failed to update transaction';
                    showFeedbackModal('error', 'Error', errorMessage);
                }
            }
        });

        // Add hidden inputs to both modals for payment type
        function addHiddenInputs() {
            if ($('#payment_type').length === 0) {
                $('form#payment_out_form').append('<input type="hidden" id="payment_type" name="type" value="Payment">');
            }

            if ($('#payment_type_edit').length === 0) {
                $('form#edit_transaction_form').append('<input type="hidden" id="payment_type_edit" name="type" value="Payment">');
            }
        }

        // Call this when document is ready
        $(document).ready(function() {
            addHiddenInputs();

            // Set default payment type
            $('input[name="Payment"][value="Payment"]').prop('checked', true);
        });

        // Handle other payment checkbox in edit modal
        $('#edit_other_paymentCHECKBOXID').on('change', function() {
            if ($(this).is(':checked')) {
                // Enable other payment inputs
                $('#edit_parson_name').prop('disabled', false);
                $('#edit_payment_amount').prop('disabled', false);

                // Disable part payment inputs
                disablePartPaymentInputsEdit();

                // Uncheck supplier/customer payment checkbox
                $('#edit_supplierANDcustomer_paymentCHECKBOXID').prop('checked', false);
            } else {
                // Disable other payment inputs if supplier/customer payment is not checked
                if (!$('#edit_supplierANDcustomer_paymentCHECKBOXID').is(':checked')) {
                    $('#edit_parson_name').prop('disabled', true);
                    $('#edit_payment_amount').prop('disabled', true);
                }
            }
        });

        // Handle supplier/customer payment checkbox in edit modal
        $('#edit_supplierANDcustomer_paymentCHECKBOXID').on('change', function() {
            if ($(this).is(':checked')) {
                // Enable part payment inputs
                enablePartPaymentInputsEdit();

                // Disable other payment inputs
                $('#edit_parson_name').prop('disabled', true);
                $('#edit_payment_amount').prop('disabled', true);
                $('#edit_parson_name').val('');
                $('#edit_payment_amount').val('');

                // Uncheck other payment checkbox
                $('#edit_other_paymentCHECKBOXID').prop('checked', false);
            } else {
                // Disable part payment inputs if other payment is not checked
                if (!$('#edit_other_paymentCHECKBOXID').is(':checked')) {
                    disablePartPaymentInputsEdit();
                }
            }
        });

        // Function to enable part payment inputs in edit modal
        function enablePartPaymentInputsEdit() {
            $('#search_partFOR_paymentEDIT').prop('disabled', false);
            $('#paidEDIT').prop('disabled', false);
            $('.part_type_radioEDIT').prop('disabled', false);
        }

        // Function to disable part payment inputs in edit modal
        function disablePartPaymentInputsEdit() {
            $('#search_partFOR_paymentEDIT').prop('disabled', true);
            $('#paidEDIT').prop('disabled', true);
            $('.part_type_radioEDIT').prop('disabled', true);

            // Clear part payment fields
            $('#search_partFOR_paymentEDIT').val('');
            $('#Part_ID_paymentEDIT').val('');
            $('#Part_Type_paymentEDIT').val('');
            $('#paidEDIT').val('');

            // Clear part tables
            $('#purchases_TableBodyEDIT').html(`
        <tr class="bg-white border-b border-blue-500">
            <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="4">
                Part payment disabled. Enable part payment to search for parts.
            </td>
        </tr>
    `);

            $('#Sale_TableBodyEDIT').html(`
        <tr class="bg-white border-b border-green-500">
            <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="4">
                Part payment disabled. Enable part payment to search for parts.
            </td>
        </tr>
    `);
        }

        // Function to reset the edit transaction form
        function resetEditTransactionForm() {
            // Reset all form fields
            $('#edit_transaction_form')[0].reset();

            // Clear input fields
            $('#edit_parson_name').val('');
            $('#edit_payment_amount').val('');
            $('#edit_journal_memo').val('');
            $('#edit_method').val('');
            $('#search_partFOR_paymentEDIT').val('');
            $('#Part_ID_paymentEDIT').val('');
            $('#Part_Type_paymentEDIT').val('');
            $('#paidEDIT').val('');

            // Uncheck checkboxes
            $('#edit_other_paymentCHECKBOXID').prop('checked', false);
            $('#edit_supplierANDcustomer_paymentCHECKBOXID').prop('checked', false);

            // Reset radio buttons
            $('input[name="payment_type_edit"][value="Payment"]').prop('checked', true);
            $('#payment_type_edit').val('Payment');
            $('.part_type_radioEDIT[value="Supplier"]').prop('checked', true);

            // Disable all input fields initially
            $('#edit_parson_name').prop('disabled', true);
            $('#edit_payment_amount').prop('disabled', true);
            $('#search_partFOR_paymentEDIT').prop('disabled', true);
            $('#paidEDIT').prop('disabled', true);
            $('.part_type_radioEDIT').prop('disabled', true);

            // Reset transaction tables
            $('#purchases_TableBodyEDIT').html(`
        <tr class="bg-white border-b border-blue-500">
            <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="4">
                No purchases found! Please search Supplier first.
            </td>
        </tr>
    `);

            $('#Sale_TableBodyEDIT').html(`
        <tr class="bg-white border-b border-green-500">
            <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="4">
                No sales found! Please search Customer first.
            </td>
        </tr>
    `);

            // Hide/show appropriate transaction containers
            $('#supplier_transactions_containerEDIT').removeClass('hidden');
            $('#customer_transactions_containerEDIT').addClass('hidden');

            // Remove any validation error highlighting
            $('.border-red-500').removeClass('border-red-500');
        }

        const filter_statistics_Inputs = [
            'from_Date',
            'to_Date',
        ];

        filter_statistics_Inputs.forEach(inputId => {
            const inputElement = document.getElementById(inputId);
            if (inputElement) {
                inputElement.addEventListener('change', function() {
                    loadStatistics();
                });

                inputElement.addEventListener('keyup', debounce(function() {
                    loadStatistics();
                }, 300));
            }
        });

        // Enhanced loadStatistics function to fetch all comprehensive statistics
        function loadStatistics() {
            const filters = {
                from_Date: document.getElementById('from_Date')?.value || '',
                to_Date: document.getElementById('to_Date')?.value || '',
            };

            $.ajax({
                type: "GET",
                url: 'getBusinessStatistics', // Using our new comprehensive endpoint
                data: filters,
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        renderComprehensiveStatistics(response.statistics);
                    }
                    $('#reports-loading').hide();
                },
                error: function(xhr, status, error) {
                    $('#reports-loading').hide();
                    showFeedbackModal('error', 'Error', `Failed to load statistics: ${error}`);
                }
            });
        }

        // Master function to render all statistics
        function renderComprehensiveStatistics(stats) {
            // Update KPI cards
            updateKpiCards(stats);

            // Render financial metrics section
            renderFinancialMetrics(stats.financial_metrics);

            // Render sales analytics
            renderSalesAnalytics(stats.sales_analytics);

            // Render inventory metrics
            renderInventoryHealth(stats.inventory_health);

            // Render customer insights
            renderCustomerInsights(stats.customer_insights);

            // Render supplier analytics
            renderSupplierPerformance(stats.supplier_performance);

            // Render product performance
            renderProductPerformance(stats.product_performance);

            // Render business KPIs
            renderBusinessKPIs(stats.business_kpis);

            // Render cash flow analysis
            renderCashFlow(stats.cash_flow);
        }

        // Update top KPI summary cards
        function updateKpiCards(stats) {
            // Set values for the top KPI cards
            $('#total_purchases').text(formatPrice(stats.purchase_analytics.total_purchase_amount || 0));
            $('#total_income').text(formatPrice(stats.financial_metrics.total_revenue || 0));
            $('#total_sales').text(stats.sales_analytics.total_sales || 0);
            $('#total_expenses').text(formatPrice(stats.financial_metrics.total_expenses || 0));
        }

        // Render financial metrics
        function renderFinancialMetrics(financialMetrics) {
            // Create financial metrics container if it doesn't exist
            if ($('#financial_metrics_container').length === 0) {
                const financialMetricsHtml = `
            <div id="financial_metrics_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                <h3 class="text-lg font-medium mb-4">Financial Overview</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div class="bg-blue-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Revenue</div>
                        <div id="fin_revenue" class="text-lg font-bold text-blue-600">0</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Gross Profit</div>
                        <div id="fin_gross_profit" class="text-xs font-bold text-green-600">0</div>
                        <div id="fin_gross_margin" class="text-xs text-gray-600">0%</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Expenses</div>
                        <div id="fin_expenses" class="text-xs font-bold text-red-600">0</div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Net Profit</div>
                        <div id="fin_net_profit" class="text-xs font-bold text-purple-600">0</div>
                        <div id="fin_net_margin" class="text-xs text-gray-600">0%</div>
                    </div>
                </div>
                <div class="h-64 mb-4">
                    <canvas id="monthly_profit_chart"></canvas>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Outstanding Receivables</div>
                        <div id="fin_receivables" class="text-xs font-bold text-gray-800">0</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Outstanding Payables</div>
                        <div id="fin_payables" class="text-xs font-bold text-gray-800">0</div>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(financialMetricsHtml);
            }

            // Update financial metrics values
            $('#fin_revenue').text(formatPrice(financialMetrics.total_revenue || 0));
            $('#fin_gross_profit').text(formatPrice(financialMetrics.gross_profit || 0));
            $('#fin_gross_margin').text(`${financialMetrics.gross_profit_margin || 0}% margin`);
            $('#fin_expenses').text(formatPrice(financialMetrics.total_expenses || 0));
            $('#fin_net_profit').text(formatPrice(financialMetrics.net_profit || 0));
            $('#fin_net_margin').text(`${financialMetrics.net_profit_margin || 0}% margin`);
            $('#fin_receivables').text(formatPrice(financialMetrics.outstanding_receivables || 0));
            $('#fin_payables').text(formatPrice(financialMetrics.outstanding_payables || 0));

            // Render monthly profit chart
            if (financialMetrics.monthly_profits && financialMetrics.monthly_profits.length > 0) {
                const ctx = document.getElementById('monthly_profit_chart').getContext('2d');

                // Prepare data for chart
                const labels = financialMetrics.monthly_profits.map(item => item.month);
                const revenueData = financialMetrics.monthly_profits.map(item => item.revenue);
                const discountData = financialMetrics.monthly_profits.map(item => item.discounts || 0);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Revenue',
                                data: revenueData,
                                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1
                            },
                            {
                                label: 'Discounts',
                                data: discountData,
                                backgroundColor: 'rgba(239, 68, 68, 0.6)',
                                borderColor: 'rgb(239, 68, 68)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Monthly Revenue & Discounts'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount (TZS)'
                                }
                            }
                        }
                    }
                });
            }
        }

        // Render sales analytics
        function renderSalesAnalytics(salesAnalytics) {
            // Create sales analytics container if it doesn't exist
            if ($('#sales_analytics_container').length === 0) {
                const salesAnalyticsHtml = `
            <div id="sales_analytics_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                <h3 class="text-lg font-medium mb-4">Sales Analytics</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="h-64 mb-4">
                            <canvas id="monthly_sales_chart"></canvas>
                        </div>
                    </div>
                    <div>
                        <div class="h-64 mb-4">
                            <canvas id="sales_by_day_chart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <h4 class="text-md font-medium mb-2">Best Selling Products</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="best_selling_products_table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-medium mb-2">Sales by Status</h4>
                        <div class="h-64">
                            <canvas id="sales_by_status_chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(salesAnalyticsHtml);
            }

            // Render monthly sales chart
            if (salesAnalytics.monthly_sales_trend && salesAnalytics.monthly_sales_trend.length > 0) {
                const ctx = document.getElementById('monthly_sales_chart').getContext('2d');

                // Prepare data for chart
                const labels = salesAnalytics.monthly_sales_trend.map(item => item.month);
                const countData = salesAnalytics.monthly_sales_trend.map(item => item.count);
                const amountData = salesAnalytics.monthly_sales_trend.map(item => item.amount);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Number of Sales',
                                data: countData,
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 2,
                                tension: 0.1,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Sales Amount',
                                data: amountData,
                                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                                borderColor: 'rgb(16, 185, 129)',
                                borderWidth: 2,
                                tension: 0.1,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Monthly Sales Trend'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Count'
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Amount (TZS)'
                                },
                                grid: {
                                    drawOnChartArea: false
                                }
                            }
                        }
                    }
                });
            }

            // Render sales by day of week chart
            if (salesAnalytics.sales_by_day_of_week && salesAnalytics.sales_by_day_of_week.length > 0) {
                const ctx = document.getElementById('sales_by_day_chart').getContext('2d');

                // Prepare data for chart
                const labels = salesAnalytics.sales_by_day_of_week.map(item => item.day);
                const countData = salesAnalytics.sales_by_day_of_week.map(item => item.count);
                const amountData = salesAnalytics.sales_by_day_of_week.map(item => item.amount);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Number of Sales',
                                data: countData,
                                backgroundColor: 'rgba(79, 70, 229, 0.6)',
                                borderColor: 'rgb(79, 70, 229)',
                                borderWidth: 1,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Sales Amount',
                                data: amountData,
                                type: 'line',
                                backgroundColor: 'rgba(245, 158, 11, 0.2)',
                                borderColor: 'rgb(245, 158, 11)',
                                borderWidth: 2,
                                tension: 0.1,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Sales by Day of Week'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Count'
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Amount (TZS)'
                                },
                                grid: {
                                    drawOnChartArea: false
                                }
                            }
                        }
                    }
                });
            }

            // Populate best selling products table
            if (salesAnalytics.best_selling_items && salesAnalytics.best_selling_items.length > 0) {
                let tableHtml = '';

                salesAnalytics.best_selling_items.forEach(item => {
                    tableHtml += `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-xs">${item.name || `Product #${item.id}`}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${item.total_quantity}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(item.total_revenue)}</td>
                </tr>
            `;
                });

                $('#best_selling_products_table').html(tableHtml);
            }

            // Render sales by status chart
            if (salesAnalytics.sales_by_status && salesAnalytics.sales_by_status.length > 0) {
                const ctx = document.getElementById('sales_by_status_chart').getContext('2d');

                // Prepare data for chart
                const labels = salesAnalytics.sales_by_status.map(item => item.status);
                const countData = salesAnalytics.sales_by_status.map(item => item.count);
                const amountData = salesAnalytics.sales_by_status.map(item => item.amount);

                // Create color array based on status
                const backgroundColors = salesAnalytics.sales_by_status.map(item => {
                    switch (item.status) {
                        case 'Paid':
                            return 'rgba(16, 185, 129, 0.6)';
                        case 'Partial paid':
                            return 'rgba(245, 158, 11, 0.6)';
                        case 'Unpaid':
                            return 'rgba(239, 68, 68, 0.6)';
                        case 'Cancelled':
                            return 'rgba(107, 114, 128, 0.6)';
                        default:
                            return 'rgba(59, 130, 246, 0.6)';
                    }
                });

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: amountData,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Sales Amount by Status'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const percentage = ((value / amountData.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
                                        return `${label}: ${formatPrice(value)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Render inventory health metrics
        function renderInventoryHealth(inventoryHealth) {
            // Create inventory health container if it doesn't exist
            if ($('#inventory_health_container').length === 0) {
                const inventoryHealthHtml = `
            <div id="inventory_health_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                <h3 class="text-lg font-medium mb-4">Inventory Health</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-blue-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Total Inventory Value</div>
                        <div id="total_inventory_value" class="text-xs font-bold text-blue-600">0</div>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Low Stock Items</div>
                        <div id="low_stock_count" class="text-xs font-bold text-yellow-600">0</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Out of Stock Items</div>
                        <div id="out_of_stock_count" class="text-xs font-bold text-red-600">0</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-md font-medium mb-2">Dead Stock Items</h4>
                        <div class="overflow-x-auto">
                            <div id="dead_stock_table" class="min-w-full">
                                <p class="text-center text-gray-500 py-4">Loading...</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-medium mb-2">Slow Moving Items</h4>
                        <div class="overflow-x-auto">
                            <div id="slow_moving_table" class="min-w-full">
                                <p class="text-center text-gray-500 py-4">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-md font-medium mb-2">Stock Turnover Rate</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sold Qty</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Inventory</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Turnover Rate</th>
                                </tr>
                            </thead>
                            <tbody id="stock_turnover_table" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(inventoryHealthHtml);
            }

            // Update inventory health metrics
            $('#total_inventory_value').text(formatPrice(inventoryHealth.total_inventory_value || 0));
            $('#low_stock_count').text(inventoryHealth.low_stock_items?.length || 0);
            $('#out_of_stock_count').text(inventoryHealth.out_of_stock_items?.length || 0);

            // Render dead stock table
            renderDeadStockTable(inventoryHealth.dead_stock);

            // Render slow moving items table
            renderSlowMovingItemsTable(inventoryHealth.slow_moving_items);

            // Populate stock turnover table
            // Fix for the stock turnover table rendering
            if (inventoryHealth.stock_turnover && inventoryHealth.stock_turnover.length > 0) {
                let tableHtml = '';

                inventoryHealth.stock_turnover.forEach(item => {
                    // Convert to numbers and handle potential null/undefined values
                    const soldQuantity = Number(item.sold_quantity || 0);
                    const avgInventory = Number(item.avg_inventory || 0);
                    const turnoverRate = Number(item.turnover_rate || 0);

                    tableHtml += `
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap text-xs">${item.name || `Product #${item.id}`}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${soldQuantity}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${avgInventory.toFixed(1)}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-right font-medium ${turnoverRate > 3 ? 'text-green-600' : (turnoverRate < 1 ? 'text-red-600' : 'text-yellow-600')}">${turnoverRate.toFixed(2)}</td>
                        </tr>
                    `;
                });

                $('#stock_turnover_table').html(tableHtml);
            } else {
                $('#stock_turnover_table').html('<tr><td colspan="4" class="px-3 py-4 text-center text-xs text-gray-500">No stock turnover data available</td></tr>');
            }
        }

        // Render customer insights
        function renderCustomerInsights(customerInsights) {
            // Create customer insights container if it doesn't exist
            if ($('#customer_insights_container').length === 0) {
                const customerInsightsHtml = `
            <div id="customer_insights_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                <h3 class="text-lg font-medium mb-4">Customer Insights</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div class="bg-blue-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Total Customers</div>
                        <div id="total_customers_count" class="text-xs font-bold text-blue-600">0</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">New Customers</div>
                        <div id="new_customers_count" class="text-xs font-bold text-green-600">0</div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Retention Rate</div>
                        <div id="retention_rate" class="text-xs font-bold text-purple-600">0%</div>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-md shadow-lg">
                        <div class="text-xs text-gray-500">Avg Sale/Customer</div>
                        <div id="avg_sale_per_customer" class="text-xs font-bold text-indigo-600">0</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-md font-medium mb-2">Top Customers</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                                    </tr>
                                </thead>
                                <tbody id="top_customers_table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-medium mb-2">Customer Debt</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unpaid Orders</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debt Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="customer_debt_table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(customerInsightsHtml);
            }

            // Update customer insights metrics
            $('#total_customers_count').text(customerInsights.total_customers || 0);
            $('#new_customers_count').text(customerInsights.new_customers || 0);
            $('#retention_rate').text(`${customerInsights.retention_rate || 0}%`);
            $('#avg_sale_per_customer').text(formatPrice(customerInsights.average_sale_per_customer || 0));

            // Populate top customers table
            if (customerInsights.top_customers && customerInsights.top_customers.length > 0) {
                let tableHtml = '';

                customerInsights.top_customers.forEach(customer => {
                    tableHtml += `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-xs">${customer.name || `Customer #${customer.id}`}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${customer.total_orders || 0}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(customer.total_spent || 0)}</td>
                </tr>
            `;
                });

                $('#top_customers_table').html(tableHtml);
            } else {
                $('#top_customers_table').html('<tr><td colspan="3" class="px-3 py-4 text-center text-xs text-gray-500">No customers data available</td></tr>');
            }

            // Populate customer debt table
            if (customerInsights.customer_debt && customerInsights.customer_debt.length > 0) {
                let tableHtml = '';

                customerInsights.customer_debt.forEach(customer => {
                    tableHtml += `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-xs">${customer.name || `Customer #${customer.id}`}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${customer.unpaid_orders || 0}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right text-red-600 font-medium">${formatPrice(customer.total_debt || 0)}</td>
                </tr>
            `;
                });

                $('#customer_debt_table').html(tableHtml);
            } else {
                $('#customer_debt_table').html('<tr><td colspan="3" class="px-3 py-4 text-center text-xs text-gray-500">No customer debt data available</td></tr>');
            }
        }

        // Render supplier performance
        function renderSupplierPerformance(supplierPerformance) {
            // Create supplier performance container if it doesn't exist
            if ($('#supplier_performance_container').length === 0) {
                const supplierPerformanceHtml = `
            <div id="supplier_performance_container" class="bg-white p-4 rounded-lg shadow-lg mb-6">
                <h3 class="text-lg font-medium mb-4">Supplier Performance</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-md font-medium mb-2">Top Suppliers</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="top_suppliers_table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-medium mb-2">Supplier Debt</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unpaid Orders</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debt Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="supplier_debt_table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-md font-medium mb-2">Average Items Per Purchase</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Count</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Items Count</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Items/Purchase</th>
                                </tr>
                            </thead>
                            <tbody id="avg_items_table" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(supplierPerformanceHtml);
            }

            // Populate top suppliers table
            if (supplierPerformance.top_suppliers && supplierPerformance.top_suppliers.length > 0) {
                let tableHtml = '';

                supplierPerformance.top_suppliers.forEach(supplier => {
                    tableHtml += `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-xs">${supplier.name || `Supplier #${supplier.id}`}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${supplier.total_orders || 0}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(supplier.total_amount || 0)}</td>
                </tr>
            `;
                });

                $('#top_suppliers_table').html(tableHtml);
            } else {
                $('#top_suppliers_table').html('<tr><td colspan="3" class="px-3 py-4 text-center text-xs text-gray-500">No suppliers data available</td></tr>');
            }

            // Populate supplier debt table
            if (supplierPerformance.supplier_debt && supplierPerformance.supplier_debt.length > 0) {
                let tableHtml = '';

                supplierPerformance.supplier_debt.forEach(supplier => {
                    tableHtml += `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-xs">${supplier.name || `Supplier #${supplier.id}`}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${supplier.unpaid_orders || 0}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right text-blue-600 font-medium">${formatPrice(supplier.total_debt || 0)}</td>
                </tr>
            `;
                });

                $('#supplier_debt_table').html(tableHtml);
            } else {
                $('#supplier_debt_table').html('<tr><td colspan="3" class="px-3 py-4 text-center text-xs text-gray-500">No supplier debt data available</td></tr>');
            }

            // Populate average items per purchase table
            // Fix for the average items per purchase table
            if (supplierPerformance.avg_items_per_purchase && supplierPerformance.avg_items_per_purchase.length > 0) {
                let tableHtml = '';

                supplierPerformance.avg_items_per_purchase.forEach(supplier => {
                    // Convert values to numbers and handle null/undefined
                    const purchaseCount = Number(supplier.purchase_count || 0);
                    const itemsCount = Number(supplier.items_count || 0);
                    const avgItems = Number(supplier.avg_items || 0);

                    tableHtml += `
            <tr>
                <td class="px-3 py-2 whitespace-nowrap text-xs">${supplier.name || `Supplier #${supplier.id}`}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${purchaseCount}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${itemsCount}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right font-medium">${avgItems.toFixed(1)}</td>
            </tr>
        `;
                });

                $('#avg_items_table').html(tableHtml);
            } else {
                $('#avg_items_table').html('<tr><td colspan="4" class="px-3 py-4 text-center text-xs text-gray-500">No supplier purchase data available</td></tr>');
            }
        }

        // Render product performance
        function renderProductPerformance(productPerformance) {
            // Create product performance container if it doesn't exist
            if ($('#product_performance_container').length === 0) {
                const productPerformanceHtml = `
            <div id="product_performance_container" class="bg-white p-4 rounded-lg shadow mb-6">
                <h3 class="text-lg font-medium mb-4">Product Performance</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-md font-medium mb-2">Highest Margin Products</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 capitalized whitespace-nowrap">Product</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 capitalized whitespace-nowrap">Sale</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 capitalized whitespace-nowrap">Cost</th>
                                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 capitalized whitespace-nowrap whitespace-nowrap">Margin %</th>
                                    </tr>
                                </thead>
                                <tbody id="highest_margin_table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-md font-medium mb-2">Discount Impact</h4>
                        <div class="h-64">
                            <canvas id="discount_impact_chart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-md font-medium mb-2">Profit Calculation</h4>
                    <div class="overflow-x-auto">
                        <div id="profit_table" class="min-w-full">
                            <p class="text-center text-gray-500 py-4">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(productPerformanceHtml);
            }

            // Populate highest margin products table
            if (productPerformance.highest_margin_items && productPerformance.highest_margin_items.length > 0) {
                let tableHtml = '';

                productPerformance.highest_margin_items.forEach(item => {
                    tableHtml += `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-xs">${item.name || `Product #${item.id}`}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(item.sale_price || 0)}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(item.avg_purchase_price || 0)}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right font-medium ${item.profit_percentage > 30 ? 'text-green-600' : (item.profit_percentage < 10 ? 'text-red-600' : 'text-yellow-600')}">${item.profit_percentage ? item.profit_percentage.toFixed(1) : 0}%</td>
                </tr>
            `;
                });

                $('#highest_margin_table').html(tableHtml);
            } else {
                $('#highest_margin_table').html('<tr><td colspan="4" class="px-3 py-4 text-center text-xs text-gray-500">No margin data available</td></tr>');
            }

            // Render discount impact chart
            if (productPerformance.discount_impact) {
                const ctx = document.getElementById('discount_impact_chart').getContext('2d');

                // Prepare data for chart
                const discountImpact = productPerformance.discount_impact;
                const data = [
                    discountImpact.with_discount || 0,
                    discountImpact.without_discount || 0
                ];

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Sales with Discount', 'Sales without Discount'],
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                'rgba(245, 158, 11, 0.6)',
                                'rgba(59, 130, 246, 0.6)'
                            ],
                            borderColor: [
                                'rgb(245, 158, 11)',
                                'rgb(59, 130, 246)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${formatPrice(value)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Render profit table
            renderProfitTable(productPerformance.highest_margin_items || []);
        }

        // Render business KPIs
        function renderBusinessKPIs(businessKPIs) {
            // Create business KPIs container if it doesn't exist
            if ($('#business_kpis_container').length === 0) {
                const businessKPIsHtml = `
            <div id="business_kpis_container" class="bg-white p-4 rounded-lg shadow mb-6">
                <h3 class="text-lg font-medium mb-4">Business Performance KPIs</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-indigo-50 p-3 rounded-md shadow">
                        <div class="text-xs text-gray-500">Sales Growth</div>
                        <div id="sales_growth" class="text-lg font-bold"></div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-md shadow">
                        <div class="text-xs text-gray-500">Profit Growth</div>
                        <div id="profit_growth" class="text-lg font-bold"></div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-md shadow">
                        <div class="text-xs text-gray-500">Customer Growth</div>
                        <div id="customer_growth" class="text-lg font-bold"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-md shadow">
                        <h4 class="text-md font-medium mb-3">Current Period</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="text-xs text-gray-500">Period</div>
                                <div id="current_period_dates" class="text-sm font-medium"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Sales</div>
                                <div id="current_period_sales" class="text-sm font-medium"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Profit</div>
                                <div id="current_period_profit" class="text-sm font-medium"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Customers</div>
                                <div id="current_period_customers" class="text-sm font-medium"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md shadow">
                        <h4 class="text-md font-medium mb-3">Previous Period</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="text-xs text-gray-500">Period</div>
                                <div id="previous_period_dates" class="text-sm font-medium"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Sales</div>
                                <div id="previous_period_sales" class="text-sm font-medium"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Profit</div>
                                <div id="previous_period_profit" class="text-sm font-medium"></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Customers</div>
                                <div id="previous_period_customers" class="text-sm font-medium"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(businessKPIsHtml);
            }

            // Update business KPIs

            // Growth indicators
            const salesGrowth = businessKPIs.sales_growth_rate || 0;
            const profitGrowth = businessKPIs.profit_growth_rate || 0;
            const customerGrowth = businessKPIs.customer_growth_rate || 0;

            $('#sales_growth').text(`${salesGrowth > 0 ? '+' : ''}${salesGrowth}%`);
            $('#sales_growth').addClass(salesGrowth >= 0 ? 'text-green-600' : 'text-red-600');

            $('#profit_growth').text(`${profitGrowth > 0 ? '+' : ''}${profitGrowth}%`);
            $('#profit_growth').addClass(profitGrowth >= 0 ? 'text-green-600' : 'text-red-600');

            $('#customer_growth').text(`${customerGrowth > 0 ? '+' : ''}${customerGrowth}%`);
            $('#customer_growth').addClass(customerGrowth >= 0 ? 'text-green-600' : 'text-red-600');

            // Current period
            $('#current_period_dates').text(`${businessKPIs.current_period?.from || ''} to ${businessKPIs.current_period?.to || ''}`);
            $('#current_period_sales').text(formatPrice(businessKPIs.current_period?.sales || 0));
            $('#current_period_profit').text(formatPrice(businessKPIs.current_period?.profit || 0));
            $('#current_period_customers').text(businessKPIs.current_period?.customer_count || 0);

            // Previous period
            $('#previous_period_dates').text(`${businessKPIs.previous_period?.from || ''} to ${businessKPIs.previous_period?.to || ''}`);
            $('#previous_period_sales').text(formatPrice(businessKPIs.previous_period?.sales || 0));
            $('#previous_period_profit').text(formatPrice(businessKPIs.previous_period?.profit || 0));
            $('#previous_period_customers').text(businessKPIs.previous_period?.customer_count || 0);
        }

        // Render cash flow analysis
        function renderCashFlow(cashFlow) {
            // Create cash flow container if it doesn't exist
            if ($('#cash_flow_container').length === 0) {
                const cashFlowHtml = `
            <div id="cash_flow_container" class="bg-white p-4 rounded-lg shadow mb-6">
                <h3 class="text-lg font-medium mb-4">Cash Flow Analysis</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-green-50 p-3 rounded-md shadow">
                        <div class="text-xs text-gray-500">Total Inflow</div>
                        <div id="total_inflow" class="text-lg font-bold text-green-600">0</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-md shadow">
                        <div class="text-xs text-gray-500">Total Outflow</div>
                        <div id="total_outflow" class="text-lg font-bold text-red-600">0</div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-md shadow">
                        <div class="text-xs text-gray-500">Net Cash Flow</div>
                        <div id="net_cash_flow" class="text-lg font-bold">0</div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="text-md font-medium mb-2">Monthly Cash Flow</h4>
                    <div class="h-64">
                        <canvas id="monthly_cash_flow_chart"></canvas>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-md font-medium mb-2">Cash Flow by Payment Method</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Inflow</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Outflow</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net</th>
                                </tr>
                            </thead>
                            <tbody id="payment_methods_table" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
                $('#stats_container').append(cashFlowHtml);
            }

            // Update cash flow values
            $('#total_inflow').text(formatPrice(cashFlow.totals?.inflow || 0));
            $('#total_outflow').text(formatPrice(cashFlow.totals?.outflow || 0));

            const netCashFlow = (cashFlow.totals?.net || 0);
            $('#net_cash_flow').text(formatPrice(netCashFlow));
            $('#net_cash_flow').addClass(netCashFlow >= 0 ? 'text-blue-600' : 'text-red-600');

            // Render monthly cash flow chart
            if (cashFlow.monthly_cash_flow && cashFlow.monthly_cash_flow.length > 0) {
                const ctx = document.getElementById('monthly_cash_flow_chart').getContext('2d');

                // Prepare data for chart
                const labels = cashFlow.monthly_cash_flow.map(item => item.month);
                const inflowData = cashFlow.monthly_cash_flow.map(item => item.inflow);
                const outflowData = cashFlow.monthly_cash_flow.map(item => item.outflow);
                const netData = cashFlow.monthly_cash_flow.map(item => item.net);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Inflow',
                                data: inflowData,
                                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                                borderColor: 'rgb(16, 185, 129)',
                                borderWidth: 1
                            },
                            {
                                label: 'Outflow',
                                data: outflowData.map(val => -val), // Negative to show below axis
                                backgroundColor: 'rgba(239, 68, 68, 0.6)',
                                borderColor: 'rgb(239, 68, 68)',
                                borderWidth: 1
                            },
                            {
                                label: 'Net Flow',
                                data: netData,
                                type: 'line',
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 2,
                                tension: 0.1,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount (TZS)'
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        let value = context.parsed.y;

                                        // For outflow, show positive values in the tooltip
                                        if (label === 'Outflow') {
                                            value = Math.abs(value);
                                        }

                                        return `${label}: ${formatPrice(value)}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Populate payment methods table
            if (cashFlow.payment_methods && cashFlow.payment_methods.length > 0) {
                let tableHtml = '';

                cashFlow.payment_methods.forEach(method => {
                    tableHtml += `
                <tr>
                    <td class="px-3 py-2 whitespace-nowrap text-xs">${method.method || 'Unknown'}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right text-green-600">${formatPrice(method.inflow || 0)}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right text-red-600">${formatPrice(method.outflow || 0)}</td>
                    <td class="px-3 py-2 whitespace-nowrap text-xs text-right font-medium ${method.net >= 0 ? 'text-blue-600' : 'text-red-600'}">${formatPrice(method.net || 0)}</td>
                </tr>
            `;
                });

                $('#payment_methods_table').html(tableHtml);
            } else {
                $('#payment_methods_table').html('<tr><td colspan="4" class="px-3 py-4 text-center text-xs text-gray-500">No payment method data available</td></tr>');
            }
        }

        // Helper function to format prices with currency
        function formatPrice(price) {
            return new Intl.NumberFormat('sw-TZ', {
                style: 'currency',
                currency: 'TZS'
            }).format(price);
        }

        // Updated versions of the existing render functions to work within the new framework

        // Render dead stock table
        function renderDeadStockTable(items) {
            if (!items || items.length === 0) {
                $('#dead_stock_table').html('<p class="text-center text-gray-500 py-4">No dead stock items found</p>');
                return;
            }

            let html = `
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
    `;

            items.forEach(item => {
                html += `
            <tr>
                <td class="px-3 py-2 whitespace-nowrap text-xs">${item.name}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs">${item.sku || 'N/A'}</td>
            </tr>
        `;
            });

            html += `
            </tbody>
        </table>
    `;

            $('#dead_stock_table').html(html);
        }

        // Render slow moving items table
        function renderSlowMovingItemsTable(items) {
            if (!items || items.length === 0) {
                $('#slow_moving_table').html('<p class="text-center text-gray-500 py-4">No slow-moving items found</p>');
                return;
            }

            let html = `
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold (30 days)</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
    `;

            items.forEach(item => {
                html += `
            <tr>
                <td class="px-3 py-2 whitespace-nowrap text-xs">${item.name}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${item.total_sold}</td>
            </tr>
        `;
            });

            html += `
            </tbody>
        </table>
    `;

            $('#slow_moving_table').html(html);
        }

        // Render profit table
        function renderProfitTable(items) {
            if (!items || items.length === 0) {
                $('#profit_table').html('<p class="text-center text-gray-500 py-4">No profit data available</p>');
                return;
            }

            let html = `
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sale</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Margin %</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
    `;

            items.forEach(item => {
                const profitMargin = item.profit_percentage || 0;
                const profit = (item.sale_price || 0) - (item.avg_purchase_price || 0);

                html += `
            <tr>
                <td class="px-3 py-2 whitespace-nowrap text-xs">${item.name}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(item.sale_price || 0)}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(item.avg_purchase_price || 0)}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right">${formatPrice(profit)}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-right ${profitMargin < 0 ? 'text-red-500' : 'text-green-500'}">${profitMargin.toFixed(2)}%</td>
            </tr>
        `;
            });

            html += `
            </tbody>
        </table>
    `;

            $('#profit_table').html(html);
        }

        // Initialize the functions when DOM is ready
        $(document).ready(function() {
            // Set default dates if none are provided
            if (!$('#from_Date').val()) {
                const sixMonthsAgo = new Date();
                sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6);
                $('#from_Date').val(sixMonthsAgo.toISOString().split('T')[0]);
            }

            if (!$('#to_Date').val()) {
                const today = new Date();
                $('#to_Date').val(today.toISOString().split('T')[0]);
            }

            // Load statistics when tab is selected
            $('#reports-tab').on('click', function() {
                loadStatistics();
            });

            // Auto-load statistics if the reports tab is visible
            if ($('#reports').is(':visible')) {
                loadStatistics();
            }
        });

        // Function to populate the edit form with employee data
        function edit_employee(employeeId) {
            // Show the modal
            let dialog = document.getElementById('edit_employee_Modal');
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');

            // Set the form action
            document.getElementById('editEmployeeForm').action = `/update_employee/${employeeId}`;

            // Set the employee ID in the hidden field
            document.getElementById('edit_employee_id').value = employeeId;

            // Fetch the employee data
            fetch(`/get_employee/${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    // Check if employee data exists
                    if (!data.user) {
                        showFeedbackModal('error', 'Error', 'Failed to load employee data.');
                        return;
                    }

                    const employee = data.user;

                    // Populate the form fields - using the correct IDs from your HTML
                    document.getElementById('edit_user_name').value = employee.name || '';
                    document.getElementById('edit_user_email').value = employee.email || '';

                    // Format phone number (remove 255 prefix if present)
                    let phoneNumber = employee.phone || '';
                    if (phoneNumber && phoneNumber.startsWith('255')) {
                        phoneNumber = phoneNumber.substring(3);
                    } else {
                        phoneNumber = '';
                    }
                    document.getElementById('edit_phone').value = phoneNumber;

                    // Set role
                    document.getElementById('edit_role').value = employee.role || '';

                    // Set gender
                    if (employee.gender === 'Male') {
                        document.getElementById('edit_gender_male').checked = true;
                    } else if (employee.gender === 'Female') {
                        document.getElementById('edit_gender_female').checked = true;
                    } else {
                        // Clear gender selection if none set
                        document.getElementById('edit_gender_male').checked = false;
                        document.getElementById('edit_gender_female').checked = false;
                    }

                    // Set address
                    document.getElementById('edit_user_address').value = employee.address || '';
                })
                .catch(error => {
                    console.error('Error fetching employee data:', error);
                    showFeedbackModal('error', 'Error', 'Failed to load employee data. Please try again.');
                });
        }

        // Form submission handlers
        document.addEventListener('DOMContentLoaded', function() {
            // Add Employee Form
            const addEmployeeForm = document.getElementById('addEmployeeForm');
            if (addEmployeeForm) {
                addEmployeeForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(addEmployeeForm);

                    fetch('/add_employee', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Close the modal first
                                hide_add_employee_Dialog();

                                // Show success message with feedback modal
                                showFeedbackModal(
                                    'success',
                                    'Employee Added',
                                    'New employee has been added successfully! Default password is 12345678'
                                );

                                // Refresh the employee list without full page reload
                                loadEmployees();

                                // Clear form for next use
                                addEmployeeForm.reset();
                            } else {
                                // Show error message with feedback modal
                                showFeedbackModal(
                                    'error',
                                    'Error Adding Employee',
                                    data.message || 'Failed to add employee. Please try again.'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error adding employee:', error);
                            showFeedbackModal('error', 'Server Error', 'An unexpected error occurred. Please try again.');
                        });
                });
            }

            // Edit Employee Form
            const editEmployeeForm = document.getElementById('editEmployeeForm');
            if (editEmployeeForm) {
                editEmployeeForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(editEmployeeForm);
                    const employeeId = document.getElementById('edit_employee_id').value;

                    // Log the form data for debugging
                    console.log("Updating employee", employeeId);
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }

                    fetch(`/update_employee/${employeeId}`, {
                            method: 'POST', // Changed from PUT to POST to work with Laravel's form handling
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Close the modal
                                hide_edit_employee_Dialog();

                                // Show success message with feedback modal
                                showFeedbackModal(
                                    'success',
                                    'Employee Updated',
                                    'Employee information has been updated successfully!'
                                );

                                // Refresh the employee list without full page reload
                                loadEmployees();
                            } else {
                                // Show error message with feedback modal
                                showFeedbackModal(
                                    'error',
                                    'Error Updating Employee',
                                    data.message || 'Failed to update employee. Please try again.'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error updating employee:', error);
                            showFeedbackModal('error', 'Server Error', 'An unexpected error occurred. Please try again.');
                        });
                });
            }
        });

        $(document).on('click', '.delete_employee_btn', function(e) {
            e.preventDefault();
            var employee_id = $('#delete_employee_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/delete_employee/" + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    // Disable delete button and show loading state
                    $('.delete_employee_btn').prop('disabled', true)
                        .html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...')
                        .addClass('opacity-75');
                },
                success: function(response) {
                    hide_delete_employeeDialog();

                    // Check if we have a success response
                    if (response && response.status === 'success') {
                        showFeedbackModal(
                            'success',
                            'Employee Deleted',
                            response.message || 'Employee has been deleted successfully!'
                        );

                        // Refresh the employee list
                        loadEmployees();
                    } else {
                        // Handle unexpected success response format
                        showFeedbackModal(
                            'warning',
                            'Operation Completed',
                            'The operation completed but with an unexpected response. Please verify the changes.'
                        );

                        // Refresh anyway to be safe
                        loadEmployees();
                    }
                },
                error: function(xhr, status, error) {
                    // Get response data
                    let errorMessage = 'There was an error deleting the employee. Please try again.';
                    let errorTitle = 'Deletion Failed';

                    // Try to extract detailed error from response
                    try {
                        const response = xhr.responseJSON;

                        if (response) {
                            // Use the error message from response if available
                            errorMessage = response.message || errorMessage;

                            // Set different titles based on error type
                            if (xhr.status === 403) {
                                errorTitle = 'Permission Denied';
                                errorMessage = 'You do not have permission to delete this employee.';
                            } else if (xhr.status === 404) {
                                errorTitle = 'Employee Not Found';
                                errorMessage = 'The employee you are trying to delete could not be found.';
                            } else if (xhr.status === 422) {
                                errorTitle = 'Validation Error';
                            } else if (xhr.status >= 500) {
                                errorTitle = 'Server Error';
                                errorMessage = 'A server error occurred. Please try again later.';
                            }
                        }
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }

                    // Show the error feedback
                    showFeedbackModal('error', errorTitle, errorMessage);

                    // Hide the modal
                    hide_delete_employeeDialog();
                },
                complete: function() {
                    // Reset button state regardless of success/failure
                    $('.delete_employee_btn').prop('disabled', false)
                        .html('Delete employee')
                        .removeClass('opacity-75');
                }
            });
        });

        function delete_employee(employeeId, employeeName) {
            // Set the item ID to delete
            $('#delete_employee_id').val(employeeId);
            let firstName = employeeName.replace(/[^a-zA-Z\s]/g, '').split(' ')[0];
            $('#delete_employee_name').text(firstName);

            // Show the modal
            const modal = document.getElementById('delete_employeeModal');
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
                    hide_delete_employeeDialog();
                }
            });

            // Add escape key listener
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    hide_delete_employeeDialog();
                }
            });
        }

        function hide_delete_employeeDialog() {
            const modal = document.getElementById('delete_employeeModal');
            const modalContent = modal.querySelector('.relative');

            // Add exit animation
            modalContent.classList.remove('animate-modal-in');
            modalContent.classList.add('animate-modal-out');

            // Wait for animation to complete before hiding
            setTimeout(function() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modalContent.classList.remove('animate-modal-out');
                document.body.classList.remove('overflow-hidden');

                // Remove event listeners
                modal.removeEventListener('click', hide_delete_employeeDialog);
                document.removeEventListener('keydown', hide_delete_employeeDialog);
            }, 200);
        }


        function update_SelectedCount() {
            const selectedCount = $('.item_checkbox:checked').length; // Count checked checkboxes
            $('#selected_items_count').text(`${selectedCount}`); // Update the text
        }

        // Handle select all checkbox
        $('#select_all_items').on('change', function() {
            $('.item_checkbox').prop('checked', this.checked);
            toggle_stock_btn();
            grab_items_ids();
            update_SelectedCount();
        });

        // Handle individual items checkbox change
        $(document).on('change', '.item_checkbox', function() {
            let allChecked = $('.item_checkbox').length === $('.item_checkbox:checked').length;
            $('#select_all_items').prop('checked', allChecked);
            toggle_stock_btn();
            grab_items_ids();
            update_SelectedCount();
        });


        // Show/Hide stock button
        function toggle_stock_btn() {
            let anyChecked = $('.item_checkbox:checked').length > 0 || $('#select_all_items').prop('checked');
            if (anyChecked) {
                $('#stock_btn').removeClass('hidden');
            } else {
                $('#stock_btn').addClass('hidden');
            }
        }

        // Grab all selected items IDs
        function grab_items_ids() {
            let selectedIds = [];
            $('.item_checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            $('#selected_items').val(selectedIds.join(','));
        }

        function hide_stock_Modal() {
            let dialog = document.getElementById('stock_Modal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        // Initialize dates to current date on modal open
        function initializeDates() {
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0]; // YYYY-MM-DD format
            document.getElementById('to_date').value = formattedDate;

            // Set from_date to 30 days before by default
            const fromDate = new Date();
            fromDate.setDate(today.getDate() - 30);
            document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
        }

        // Set period based dropdown selection
        function setPeriodDates(period) {
            const today = new Date();
            let fromDate = new Date();

            switch (period) {
                case 'today':
                    // From and To both set to today
                    document.getElementById('from_date').value = today.toISOString().split('T')[0];
                    document.getElementById('to_date').value = today.toISOString().split('T')[0];
                    break;
                case 'this_week':
                    // Start of current week (Sunday)
                    fromDate.setDate(today.getDate() - today.getDay());
                    document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('to_date').value = today.toISOString().split('T')[0];
                    break;
                case 'last_week':
                    // Start of last week (Sunday)
                    fromDate.setDate(today.getDate() - today.getDay() - 7);
                    let toLastWeek = new Date(fromDate);
                    toLastWeek.setDate(fromDate.getDate() + 6);
                    document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('to_date').value = toLastWeek.toISOString().split('T')[0];
                    break;
                case 'this_month':
                    // First day of current month
                    fromDate.setDate(1);
                    document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('to_date').value = today.toISOString().split('T')[0];
                    break;
                case 'last_month':
                    // First day of last month
                    fromDate.setDate(1);
                    fromDate.setMonth(today.getMonth() - 1);
                    let toLastMonth = new Date(fromDate);
                    toLastMonth.setMonth(fromDate.getMonth() + 1);
                    toLastMonth.setDate(0); // Last day of the previous month
                    document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('to_date').value = toLastMonth.toISOString().split('T')[0];
                    break;
                case 'this_year':
                    // First day of current year
                    fromDate.setMonth(0);
                    fromDate.setDate(1);
                    document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('to_date').value = today.toISOString().split('T')[0];
                    break;
                case 'last_year':
                    // First day of last year
                    fromDate.setFullYear(today.getFullYear() - 1);
                    fromDate.setMonth(0);
                    fromDate.setDate(1);
                    let toLastYear = new Date(fromDate);
                    toLastYear.setMonth(11);
                    toLastYear.setDate(31);
                    document.getElementById('from_date').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('to_date').value = toLastYear.toISOString().split('T')[0];
                    break;
                default:
                    // Don't change dates
                    break;
            }
        }

        // Load stock data with applied filters
        function loadStockData() {
            const selectedIds = document.getElementById('selected_items').value;
            const statusFilter = document.getElementById('modal_status_filter').value;
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;
            const minQuantity = document.getElementById('modal_min_quantity').value;
            const maxQuantity = document.getElementById('modal_max_quantity').value;
            const searchTerm = document.getElementById('modal_search').value;
            const priceCalculation = document.getElementById('price_calculation').value;

            // Show loading state
            document.getElementById('stock_table_body').innerHTML = `
                <tr class="text-center">
                    <td colspan="7" class="px-6 py-4">
                        <div class="flex justify-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-800"></div>
                        </div>
                    </td>
                </tr>
            `;

            // Make an AJAX call to the backend
            $.ajax({
                url: "/items/stock",
                type: "GET",
                data: {
                    item_ids: selectedIds,
                    status: statusFilter,
                    from_date: fromDate,
                    to_date: toDate,
                    min_quantity: minQuantity,
                    max_quantity: maxQuantity,
                    search: searchTerm
                },
                success: function(response) {
                    renderStockData(response.items, priceCalculation);
                    updateStockStats(response.stats, priceCalculation);
                    updateLastUpdated();
                },
                error: function(xhr) {
                    document.getElementById('stock_table_body').innerHTML = `
                <tr class="text-center">
                    <td colspan="7" class="px-6 py-4 text-sm text-red-500">
                        Error loading stock data. Please try again.
                    </td>
                </tr>
            `;
                    console.error("Error loading stock data:", xhr.responseText);
                }
            });
        }

        // Render stock data to the table
        function renderStockData(data, priceCalculation) {
            const tableBody = document.getElementById('stock_table_body');

            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr class="text-center">
                        <td colspan="7" class="px-6 py-4 text-sm text-gray-500">No stock data found with the applied filters.</td>
                    </tr>
                `;
                return;
            }

            let html = '';
            data.forEach(function(item, key) {
                const price = priceCalculation === 'latest' ? item.latest_price : item.oldest_price;
                const stockValue = priceCalculation === 'latest' ? item.latest_value : item.oldest_value;

                // Determine status class for color-coding
                let statusClass = 'bg-gray-100 text-gray-800';
                if (item.status === 'Available') {
                    statusClass = 'bg-green-100 text-green-800';
                } else if (item.status === 'Not Available') {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                } else if (item.status === 'Sold Out') {
                    statusClass = 'bg-red-100 text-red-800';
                } else if (item.status === 'Damage') {
                    statusClass = 'bg-red-100 text-red-800';
                } else if (item.status === 'Expired') {
                    statusClass = 'bg-purple-100 text-purple-800';
                } else if (item.status === 'Inactive') {
                    statusClass = 'bg-gray-100 text-gray-800';
                } else if (item.status === 'Active') {
                    statusClass = 'bg-blue-100 text-blue-800';
                }

                html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${key + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.sku}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${item.current_stock}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${formatCurrency(item.latest_price)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${formatCurrency(item.oldest_price)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${formatCurrency(stockValue)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${item.status}
                            </span>
                        </td>
                    </tr>
                `;
            });

            tableBody.innerHTML = html;
        }

        // Update stock statistics
        function updateStockStats(stats, priceCalculation) {
            // Update stats in the UI
            document.getElementById('modal_total_items').textContent = stats.total_items;
            document.getElementById('modal_total_stock').textContent = stats.total_stock;
            document.getElementById('modal_stock_value_latest').textContent = formatCurrency(stats.total_latest_value);
            document.getElementById('modal_stock_value_oldest').textContent = formatCurrency(stats.total_oldest_value);
            document.getElementById('modal_avg_price').textContent = formatCurrency(
                priceCalculation === 'latest' ? stats.avg_latest_price : stats.avg_oldest_price
            );
            document.getElementById('modal_selected_count').textContent = stats.total_items;
        }

        // Update the last updated timestamp
        function updateLastUpdated() {
            const now = new Date();
            const formattedTime = now.toLocaleTimeString();
            document.getElementById('last_updated').textContent = formattedTime;
        }

        // Print stock report function
        function printStockReport() {
            const priceCalculation = document.getElementById('price_calculation').value;
            const stockTableBody = document.getElementById('stock_table_body');
            // Get the print frame
            const printFrame = document.getElementById('printFrame_for_items_stocks');

            // Set date and time for print
            const now = new Date();
            const formattedDate = now.toLocaleDateString();
            const formattedTime = now.toLocaleTimeString();

            // Get stats for print
            const totalItems = document.getElementById('modal_total_items').textContent;
            const totalStock = document.getElementById('modal_total_stock').textContent;
            const stockValue = priceCalculation === 'latest' ?
                document.getElementById('modal_stock_value_latest').textContent :
                document.getElementById('modal_stock_value_oldest').textContent;
            const avgPrice = document.getElementById('modal_avg_price').textContent;

            // Prepare table content for printing
            let tableHtml = '';
            const rows = stockTableBody.getElementsByTagName('tr');

            if (rows.length === 0 || (rows.length === 1 && rows[0].cells.length === 1)) {
                tableHtml = `<tr><td colspan="7" style="border: 1px solid #000; padding: 3px; text-align: center; font-size: 10px;">No stock data available</td></tr>`;
            } else {
                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].cells;
                    // Skip rows that are just messages (like "No data found")
                    if (cells.length < 7) continue;

                    // Get cell values, including the S/N from the first column
                    const sn = cells[0].textContent;
                    const sku = cells[1].textContent;
                    const product = cells[2].textContent;
                    const stock = cells[3].textContent;
                    const latestPrice = cells[4].textContent;
                    const oldestPrice = cells[5].textContent;
                    const price = priceCalculation === 'latest' ? latestPrice : oldestPrice;
                    const value = cells[6].textContent;
                    const status = cells[7].querySelector('span').textContent.trim();

                    tableHtml += `
                <tr>
                    <td style="border: 1px solid #000; padding: 3px; font-size: 10px;">${sn}</td>
                    <td style="border: 1px solid #000; padding: 3px; font-size: 10px;">${sku}</td>
                    <td style="border: 1px solid #000; padding: 3px; font-size: 10px;">${product}</td>
                    <td style="border: 1px solid #000; padding: 3px; text-align: right; font-size: 10px;">${stock}</td>
                    <td style="border: 1px solid #000; padding: 3px; text-align: right; font-size: 10px;">${price}</td>
                    <td style="border: 1px solid #000; padding: 3px; text-align: right; font-size: 10px;">${value}</td>
                    <td style="border: 1px solid #000; padding: 3px; text-align: center; font-size: 10px;">${status}</td>
                </tr>
            `;
                }
            }

            // Create print content with inline styles
            const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Stock Report</title>
            <style>
                @page { 
                    margin: 0.5cm; 
                }
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 10px;
                    font-size: 11px;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 10px; 
                }
                th { 
                    border: 1px solid #000; 
                    padding: 3px; 
                    background-color: #f2f2f2; 
                    text-align: left;
                    font-size: 10px;
                    font-weight: bold;
                }
                td { 
                    border: 1px solid #000; 
                    padding: 3px; 
                    font-size: 10px;
                }
                .print-header { 
                    margin-bottom: 10px; 
                    text-align: center;
                }
                .print-summary { 
                    margin-top: 10px; 
                    font-weight: bold;
                    font-size: 10px;
                }
                h1 { 
                    text-align: center; 
                    margin: 5px 0;
                    font-size: 14px;
                }
                .flex-space-between {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 5px;
                    font-size: 10px;
                }
                .report-info {
                    font-size: 10px;
                    color: #444;
                    text-align: center;
                    margin-bottom: 5px;
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h1>STOCK REPORT</h1>
                <div class="report-info">Generated on: ${formattedDate} ${formattedTime}</div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>SKU</th>
                        <th>PRODUCT</th>
                        <th style="text-align: right;">QTY</th>
                        <th style="text-align: right;">PRICE (TZS)</th>
                        <th style="text-align: right;">VALUE (TZS)</th>
                        <th style="text-align: center;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableHtml}
                </tbody>
            </table>
            
            <div class="print-summary">
                <div class="flex-space-between">
                    <div>Total Items: ${totalItems}</div>
                    <div>Total Stock: ${totalStock}</div>
                    <div>Total Value: ${stockValue}</div>
                    <div>Avg. Price: ${avgPrice}</div>
                </div>
            </div>
        </body>
        </html>
    `;

            // Write to the iframe
            const frameDoc = printFrame.contentDocument || printFrame.contentWindow.document;
            frameDoc.open();
            frameDoc.write(printContent);
            frameDoc.close();

            // Trigger print after a small delay to ensure content is loaded
            setTimeout(function() {
                try {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                } catch (e) {
                    console.error("Printing failed:", e);
                    alert("Printing failed. Please try again.");
                }
            }, 500);
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // When stock button is clicked
            $('#stock_btn').on('click', function() {
                // Update modal selected count
                const selectedCount = $('.item_checkbox:checked').length;
                $('#modal_selected_count').text(selectedCount);

                // Initialize dates
                initializeDates();

                // Load stock data
                loadStockData();
            });

            // Period filter change
            $('#period_filter').on('change', function() {
                const period = $(this).val();
                if (period) {
                    setPeriodDates(period);
                }
            });

            // Apply filters button
            $('#apply_filters').on('click', function() {
                loadStockData();
            });

            // Reset filters button
            $('#reset_filters').on('click', function() {
                $('#modal_status_filter').val('');
                $('#modal_min_quantity').val('');
                $('#modal_max_quantity').val('');
                $('#modal_search').val('');
                $('#price_calculation').val('latest');

                // Reset period to default (30 days)
                $('#period_filter').val('');
                initializeDates();

                // Reload data
                loadStockData();
            });

            // Print report button
            $('#print_stock_report').on('click', function() {
                printStockReport();
            });

            // Search input keyup
            $('#modal_search').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    loadStockData();
                }
            });

            // Date inputs change
            $('#from_date, #to_date').on('change', function() {
                // When manually changing dates, reset the period dropdown
                $('#period_filter').val('');
            });
        });


        // Enhanced show modal function with animation
        function show_stock_Modal() {
            const modal = document.getElementById('stock_Modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Ensure we have latest selected items
            grab_items_ids();

            // Update the selected count
            const selectedCount = $('.item_checkbox:checked').length;
            $('#modal_selected_count').text(selectedCount);

            // Initialize dates and load data
            initializeDates();
            loadStockData();
        }

        // Make sure the stock button uses the enhanced show modal function
        $(document).ready(function() {
            $('#stock_btn').off('click').on('click', function() {
                show_stock_Modal();
                grab_items_ids(); // Ensure all selected IDs are grabbed
            });
        });

        function printGenericTable(tableId, title, subtitle, columnConfig, filterInfo) {
            // Add current date and time for the report
            const now = new Date();
            const dateString = now.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Create a clone of the table to manipulate for printing
            const originalContent = document.getElementById(tableId);
            const clonedContent = originalContent.cloneNode(true);

            // Remove all elements with no_print class
            const noPrintElements = clonedContent.querySelectorAll('.no_print');
            noPrintElements.forEach(element => {
                element.remove();
            });

            // Get print frame
            const printFrame = document.getElementById('printFrame');
            if (!printFrame) {
                // Create the iframe if it doesn't exist
                const iframe = document.createElement('iframe');
                iframe.id = 'printFrame';
                iframe.style.position = 'absolute';
                iframe.style.top = '-9999px';
                iframe.style.left = '-9999px';
                document.body.appendChild(iframe);
            }

            const frameDoc = printFrame.contentDocument || printFrame.contentWindow.document;

            // Prepare filter information string
            let filterString = '';
            if (filterInfo) {
                const filters = [];
                for (const key in filterInfo) {
                    if (filterInfo[key]) {
                        filters.push(`${key}: ${filterInfo[key]}`);
                    }
                }
                filterString = filters.join(' | ');
            }

            // Generate column style definitions
            let columnStyles = '';
            for (const col in columnConfig.widths) {
                columnStyles += `.${col} { width: ${columnConfig.widths[col]}; }\n`;
            }

            frameDoc.open();
            frameDoc.write(`
        <html>
        <head>
            <title>${title}</title>
            <style>
                @page {
                    margin: 0.5cm;
                }
                @media print {
                    * {
                        box-sizing: border-box;
                    }
                    html, body {
                        margin: 0;
                        padding: 0;
                        width: 100%;
                        font-size: 9pt;
                        font-family: Arial, sans-serif;
                    }
                    .container {
                        width: 100%;
                        max-width: 100%;
                        padding: 5px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        table-layout: fixed;
                    }
                    th, td {
                        border: 0.5px solid #000;
                        padding: 2px 3px;
                        font-size: 8pt;
                        overflow: hidden;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }
                    .report-header {
                        text-align: center;
                        margin-bottom: 5px;
                    }
                    .report-header h2 {
                        margin: 2px 0;
                        font-size: 12pt;
                    }
                    .report-meta {
                        font-size: 8pt;
                        margin-bottom: 3px;
                        text-align: left;
                    }
                    .report-date {
                        text-align: right;
                        font-size: 8pt;
                        margin-bottom: 5px;
                    }
                    /* Status colors */
                    .status-paid { background-color: #d1fae5; color: #065f46; }
                    .status-unpaid { background-color: #fee2e2; color: #b91c1c; }
                    .status-partial { background-color: #fef3c7; color: #92400e; }
                    .status-available { background-color: #d1fae5; color: #065f46; }
                    .status-not-available { background-color: #fef3c7; color: #92400e; }
                    .status-sold-out { background-color: #fee2e2; color: #b91c1c; }
                    
                    /* Column widths */
                    ${columnStyles}
                    
                    /* Remove any remnant scrollbars */
                    ::-webkit-scrollbar {
                        display: none;
                    }
                    * {
                        overflow: visible !important;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="report-header">
                    <h2 style="font-weight: bold;">${title}</h2>
                    <h2>${subtitle}</h2>
                </div>
                
                <div class="report-date">
                    Generated: ${dateString}
                </div>
                
                ${filterString ? `<div class="report-meta">
                    <strong>Filters:</strong> ${filterString}
                </div>` : ''}
            </div>
    `);

            // Create the table header
            frameDoc.write(`
        <table>
            <thead>
                <tr>
    `);

            // Add header columns
            for (const col of columnConfig.columns) {
                frameDoc.write(`<th class="${col.class}">${col.label}</th>`);
            }

            frameDoc.write(`
                </tr>
            </thead>
            <tbody>
    `);

            // Get all table rows and create optimized ones for print
            const tableRows = clonedContent.querySelectorAll('tbody tr');

            if (tableRows.length === 0) {
                frameDoc.write(`
            <tr>
                <td colspan="${columnConfig.columns.length}" style="text-align: center;">No data found</td>
            </tr>
        `);
            } else {
                let rowIndex = 1;

                tableRows.forEach(row => {
                    const cells = row.querySelectorAll('td');

                    if (cells.length > 1) { // Skip empty message rows
                        frameDoc.write(`<tr>`);

                        // Handle each column according to the configuration
                        for (const col of columnConfig.columns) {
                            let cellContent = '';

                            if (col.type === 'index') {
                                cellContent = rowIndex;
                            } else if (col.cellIndex !== undefined && cells[col.cellIndex]) {
                                cellContent = cells[col.cellIndex].textContent.trim();

                                // Add status styling if needed
                                if (col.type === 'status') {
                                    const statusClass = getStatusClass(cellContent);
                                    if (statusClass) {
                                        cellContent = `<span class="${statusClass}">${cellContent}</span>`;
                                    }
                                }

                                // Format currency if needed
                                if (col.type === 'currency' && cellContent && cellContent !== '-') {
                                    // Remove any existing formatting and format consistently
                                    const numValue = parseFloat(cellContent.replace(/[^0-9.-]+/g, ''));
                                    if (!isNaN(numValue)) {
                                        cellContent = numValue.toLocaleString('en-TZ', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }

                            frameDoc.write(`<td class="${col.class}">${cellContent}</td>`);
                        }

                        frameDoc.write(`</tr>`);
                        rowIndex++;
                    }
                });
            }

            frameDoc.write(`
            </tbody>
        </table>
        </body>
        </html>
    `);

            frameDoc.close();

            // Small delay to ensure content is loaded before printing
            setTimeout(() => {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            }, 500);
        }

        // Helper function to get status class for styling
        function getStatusClass(status) {
            const statusLower = status.toLowerCase();

            if (statusLower.includes('paid') && !statusLower.includes('unpaid') && !statusLower.includes('partial')) {
                return 'status-paid';
            } else if (statusLower.includes('unpaid')) {
                return 'status-unpaid';
            } else if (statusLower.includes('partial')) {
                return 'status-partial';
            } else if (statusLower.includes('available')) {
                return 'status-available';
            } else if (statusLower.includes('not available')) {
                return 'status-not-available';
            } else if (statusLower.includes('sold out')) {
                return 'status-sold-out';
            }

            return '';
        }

        /**
         * Print Sales Table
         */
        function printSalesTable() {
            // Get filter information
            const search = document.getElementById('Salesearch')?.value || 'All';
            const startDate = document.getElementById('sale_startDate')?.value || 'All';
            const endDate = document.getElementById('sale_endDate')?.value || 'All';

            const filterInfo = {
                'Search': search !== '' ? search : 'All',
                'Start Date': startDate !== '' ? startDate : 'All',
                'End Date': endDate !== '' ? endDate : 'All'
            };

            // Define column configuration
            const columnConfig = {
                columns: [{
                        label: 'S/N',
                        class: 'col-sn',
                        type: 'index'
                    },
                    {
                        label: 'Ref.No',
                        class: 'col-ref',
                        cellIndex: 1
                    },
                    {
                        label: 'Date',
                        class: 'col-date',
                        cellIndex: 2
                    },
                    {
                        label: 'Customer',
                        class: 'col-customer',
                        cellIndex: 3
                    },
                    {
                        label: 'Discount',
                        class: 'col-discount',
                        cellIndex: 4,
                        type: 'currency'
                    },
                    {
                        label: 'Amount',
                        class: 'col-amount',
                        cellIndex: 5,
                        type: 'currency'
                    },
                    {
                        label: 'Paid',
                        class: 'col-paid',
                        cellIndex: 6,
                        type: 'currency'
                    },
                    {
                        label: 'Unpaid',
                        class: 'col-unpaid',
                        cellIndex: 7,
                        type: 'currency'
                    },
                    {
                        label: 'Status',
                        class: 'col-status',
                        cellIndex: 8,
                        type: 'status'
                    }
                ],
                widths: {
                    'col-sn': '5%',
                    'col-ref': '10%',
                    'col-date': '10%',
                    'col-customer': '20%',
                    'col-discount': '10%',
                    'col-amount': '15%',
                    'col-paid': '10%',
                    'col-unpaid': '10%',
                    'col-status': '10%'
                }
            };

            printGenericTable('sales', 'SALES REPORT', 'TRANSACTION DETAILS', columnConfig, filterInfo);
        }

        /**
         * Print Purchases Table
         */
        function printPurchasesTable() {
            // Get filter information
            const search = document.getElementById('Purchasessearch')?.value || 'All';
            const startDate = document.getElementById('PurchasesstartDate')?.value || 'All';
            const endDate = document.getElementById('PurchasesendDate')?.value || 'All';

            const filterInfo = {
                'Search': search !== '' ? search : 'All',
                'Start Date': startDate !== '' ? startDate : 'All',
                'End Date': endDate !== '' ? endDate : 'All'
            };

            // Define column configuration
            const columnConfig = {
                columns: [{
                        label: 'S/N',
                        class: 'col-sn',
                        type: 'index'
                    },
                    {
                        label: 'Ref.No',
                        class: 'col-ref',
                        cellIndex: 1
                    },
                    {
                        label: 'Date',
                        class: 'col-date',
                        cellIndex: 2
                    },
                    {
                        label: 'Supplier',
                        class: 'col-supplier',
                        cellIndex: 3
                    },
                    {
                        label: 'Discount',
                        class: 'col-discount',
                        cellIndex: 4,
                        type: 'currency'
                    },
                    {
                        label: 'Total',
                        class: 'col-total',
                        cellIndex: 5,
                        type: 'currency'
                    },
                    {
                        label: 'Paid',
                        class: 'col-paid',
                        cellIndex: 6,
                        type: 'currency'
                    },
                    {
                        label: 'Unpaid',
                        class: 'col-unpaid',
                        cellIndex: 7,
                        type: 'currency'
                    },
                    {
                        label: 'Status',
                        class: 'col-status',
                        cellIndex: 8,
                        type: 'status'
                    }
                ],
                widths: {
                    'col-sn': '5%',
                    'col-ref': '10%',
                    'col-date': '10%',
                    'col-supplier': '20%',
                    'col-discount': '10%',
                    'col-total': '15%',
                    'col-paid': '10%',
                    'col-unpaid': '10%',
                    'col-status': '10%'
                }
            };

            printGenericTable('purchases', 'PURCHASES REPORT', 'TRANSACTION DETAILS', columnConfig, filterInfo);
        }

        /**
         * Print Transactions Table
         */
        function printTransactionsTable() {
            // Get filter information
            const search = document.getElementById('transactionsearch')?.value || 'All';
            const startDate = document.getElementById('transactionstartDate')?.value || 'All';
            const endDate = document.getElementById('transactionendDate')?.value || 'All';

            const filterInfo = {
                'Search': search !== '' ? search : 'All',
                'Start Date': startDate !== '' ? startDate : 'All',
                'End Date': endDate !== '' ? endDate : 'All'
            };

            // Define column configuration
            const columnConfig = {
                columns: [{
                        label: 'S/N',
                        class: 'col-sn',
                        type: 'index'
                    },
                    {
                        label: 'Ref.No',
                        class: 'col-ref',
                        cellIndex: 1
                    },
                    {
                        label: 'Date',
                        class: 'col-date',
                        cellIndex: 2
                    },
                    {
                        label: 'Name',
                        class: 'col-name',
                        cellIndex: 3
                    },
                    {
                        label: 'Method',
                        class: 'col-method',
                        cellIndex: 4
                    },
                    {
                        label: 'Type',
                        class: 'col-type',
                        cellIndex: 5
                    },
                    {
                        label: 'Amount',
                        class: 'col-amount',
                        cellIndex: 6,
                        type: 'currency'
                    }
                ],
                widths: {
                    'col-sn': '5%',
                    'col-ref': '15%',
                    'col-date': '15%',
                    'col-name': '20%',
                    'col-method': '15%',
                    'col-type': '15%',
                    'col-amount': '15%'
                }
            };

            printGenericTable('transaction_table_container', 'TRANSACTIONS REPORT', 'PAYMENT DETAILS', columnConfig, filterInfo);
        }

        /**
         * Print Parties (Customers/Suppliers) Table
         */
        function printPartiesTable() {
            // Get filter information
            const search = document.getElementById('partDetails_filterTable')?.value || 'All';
            const status = document.getElementById('filterby_status_inTable')?.value || 'All';
            const type = document.getElementById('filter_type_inTable')?.value || 'All';

            const filterInfo = {
                'Search': search !== '' ? search : 'All',
                'Status': status !== '' ? status : 'All',
                'Type': type !== '' ? type : 'All'
            };

            // Define column configuration
            const columnConfig = {
                columns: [{
                        label: 'S/N',
                        class: 'col-sn',
                        type: 'index'
                    },
                    {
                        label: 'TIN',
                        class: 'col-tin',
                        cellIndex: 1
                    },
                    {
                        label: 'Name',
                        class: 'col-name',
                        cellIndex: 2
                    },
                    {
                        label: 'Email',
                        class: 'col-email',
                        cellIndex: 3
                    },
                    {
                        label: 'Phone',
                        class: 'col-phone',
                        cellIndex: 4
                    },
                    {
                        label: 'Type',
                        class: 'col-type',
                        cellIndex: 5
                    },
                    {
                        label: 'Amount',
                        class: 'col-amount',
                        cellIndex: 6,
                        type: 'currency'
                    },
                    {
                        label: 'Paid',
                        class: 'col-paid',
                        cellIndex: 7,
                        type: 'currency'
                    },
                    {
                        label: 'Debt',
                        class: 'col-debt',
                        cellIndex: 8,
                        type: 'currency'
                    },
                    {
                        label: 'Status',
                        class: 'col-status',
                        cellIndex: 9,
                        type: 'status'
                    }
                ],
                widths: {
                    'col-sn': '5%',
                    'col-tin': '10%',
                    'col-name': '15%',
                    'col-email': '15%',
                    'col-phone': '10%',
                    'col-type': '10%',
                    'col-amount': '10%',
                    'col-paid': '10%',
                    'col-debt': '10%',
                    'col-status': '5%'
                }
            };

            printGenericTable('parties_table_container', 'PARTIES REPORT', 'CUSTOMERS & SUPPLIERS', columnConfig, filterInfo);
        }

        /**
         * Print Employees Table
         */
        function printEmployeesTable() {
            // Get filter information
            const search = document.getElementById('search_emlpoyee_inTable')?.value || 'All';

            const filterInfo = {
                'Search': search !== '' ? search : 'All'
            };

            // Define column configuration
            const columnConfig = {
                columns: [{
                        label: 'S/N',
                        class: 'col-sn',
                        type: 'index'
                    },
                    {
                        label: 'Name',
                        class: 'col-name',
                        cellIndex: 1
                    },
                    {
                        label: 'Phone',
                        class: 'col-phone',
                        cellIndex: 2
                    },
                    {
                        label: 'Email',
                        class: 'col-email',
                        cellIndex: 3
                    },
                    {
                        label: 'Gender',
                        class: 'col-gender',
                        cellIndex: 4
                    },
                    {
                        label: 'Role',
                        class: 'col-role',
                        cellIndex: 5
                    },
                    {
                        label: 'Status',
                        class: 'col-status',
                        cellIndex: 6,
                        type: 'status'
                    }
                ],
                widths: {
                    'col-sn': '5%',
                    'col-name': '25%',
                    'col-phone': '15%',
                    'col-email': '20%',
                    'col-gender': '10%',
                    'col-role': '15%',
                    'col-status': '10%'
                }
            };

            printGenericTable('employees', 'EMPLOYEES REPORT', 'STAFF DETAILS', columnConfig, filterInfo);
        }

        // Function to attach print button event listeners
        function attachPrintButtonListeners() {
            // Add hidden iframe for printing if it doesn't exist
            if (!document.getElementById('printFrame')) {
                const iframe = document.createElement('iframe');
                iframe.id = 'printFrame';
                iframe.style.position = 'absolute';
                iframe.style.top = '-9999px';
                iframe.style.left = '-9999px';
                document.body.appendChild(iframe);
            }

            // Find all print buttons and attach event listeners
            const printButtons = document.querySelectorAll('.bi-printer');
            printButtons.forEach(button => {
                // Get parent tab content to determine which print function to call
                const parentTab = button.closest('.tab-content');
                if (parentTab) {
                    button.addEventListener('click', function() {
                        if (parentTab.id === 'sales') {
                            printSalesTable();
                        } else if (parentTab.id === 'purchases') {
                            printPurchasesTable();
                        } else if (parentTab.id === 'transaction') {
                            printTransactionsTable();
                        } else if (parentTab.id === 'customers') {
                            printPartiesTable();
                        } else if (parentTab.id === 'employees') {
                            printEmployeesTable();
                        }
                    });
                }
            });
        }

        // Call this function when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            attachPrintButtonListeners();
        });


























































































































































































































        function showEditpasswordModal() {
            let dialog = document.getElementById('EditpasswordModal');
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');
        }

        function hideEditpasswordModal() {
            let dialog = document.getElementById('EditpasswordModal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        document.getElementById('EditpasswordModal').addEventListener('click', function(event) {
            if (event.target === this) {
                hideEditpasswordModal();
            }
        });

        $('#changePasswordForm').validate({
            errorPlacement: function($error, $element) {
                $error.appendTo($element.closest("div"));
            },
            rules: {
                current_password: {
                    required: true
                },
                new_password: {
                    required: true,
                    minlength: 8
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password" // Validate that confirm_password matches new_password
                }
            },
            messages: {
                current_password: {
                    required: "Current password is required"
                },
                new_password: {
                    required: "New password is required",
                    minlength: "Password must be at least 8 characters"
                },
                confirm_password: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                const formData = $(form).serializeArray();
                const userId = $('#userId').val();

                $.ajax({
                    url: '/change-password',
                    type: "POST",
                    data: formData,
                    beforeSend: function() {
                        console.log('Loading...');
                    },
                    success: function(response) {
                        $("#changePasswordForm")[0].reset();
                        hideEditpasswordModal();
                        showFeedbackModal('success', 'Password Changed!', 'Password has been change successfully.');

                    },
                    error: function(error) {
                        showFeedbackModal('error', 'Changing Failed!', 'There was an error Changing Password. Please try again.');

                    }
                });
            }
        });


        function show_add_employee_Dialog(employeeID) {
            let dialog = document.getElementById('add_employee_Modal');
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');
        }

        function hide_add_employee_Dialog() {
            let dialog = document.getElementById('add_employee_Modal');

            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        function hide_edit_employee_Dialog() {
            let dialog = document.getElementById('edit_employee_Modal');

            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }


        function show_delete_transaction_Dialog(transaction_id) {
            // Set the item ID to delete
            $('#delete_transaction_id').val(transaction_id);
            // Show the modal
            const modal = document.getElementById('delete_transaction_Modal');
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
                    hide_delete_transaction_Dialog();
                }
            });

            // Add escape key listener
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    hide_delete_transaction_Dialog();
                }
            });
        }

        $(document).on('click', '.delete_transaction_btn', function(e) {
            e.preventDefault();
            var transaction_id = $('#delete_transaction_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/delete_transaction/" + transaction_id,
                success: function(response) {

                    hide_delete_transaction_Dialog();
                    showFeedbackModal('success', 'Transaction Deleted!', 'Your Transaction has been Deleted successfully.');
                    loadTransactions();
                    loadPurchases();
                    loadSale();
                    loadinventory();
                    loadParties();
                    loadStatistics();
                },
                error: function(error) {
                    const response = error.responseJSON;
                    if (response && response.status === 'error') {
                        showFeedbackModal('error', 'Deleting Failed!', 'There was an error Deleting the Transaction. Please try again.');
                    }
                }
            });
        });

        function hide_delete_transaction_Dialog() {
            let dialog = document.getElementById('delete_transaction_Modal');
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

        function show_addTransactionModal() {
            let dialog = document.getElementById('addTransactionModal');
            dialog.classList.remove('hidden');
        }

        function hide_addTransactionModal() {
            let dialog = document.getElementById('addTransactionModal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        function hide_supplier_customerCheckbox() {
            let checkbox_s_c = document.getElementById('edit_supplierANDcustomer_paymentCHECKBOXID');
            checkbox_s_c.classList.add('hidden');
        }

        function hide_otherpaymentCheckbox() {
            let checkbox = document.getElementById('edit_other_paymentCHECKBOXID');
            checkbox.classList.add('hidden');
        }

        function show_editTransactionModal(transaction_id) {
            let dialog = document.getElementById('editTransactionModal');
            dialog.classList.remove('hidden');

            $.ajax({
                type: 'GET',
                url: `/populate_transaction/${transaction_id}`,
                dataType: 'json',
                success: function(transaction) {
                    // Reset form first
                    resetEditTransactionForm();

                    // Set transaction ID for form submission
                    $('#edit_transaction_id').val(transaction.id);

                    // Set common fields
                    $('#edit_transaction_date').val(transaction.transaction_date);
                    $('#edit_method').val(transaction.method);
                    $('#edit_journal_memo').val(transaction.journal_memo);

                    // Set payment type (Payment or Receipt)
                    if (transaction.type === 'Payment') {
                        $('input[name="payment_type_edit"][value="Payment"]').prop('checked', true);
                        $('#payment_type_edit').val('Payment');
                    } else if (transaction.type === 'Receipt') {
                        $('input[name="payment_type_edit"][value="Receipt"]').prop('checked', true);
                        $('#payment_type_edit').val('Receipt');
                    }

                    // Check transaction type and set appropriate fields
                    if (transaction.part_id) {
                        // This is a part payment (supplier or customer)
                        const partType = transaction.part?.type;
                        hide_otherpaymentCheckbox();
                        // Uncheck other payment checkbox and disable its fields
                        $('#edit_other_paymentCHECKBOXID').prop('checked', false);
                        $('#edit_parson_name').prop('disabled', true);
                        $('#edit_payment_amount').prop('disabled', true);

                        // Check supplier/customer payment checkbox
                        $('#edit_supplierANDcustomer_paymentCHECKBOXID').prop('checked', true);

                        // Enable part payment fields
                        $('#search_partFOR_paymentEDIT').prop('disabled', false);
                        $('#paidEDIT').prop('disabled', false);
                        $('.part_type_radioEDIT').prop('disabled', false);

                        if (partType === 'Supplier') {
                            // Supplier payment
                            $('.part_type_radioEDIT[value="Supplier"]').prop('checked', true);
                            $('.payment_type_radioEDIT').prop('disabled', true);

                            // Show supplier container, hide customer container
                            $('#supplier_transactions_containerEDIT').removeClass('hidden');
                            $('#customer_transactions_containerEDIT').addClass('hidden');

                            // Set supplier name
                            $('#search_partFOR_paymentEDIT').val(transaction.part?.name || '');
                            $('#Part_ID_paymentEDIT').val(transaction.part_id);
                            $('#Part_Type_paymentEDIT').val('Supplier');

                            // Set payment amount
                            $('#paidEDIT').val(transaction.payment_amount);

                            // Fetch supplier balance and transactions
                            fetchPartBalance(transaction.part_id);

                            // Fetch all supplier purchases - simpler approach
                            fetchPartPurchases(transaction.part_id, 'purchases_TableBodyEDIT');

                        } else if (partType === 'Customer') {
                            // Customer payment
                            $('.part_type_radioEDIT[value="Customer"]').prop('checked', true);
                            $('.payment_type_radioEDIT').prop('disabled', true);

                            // Show customer container, hide supplier container
                            $('#supplier_transactions_containerEDIT').addClass('hidden');
                            $('#customer_transactions_containerEDIT').removeClass('hidden');

                            // Set customer name
                            $('#search_partFOR_paymentEDIT').val(transaction.part?.name || '');
                            $('#Part_ID_paymentEDIT').val(transaction.part_id);
                            $('#Part_Type_paymentEDIT').val('Customer');

                            // Set payment amount
                            $('#paidEDIT').val(transaction.payment_amount);

                            // Fetch customer balance and transactions
                            fetchPartBalance(transaction.part_id);

                            // Fetch all customer sales - simpler approach
                            fetchPartSales(transaction.part_id, 'Sale_TableBodyEDIT');
                        }
                    } else {
                        hide_supplier_customerCheckbox();

                        // This is an other payment
                        $('#edit_other_paymentCHECKBOXID').prop('checked', true);
                        $('#edit_parson_name').val(transaction.person_name || '');
                        $('#edit_payment_amount').val(transaction.payment_amount || '');

                        // Enable other payment fields
                        $('#edit_parson_name').prop('disabled', false);
                        $('#edit_payment_amount').prop('disabled', false);

                        // Uncheck supplier/customer payment checkbox and disable its fields
                        $('#edit_supplierANDcustomer_paymentCHECKBOXID').prop('checked', false);

                        // Disable part payment fields
                        $('#search_partFOR_paymentEDIT').prop('disabled', true);
                        $('#paidEDIT').prop('disabled', true);
                        $('.part_type_radioEDIT').prop('disabled', true);

                        // Clear part payment fields
                        $('#search_partFOR_paymentEDIT').val('');
                        $('#Part_ID_paymentEDIT').val('');
                        $('#Part_Type_paymentEDIT').val('');
                        $('#paidEDIT').val('');

                        // Reset transaction tables
                        $('#purchases_TableBodyEDIT').html(`
<tr class="bg-white border-b border-blue-500">
    <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="5">
        Part payment disabled.
    </td>
</tr>
`);

                        $('#Sale_TableBodyEDIT').html(`
<tr class="bg-white border-b border-green-500">
    <td class="py-1 px-2 text-center text-red-600 italic text-xs" colspan="5">
        Part payment disabled.
    </td>
</tr>
`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching transaction:', error);
                    showFeedbackModal('error', 'Error', 'Failed to load transaction data');
                }
            });
        }

        function hide_editTransactionModal() {
            let dialog = document.getElementById('editTransactionModal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        function show_addPartyModal() {
            let dialog = document.getElementById('addPartyModal');
            dialog.classList.remove('hidden');
            // Reset form
            $('#addPartyForm')[0].reset();
            $('.text-red-600').addClass('hidden');
        }

        function show_editPartyModal(partyId) {
            let dialog = document.getElementById('editPartyModal');
            dialog.classList.remove('hidden');
            $.ajax({
                url: `/edit_part/${partyId}`,
                type: 'GET',
                success: function(response) {

                    const party = response.part;

                    // Fill the form
                    $('#edit_party_id').val(party.id);
                    $('#edit_name').val(party.name);
                    $('#edit_type').val(party.type);
                    $('#edit_address').val(party.address);
                    $('#edit_vat_number').val(party.vat_number);
                    $('#edit_tin_number').val(party.tin_number);
                    $('#edit_phone_number').val(party.phone_number);
                    $('#edit_email').val(party.email);
                    $('#edit_company_name').val(party.company_name);
                    $('#edit_contact_person').val(party.contact_person);

                    // Show modal
                    $('#editPartyModal').removeClass('hidden');
                },
                error: function(xhr) {
                    showFeedbackModal('error', 'Error!', xhr.responseJSON.message || 'Failed to get party details');
                }
            });
        }

        function show_delete_sale_Dialog(sale_id) {
            let dialog = document.getElementById('delete_sale_Modal');
            dialog.classList.remove('hidden');
            $('#delete_sale_id').val(sale_id);
        }

        $(document).on('click', '.delete_sale_btn', function(e) {
            e.preventDefault();
            var sale_id = $('#delete_sale_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/delete_sale/" + sale_id,
                success: function(response) {
                    hide_delete_sale_Dialog();
                    showFeedbackModal('success', 'Sale Deleted!', 'Your Sale has been Deleted successfully.');
                    loadSale();
                    loadTransactions();
                    loadinventory();
                    loadParties();
                    loadPurchases();
                    loadStatistics();
                },
                error: function(error) {
                    const response = error.responseJSON;
                    if (response && response.status === 'error') {
                        showFeedbackModal('error', 'Deleting Failed!', 'There was an error Deleting the Sale. Please try again.');
                    }
                }
            });
        });


        function hide_delete_sale_Dialog() {
            let dialog = document.getElementById('delete_sale_Modal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        function show_delete_purchase_Dialog(purchase_id) {
            let dialog = document.getElementById('delete_purchase_Modal');
            dialog.classList.remove('hidden');
            $('#delete_purchase_id').val(purchase_id);
        }

        $(document).on('click', '.delete_purchase_btn', function(e) {
            e.preventDefault();
            var purchase_id = $('#delete_purchase_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/delete_purchase/" + purchase_id,
                success: function(response) {
                    hide_delete_purchase_Dialog();
                    showFeedbackModal('success', 'Purchases Deleted!', 'Your Purchases has been Deleted successfully.');
                    loadPurchases();
                    loadTransactions();
                    loadinventory();
                    loadParties();
                    loadSale();
                    loadStatistics();
                },
                error: function(error) {
                    const response = error.responseJSON;
                    if (response && response.status === 'error') {
                        showFeedbackModal('error', 'Deleting Failed!', 'There was an error Deleting the Purchases. Please try again.');
                    }
                }
            });
        });

        function hide_delete_purchase_Dialog() {
            let dialog = document.getElementById('delete_purchase_Modal');
            setTimeout(() => {
                dialog.classList.add('hidden');
            }, 400);
        }

        function show_deletePartyModal(partyId) {
            let dialog = document.getElementById('deletePartyModal');
            dialog.classList.remove('hidden');
            $('#delete_party_id').val(partyId);
        }

        // Close Modals
        $('.close-modal').click(function() {
            $('#addPartyModal, #editPartyModal, #deletePartyModal').addClass('hidden');
        });

        function close_purchase_Modal() {
            document.getElementById('purchase_Modal').classList.add('hidden');
            document.getElementById('print_Invoice').innerHTML = '';
        }


        function close_Sales_Modal() {
            document.getElementById('Sales_Modal').classList.add('hidden');
            document.getElementById('print_Invoice').innerHTML = '';
            document.getElementById('efd_receipt').classList.add('hidden');
        }

        // Function to close EFD receipt modal
        function closeEFDReceipt() {
            document.getElementById('efd_receipt').classList.add('hidden');
        }

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
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-20px);
        }
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
            dialog.classList.add('flex');
        }

        function hide_edit_SalesMODAL() {
            let dialog = document.getElementById('editSalesModal');
            dialog.classList.add('hidden');
        }


        function show_edit_purchaseMODAL() {
            let dialog = document.getElementById('editPurchaseModal');
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');
        }

        function hide_edit_purchaseMODAL() {
            let dialog = document.getElementById('editPurchaseModal');
            dialog.classList.add('hidden');
        }

        function formatDate(dateString) {
            if (!dateString) return '';

            // Try to parse the date string
            const date = new Date(dateString);

            // Check if the date is valid
            if (isNaN(date.getTime())) {
                console.warn('Invalid date:', dateString);
                return '';
            }

            // Format as YYYY-MM-DD for input[type="date"]
            return date.toISOString().split('T')[0];
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
                    $('#record_sales_table tbody').append(newRow);
                    $('#purchase_record_table tbody').append(newRow);
                }

                // After adding the new row, ensure the item search functionality is correctly bound
                const $newRow = $(button).closest('tr').next();
                const $itemInput = $newRow.find('.item_name');

                // Make sure the parent has relative positioning
                $itemInput.parent().addClass('relative');


                // Update totals
                totalFn();
            }

            // Delete row for the main modals (regular sales or purchases)
            delRow(button) {
                const tbody_record_sales_table = $('#record_sales_table tbody');
                const tbody_purchase_record_table = $('#purchase_record_table tbody');
                const rowCount_record_sales_table = tbody_record_sales_table.children('tr').length;
                const rowCount_purchase_record_table = tbody_purchase_record_table.children('tr').length;

                // Prevent deleting the last row
                if (rowCount_record_sales_table > 1) {
                    $(button).closest('tr').remove();
                    totalFn();
                } else {
                    // Show error message
                    showFeedbackModal('error', 'Error', 'Cannot delete the last row');
                }

                // Prevent deleting the last row
                if (rowCount_purchase_record_table > 1) {
                    $(button).closest('tr').remove();
                    totalFn();
                } else {
                    // Show error message
                    showFeedbackModal('error', 'Error', 'Cannot delete the last row');
                }
            }

            // Add row for edit modals
            addRow_edit(button) {
                // Determine which edit modal container to target based on the instance type
                let containerSelector;
                if (this.modalType === 'sales') {
                    containerSelector = '#sales_edit_items_container';
                } else {
                    containerSelector = '#purchase_edit_items_container';
                }

                // Create the appropriate template
                const newRow = this.getEditRowTemplate();

                // Add the row to the proper container
                if (button) {
                    $(button).closest('tr').after(newRow);
                } else {
                    $(containerSelector).append(newRow);
                }

                // Update totals
                this.updateEditTotals();
            }

            // Delete row for edit modals
            delRow_edit(button) {
                let containerSelector;
                if (this.modalType === 'sales') {
                    containerSelector = '#sales_edit_items_container';
                } else {
                    containerSelector = '#purchase_edit_items_container';
                }

                const rowCount = $(containerSelector + ' tr').length;

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
        <input onkeyup="netPriceFn(this)" id="qtn" placeholder="Qty" name="quantity" class="quantity border w-14 px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="number" />
    </td>
    <td class="p-1">
        <div class="relative">
            <input placeholder="Item" id="sale_item_name" name="item_name" data-type="item_name" class="item_name border w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
            <div id="itemslist" class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
            <input type="hidden" class="item_id" name="item_id" />
        </div>
    </td>
    <td class="p-1">
        <textarea placeholder="Description" rows="1" class="description w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
    </td>
    <td class="p-1">
        <input onkeyup="netPriceFn(this)" name="Sales_price" placeholder="0.00 TZS" class="unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border cursor-not-allowed" type="text" readonly />
    </td>
    <td class="p-1">
        <input onkeyup="netPriceFn(this)" name="discount" placeholder="0.00 TZS" class="discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" />
    </td>
    <td id="ntl" class="ntl text-center p-1"><span class="">TSh</span>0.00</td>
    <td class="p-1">
        <div class="flex justify-center gap-2">
            <button type="button" onclick="tableManager.addRow(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </button>
            <button type="button" onclick="tableManager.delRow(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
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
            <input placeholder="Item" id="purchase_item_name" name="item_name" data-type="item_name" class="item_name border w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded" type="text" />
            <div class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
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
            <button type="button" onclick="purchaseTableManager.addRow(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </button>
            <button type="button" onclick="purchaseTableManager.delRow(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
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

            // Template for edit rows
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
            <div class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
            <input type="hidden" name="item_ids[]" value="">
        </div>
    </td>
    <td class="p-1">
        <textarea row="1" class="w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border"></textarea>
    </td>
    <td class="p-1">
        <input value="" name="Sales_prices[]" class="edit_unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border cursor-not-allowed" type="number" step="0.01" readonly required>
    </td>
    <td class="p-1">
        <input value="0" name="discounts[]" class="edit_discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01">
    </td>
    <td class="edit_net_price text-center p-1">TSh 0.00</td>
    <td class="p-1">
        <div class="flex justify-center gap-2">
            <button type="button" onclick="tableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </button>
            <button type="button" onclick="tableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
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
            <div class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
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
            <button type="button" onclick="purchaseTableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </button>
            <button type="button" onclick="purchaseTableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
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
                // Determine which modal and elements to use based on instance type
                let totalSelector, debtSelector, paidSelector, itemsContainerSelector;

                if (this.modalType === 'sales') {
                    totalSelector = '#sales_edit_total';
                    debtSelector = '#sales_edit_debt';
                    paidSelector = '#sales_edit_paid';
                    itemsContainerSelector = '#sales_edit_items_container';
                } else {
                    totalSelector = '#purchase_edit_total';
                    debtSelector = '#purchase_edit_debt';
                    paidSelector = '#purchase_edit_paid';
                    itemsContainerSelector = '#purchase_edit_items_container';
                }

                let total = 0;
                $(itemsContainerSelector + ' tr').each(function() {
                    const quantity = parseFloat($(this).find('.edit_quantity').val()) || 0;
                    const price = parseFloat($(this).find('.edit_unit_price').val()) || 0;
                    const discount = parseFloat($(this).find('.edit_discount').val()) || 0;
                    const netPrice = (quantity * price) - discount;

                    $(this).find('.edit_net_price').text(`TSh ${netPrice.toFixed(2)}`);
                    total += netPrice;
                });

                const paid = parseFloat($(paidSelector).val()) || 0;
                const debt = total - paid;

                $(totalSelector).text(`${total.toFixed(2)} Tzs`);
                $(debtSelector).text(`${debt.toFixed(2)} Tzs`);
            }

            // Populate edit item row with item data
            populateEditItemRow(item) {
                // Calculate current stock if item exists
                const currentStock = item?.item?.current_stock ?? 0;

                if (this.modalType === 'purchase') {
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
            <input value="${item?.item?.name || ''}" name="edit_item_name[]" id="edit_purchase_item_name" class="edit_item_name w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text">
            <div class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
            <input type="hidden" name="item_ids[]" value="${item?.item_id || ''}">
        </div>
    </td>
    <td class="p-1">
        <textarea rows="1" class="w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border">${item?.item?.description || ''}</textarea>
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
            <button type="button" onclick="purchaseTableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </button>
            <button type="button" onclick="purchaseTableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                </svg>
            </button>
        </div>
    </td>
</tr>
`;
                } else { // sales
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
            <input value="${item?.item?.name || ''}" name="edit_item_name[]" id="edit_sale_item_name" class="edit_item_name w-full min-w-[100px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="text">
            <div class="itemslist absolute z-40 bg-white shadow-lg rounded-md max-h-40 overflow-y-auto w-full"></div>
            <input type="hidden" name="item_ids[]" value="${item?.item_id || ''}">
        </div>
    </td>
    <td class="p-1">
        <textarea rows="1" class="w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border">${item?.item?.description || ''}</textarea>
    </td>
    <td class="p-1">
        <input value="${item?.sale_price || ''}" name="Sales_prices[]" class="edit_unit_price w-full min-w-[80px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border cursor-not-allowed" type="number" step="0.01" readonly required>
    </td>
    <td class="p-1">
        <input value="${item?.discount || '0'}" name="discounts[]" class="edit_discount w-full min-w-[70px] px-2 py-1 bg-gray-100 text-xs sm:text-sm rounded border" type="number" step="0.01">
    </td>
    <td class="edit_net_price text-center p-1">TSh ${this.calculateNetPrice(item?.quantity || 0, item?.sale_price || 0, item?.discount || 0)}</td>
    <td class="p-1">
        <div class="flex justify-center gap-2">
            <button type="button" onclick="tableManager.addRow_edit(this)" class="text-blue-600 hover:text-blue-800 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                </svg>
            </button>
            <button type="button" onclick="tableManager.delRow_edit(this)" class="text-red-600 hover:text-red-800 cursor-pointer">
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

            populateEditForm(data) {
                if (this.modalType === 'sales') {
                    let formatted_date = '';
                    if (data?.sale_date) {
                        // Format the date properly for the date input
                        formatted_date = formatDate(data.sale_date);
                    }

                    // Update form fields
                    $('#sales_edit_Sales_id').val(data.id);
                    $('#sales_edit_date').val(formatted_date);
                    $('#sales_edit_Customer').val(data.customer && data.customer.name ? data.customer.name : '');
                    $('#sales_edit_Customer_id').val(data.customer && data.customer.id ? data.customer.id : '');
                    $('#sales_edit_description').val(data.description);
                    $('#sales_edit_paid').val(data.paid);

                    // Clear existing items
                    $('#sales_edit_items_container').empty();

                    // Add sales items
                    if (data.sale_items && data.sale_items.length > 0) {
                        data.sale_items.forEach(item => {
                            $('#sales_edit_items_container').append(this.populateEditItemRow(item));
                        });
                    } else {
                        // Add an empty row if no items found
                        this.addRow_edit();
                    }
                } else { // purchase
                    let formatted_date = '';
                    if (data?.purchase_date) {
                        // Format the date properly for the date input
                        formatted_date = formatDate(data.purchase_date);
                    }

                    // Update form fields
                    $('#purchase_edit_purchase_id').val(data.id);
                    $('#purchase_edit_date').val(formatted_date);
                    $('#purchase_edit_supplier').val(data.supplier && data.supplier.name ? data.supplier.name : '');
                    $('#purchase_edit_supplier_id').val(data.supplier && data.supplier.id ? data.supplier.id : '');
                    $('#purchase_edit_description').val(data.description);
                    $('#purchase_edit_paid').val(data.paid);

                    // Clear existing items
                    $('#purchase_edit_items_container').empty();

                    if (data.purchase_items && data.purchase_items.length > 0) {
                        data.purchase_items.forEach(item => {
                            // Format expire_date for each item before passing to row creation
                            if (item.expire_date) {
                                const formattedExpireDate = formatDate(item.expire_date);
                                item.expire_date = formattedExpireDate;
                            }
                            $('#purchase_edit_items_container').append(this.populateEditItemRow(item));
                        });
                    } else {
                        // Add empty row if no items
                        this.addRow_edit();
                    }
                }

                // Update totals
                this.updateEditTotals();
            }

            // Bind events
            bindEvents() {
                // Use proper context binding for 'this'
                const self = this;

                // Event listeners for real-time updates in edit modal
                $(document).on('input', '.edit_quantity, .edit_unit_price, .edit_discount', function() {
                    self.updateEditTotals();
                });

                // Special handler for paid field changes
                $(document).on('input', '#sales_edit_paid, #purchase_edit_paid', function() {
                    self.updateEditTotals();
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

        // Initialize document ready function to reinitialize managers
        window.tableManager = new TableRowManager('sales');
        window.purchaseTableManager = new TableRowManager('purchase');
    </script>
</body>

</html>