<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Tabbed Interface</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- jQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
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

        /* Loading animation */
        .loading-spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 3px solid #4F46E5;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="customers">
                Customers
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="inventory">
                Inventory
            </button>
            <button class="tab-button px-4 py-2 text-sm font-medium rounded-t-lg mr-2 focus:outline-none" data-tab="reports">
                Reports
            </button>
        </div>

        <!-- Tab Content -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 animate__animated animate__fadeIn">
            <!-- Sales Tab Content -->
            <div id="sales" class="tab-content active">
                <div class=" flex justify-between">
                    <h2 class="text-xl font-semibold mb-1">Sales Overview</h2>

                    <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-md">
                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart3 scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
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
                <div class="mt-4 text-center text-gray-500" id="sales-loading">
                    <div class="loading-spinner"></div>
                    <p class="mt-2">Loading data...</p>
                </div>
            </div>

            <!-- Customers Tab Content -->
            <div id="customers" class="tab-content">
                <h2 class="text-xl font-semibold mb-4">Customer Database</h2>
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
                <div class="mt-4 text-center text-gray-500" id="customers-loading">
                    <div class="loading-spinner"></div>
                    <p class="mt-2">Loading data...</p>
                </div>
            </div>

            <!-- Inventory Tab Content -->
            <div id="inventory" class="tab-content">
                <div class=" flex justify-between mb-1">
                    <h2 class="text-xl font-semibold">Inventory Status</h2>

                    <div class="flex gap-5 bg-gray-300 p-2 rounded-md shadow-md">
                        <div class="relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-bar-graph scale-125 hover:text-blue-600 cursor-pointer" viewBox="0 0 16 16">
                                <path d="M10 13.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-6a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm-2.5.5a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5zm-3 0a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5z" />
                                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                            </svg>
                            <span class="absolute left-1/2 transform -translate-x-1/2 -bottom-8 text-xs bg-gray-700 text-white px-2 py-1 rounded-md opacity-0 group-hover:opacity-100">Statisticts</span>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <!-- Filter Container with responsive grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">

                        <!-- Search Filter -->
                        <div class="w-full">
                            <div class="relative">
                                <svg class="w-5 h-5 text-gray-400 absolute right-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input
                                    type="text"
                                    id="searchTable_items"
                                    placeholder="Search..."
                                    class="w-full px-3 h-8 border-2 border-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full">
                            <select
                                id="part_type_filter"
                                class="w-full px-3 h-8 border-2 border-gray-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                <option value="Available">Available</option>
                                <option value="Available">Not Available</option>
                                <option value="Sold Out">Sold Out</option>
                                <option value="Damage">Damaged</option>
                                <option value="Expired">Expired</option>
                            </select>
                        </div>

                        <!-- Quantity Range Filter -->
                        <div class="w-full">
                            <div class="flex flex-wrap items-center space-x-1">
                                <input
                                    type="number"
                                    id="min_quantity"
                                    placeholder="Min Qty"
                                    min="0"
                                    max="1000"
                                    class="flex-1 min-w-[80px] px-3 h-8 border-2 border-gray-500 rounded-lg">
                                <span class="text-gray-500">to</span>
                                <input
                                    type="number"
                                    id="max_quantity"
                                    placeholder="Max Qty"
                                    min="0"
                                    max="1000"
                                    class="flex-1 min-w-[80px] px-3 h-8 border-2 border-gray-500 rounded-lg">
                            </div>
                        </div>

                    </div>

                    <div class="responsive-table">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">SKU</th>
                                    <th class="py-3 px-6 text-left">Product</th>
                                    <th class="py-3 px-6 text-left">Category</th>
                                    <th class="py-3 px-6 text-right">Stock</th>
                                    <th class="py-3 px-6 text-right">Price</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                <!-- Table rows will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center text-gray-500" id="inventory-loading">
                        <div class="loading-spinner"></div>
                        <p class="mt-2">Loading data...</p>
                    </div>
                </div>

                <!-- Reports Tab Content -->
                <div id="reports" class="tab-content">
                    <h2 class="text-xl font-semibold mb-4">Monthly Reports</h2>
                    <div class="responsive-table">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Month</th>
                                    <th class="py-3 px-6 text-right">Total Sales</th>
                                    <th class="py-3 px-6 text-right">Orders</th>
                                    <th class="py-3 px-6 text-right">New Customers</th>
                                    <th class="py-3 px-6 text-right">Growth</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                <!-- Table rows will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center text-gray-500" id="reports-loading">
                        <div class="loading-spinner"></div>
                        <p class="mt-2">Loading data...</p>
                    </div>
                </div>
            </div>
    </main>

    <script>
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

            // Simulate AJAX request with setTimeout
            setTimeout(function() {
                let data = [];

                // Generate mock data based on tab
                switch (tabId) {
                    case 'sales':
                        data = [{
                                id: 'S001',
                                date: '2025-02-25',
                                customer: 'John Doe',
                                amount: '$1,234.56',
                                status: 'Completed'
                            },
                            {
                                id: 'S002',
                                date: '2025-02-24',
                                customer: 'Jane Smith',
                                amount: '$842.19',
                                status: 'Processing'
                            },
                            {
                                id: 'S003',
                                date: '2025-02-23',
                                customer: 'Bob Johnson',
                                amount: '$547.33',
                                status: 'Completed'
                            },
                            {
                                id: 'S004',
                                date: '2025-02-22',
                                customer: 'Alice Brown',
                                amount: '$1,842.00',
                                status: 'Completed'
                            },
                            {
                                id: 'S005',
                                date: '2025-02-21',
                                customer: 'Tom Wilson',
                                amount: '$326.87',
                                status: 'Pending'
                            }
                        ];

                        let salesHtml = '';
                        data.forEach((item, index) => {
                            salesHtml += `
                                <tr class="border-b border-gray-200 hover:bg-gray-50 animate__animated animate__fadeIn" style="animation-delay: ${index * 0.1}s">
                                    <td class="py-3 px-6 text-left">${item.id}</td>
                                    <td class="py-3 px-6 text-left">${item.date}</td>
                                    <td class="py-3 px-6 text-left">${item.customer}</td>
                                    <td class="py-3 px-6 text-right">${item.amount}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="bg-${getStatusColor(item.status)}-100 text-${getStatusColor(item.status)}-800 py-1 px-3 rounded-full text-xs">
                                            ${item.status}
                                        </span>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#sales tbody').html(salesHtml);
                        break;

                    case 'customers':
                        data = [{
                                id: 'C001',
                                name: 'John Doe',
                                email: 'john@example.com',
                                phone: '(555) 123-4567',
                                orders: 8
                            },
                            {
                                id: 'C002',
                                name: 'Jane Smith',
                                email: 'jane@example.com',
                                phone: '(555) 987-6543',
                                orders: 12
                            },
                            {
                                id: 'C003',
                                name: 'Bob Johnson',
                                email: 'bob@example.com',
                                phone: '(555) 234-5678',
                                orders: 3
                            },
                            {
                                id: 'C004',
                                name: 'Alice Brown',
                                email: 'alice@example.com',
                                phone: '(555) 876-5432',
                                orders: 15
                            },
                            {
                                id: 'C005',
                                name: 'Tom Wilson',
                                email: 'tom@example.com',
                                phone: '(555) 345-6789',
                                orders: 5
                            }
                        ];

                        let customersHtml = '';
                        data.forEach((item, index) => {
                            customersHtml += `
                                <tr class="border-b border-gray-200 hover:bg-gray-50 animate__animated animate__fadeIn" style="animation-delay: ${index * 0.1}s">
                                    <td class="py-3 px-6 text-left">${item.id}</td>
                                    <td class="py-3 px-6 text-left">${item.name}</td>
                                    <td class="py-3 px-6 text-left">${item.email}</td>
                                    <td class="py-3 px-6 text-left">${item.phone}</td>
                                    <td class="py-3 px-6 text-right">${item.orders}</td>
                                </tr>
                            `;
                        });
                        $('#customers tbody').html(customersHtml);
                        break;

                    case 'inventory':
                        data = [{
                                sku: 'P001',
                                product: 'Laptop Deluxe',
                                category: 'Electronics',
                                stock: 24,
                                price: '$1,299.99'
                            },
                            {
                                sku: 'P002',
                                product: 'Smartphone X',
                                category: 'Electronics',
                                stock: 42,
                                price: '$899.99'
                            },
                            {
                                sku: 'P003',
                                product: 'Office Chair',
                                category: 'Furniture',
                                stock: 18,
                                price: '$299.99'
                            },
                            {
                                sku: 'P004',
                                product: 'Coffee Maker',
                                category: 'Appliances',
                                stock: 36,
                                price: '$89.99'
                            },
                            {
                                sku: 'P005',
                                product: 'Wireless Headphones',
                                category: 'Electronics',
                                stock: 53,
                                price: '$149.99'
                            }
                        ];

                        let inventoryHtml = '';
                        data.forEach((item, index) => {
                            const stockClass = item.stock < 20 ? 'text-red-600' : (item.stock < 40 ? 'text-yellow-600' : 'text-green-600');

                            inventoryHtml += `
                                <tr class="border-b border-gray-200 hover:bg-gray-50 animate__animated animate__fadeIn" style="animation-delay: ${index * 0.1}s">
                                    <td class="py-3 px-6 text-left">${item.sku}</td>
                                    <td class="py-3 px-6 text-left">${item.product}</td>
                                    <td class="py-3 px-6 text-left">${item.category}</td>
                                    <td class="py-3 px-6 text-right ${stockClass} font-medium">${item.stock}</td>
                                    <td class="py-3 px-6 text-right">${item.price}</td>
                                </tr>
                            `;
                        });
                        $('#inventory tbody').html(inventoryHtml);
                        break;

                    case 'reports':
                        data = [{
                                month: 'February 2025',
                                sales: '$148,256.42',
                                orders: 532,
                                newCustomers: 78,
                                growth: '+5.2%'
                            },
                            {
                                month: 'January 2025',
                                sales: '$142,364.19',
                                orders: 508,
                                newCustomers: 65,
                                growth: '+3.7%'
                            },
                            {
                                month: 'December 2024',
                                sales: '$187,452.88',
                                orders: 684,
                                newCustomers: 112,
                                growth: '+12.3%'
                            },
                            {
                                month: 'November 2024',
                                sales: '$132,759.34',
                                orders: 487,
                                newCustomers: 53,
                                growth: '-1.8%'
                            },
                            {
                                month: 'October 2024',
                                sales: '$139,874.21',
                                orders: 521,
                                newCustomers: 61,
                                growth: '+2.4%'
                            }
                        ];

                        let reportsHtml = '';
                        data.forEach((item, index) => {
                            const growthClass = item.growth.includes('-') ? 'text-red-600' : 'text-green-600';

                            reportsHtml += `
                                <tr class="border-b border-gray-200 hover:bg-gray-50 animate__animated animate__fadeIn" style="animation-delay: ${index * 0.1}s">
                                    <td class="py-3 px-6 text-left">${item.month}</td>
                                    <td class="py-3 px-6 text-right">${item.sales}</td>
                                    <td class="py-3 px-6 text-right">${item.orders}</td>
                                    <td class="py-3 px-6 text-right">${item.newCustomers}</td>
                                    <td class="py-3 px-6 text-right ${growthClass} font-medium">${item.growth}</td>
                                </tr>
                            `;
                        });
                        $('#reports tbody').html(reportsHtml);
                        break;
                }

                $('#' + tabId + '-loading').hide();
            }, 800); // Simulate delay for AJAX request
        }

        // Helper function to get status color
        function getStatusColor(status) {
            switch (status) {
                case 'Completed':
                    return 'green';
                case 'Processing':
                    return 'blue';
                case 'Pending':
                    return 'yellow';
                default:
                    return 'gray';
            }
        }
    </script>
</body>

</html>