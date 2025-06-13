<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Latto Store - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-300 min-h-screen">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-blue-600 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome to Latto Store!</h1>
                    <p class="text-blue-100">{{ auth()->user()->name }} ({{ auth()->user()->role ?? 'User' }}) - {{ date('l, F j, Y') }}</p>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <p class="text-sm text-blue-200">Business Year</p>
                        <p class="text-xl font-semibold">{{ date('Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Products -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Products</p>
                        <p class="text-3xl font-bold text-blue-600" id="totalProducts">{{ $stats['total_products'] ?? 0 }}</p>
                        <p class="text-sm text-yellow-600 mt-1">
                            <i class="fas fa-exclamation-triangle"></i> <span id="lowStockCount">{{ $stockAlerts['low_stock'] ?? 0 }}</span> low stock
                        </p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-boxes text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Employees -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Employees</p>
                        <p class="text-3xl font-bold text-green-600" id="totalEmployees">{{ $stats['total_employees'] ?? 0 }}</p>
                        <p class="text-sm text-gray-500 mt-1">Active staff</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Today's Sales -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Today's Sales</p>
                        <div class="flex items-center space-x-2">
                            @if (in_array(Auth::user()->role, ['Admin', 'Manager']) || Auth::user()->email == 'swedyharuny@gmail.com')
                            <p class="text-3xl font-bold text-purple-600" id="todaySales">
                                <span id="hiddenSales">TSH ********</span>
                                <span id="realSales" style="display: none;">TSH {{ number_format($stats['total_sales_today'] ?? 0) }}</span>
                            </p>
                            <button id="toggleSales" class="text-gray-400 hover:text-purple-600 transition-colors">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                            @else
                            <p class="text-3xl font-bold text-purple-600">TSH ********</p>
                            @endif
                        </div>
                        <p class="text-sm text-purple-600 mt-1">Today</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-cash-register text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Monthly Revenue</p>
                        <div class="flex items-center space-x-2">
                            @if (in_array(Auth::user()->role, ['Admin', 'Manager']) || Auth::user()->email == 'swedyharuny@gmail.com')
                            <p class="text-3xl font-bold text-orange-600" id="monthlyRevenue">
                                <span id="hiddenRevenue">TSH ********</span>
                                <span id="realRevenue" style="display: none;">TSH {{ number_format($stats['total_sales_month'] ?? 0) }}</span>
                            </p>
                            <button id="toggleRevenue" class="text-gray-400 hover:text-orange-600 transition-colors">
                                <i class="fas fa-eye" id="revenueEyeIcon"></i>
                            </button>
                            @else
                            <p class="text-3xl font-bold text-orange-600">TSH ********</p>
                            @endif
                        </div>
                        <p class="text-sm text-orange-600 mt-1">This Month</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-xl font-bold text-gray-800">Business Analytics</h2>
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-600">From:</label>
                        <input type="date" id="from_Date" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-600">To:</label>
                        <input type="date" id="to_Date" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button id="refreshStats" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-spinner fa-spin text-blue-600 text-3xl mb-3"></i>
                <p class="text-gray-600">Loading analytics data...</p>
            </div>
        </div>

        <!-- Main Analytics Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left Column - Charts and Main Analytics -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Sales Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Daily Sales (Last 7 Days)</h2>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Financial Metrics -->
                <div id="financial_metrics_container" class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Financial Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500">Total Revenue</div>
                            <div id="fin_revenue" class="text-2xl font-bold text-blue-600">TSH 0</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500">Gross Profit</div>
                            <div id="fin_gross_profit" class="text-2xl font-bold text-green-600">TSH 0</div>
                            <div id="fin_gross_margin" class="text-sm text-gray-600">0% margin</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500">Total Expenses</div>
                            <div id="fin_expenses" class="text-2xl font-bold text-red-600">TSH 0</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500">Net Profit</div>
                            <div id="fin_net_profit" class="text-2xl font-bold text-purple-600">TSH 0</div>
                            <div id="fin_net_margin" class="text-sm text-gray-600">0% margin</div>
                        </div>
                    </div>
                    <div class="h-64 mb-4">
                        <canvas id="monthly_profit_chart"></canvas>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500">Outstanding Receivables</div>
                            <div id="fin_receivables" class="text-lg font-bold text-gray-800">TSH 0</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-500">Outstanding Payables</div>
                            <div id="fin_payables" class="text-lg font-bold text-gray-800">TSH 0</div>
                        </div>
                    </div>
                </div>

                <!-- Sales Analytics -->
                <div id="sales_analytics_container" class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Sales Analytics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Total Sales</div>
                            <div id="total_sales_count" class="text-2xl font-bold text-blue-600">0</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Total Amount</div>
                            <div id="total_sales_amount" class="text-2xl font-bold text-green-600">TSH 0</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Average Sale</div>
                            <div id="average_sale_value" class="text-2xl font-bold text-purple-600">TSH 0</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-medium mb-3">Monthly Sales Trend</h4>
                            <div class="h-64">
                                <canvas id="monthly_sales_chart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium mb-3">Sales by Day of Week</h4>
                            <div class="h-64">
                                <canvas id="sales_by_day_chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-medium mb-3">Best Selling Products</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="best_selling_products_table" class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Inventory Health -->
                <div id="inventory_health_container" class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Inventory Health</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Total Value</div>
                            <div id="total_inventory_value" class="text-lg font-bold text-blue-600">TSH 0</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Low Stock</div>
                            <div id="low_stock_count" class="text-lg font-bold text-yellow-600">0</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Out of Stock</div>
                            <div id="out_of_stock_count" class="text-lg font-bold text-red-600">0</div>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Dead Stock</div>
                            <div id="dead_stock_count" class="text-lg font-bold text-orange-600">0</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-medium mb-3">Stock Turnover Rate</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stock_turnover_table" class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td colspan="2" class="px-3 py-3 text-center text-gray-500">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium mb-3">Slow Moving Items</h4>
                            <div class="overflow-x-auto">
                                <div id="slow_moving_table">
                                    <p class="text-center text-gray-500 py-4">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions and Summary Cards -->
            <div class="xl:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Worksheet</h2>
                    <div class="space-y-3">

                        <a href="{{ route('welcome')}}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group">
                            <i class="fas fa-box text-green-600 mr-3 group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium text-gray-800">Sheet</span>
                        </a>
                    </div>
                </div>

                <!-- Live Users -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Live Users</h2>
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-gray-500">Live</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-50 p-3 rounded-lg text-center">
                                <div class="text-sm text-gray-500">Active Now</div>
                                <div class="text-xl font-bold text-green-600" id="liveUsersCount">{{ $liveUsers['active_count'] ?? 0 }}</div>
                            </div>
                            <div class="bg-blue-50 p-3 rounded-lg text-center">
                                <div class="text-sm text-gray-500">Today's Logins</div>
                                <div class="text-xl font-bold text-blue-600" id="todayLoginsCount">{{ $liveUsers['total_logins_today'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div id="activeUsersList">
                            @if(isset($liveUsers['active_users']) && count($liveUsers['active_users']) > 0)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-600 mb-3">Currently Active:</p>
                                <div class="space-y-2 max-h-32 overflow-y-auto">
                                    @foreach($liveUsers['active_users'] as $user)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded text-sm">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                            <span class="text-gray-700 font-medium">{{ $user['name'] }}</span>
                                        </div>
                                        <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($user['last_activity'])->diffForHumans() }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-user-clock text-2xl mb-2 text-gray-300"></i>
                                <p class="text-sm">No users currently active</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stock Alerts -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Stock Alerts</h2>
                    <div class="space-y-3">
                        <div class="bg-yellow-50 p-3 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Low Stock</span>
                            </div>
                            <span class="text-lg font-bold text-yellow-600">{{ $stockAlerts['low_stock'] ?? 0 }}</span>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-times-circle text-red-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Expired Items</span>
                            </div>
                            <span class="text-lg font-bold text-red-600">{{ $stockAlerts['expired'] ?? 0 }}</span>
                        </div>
                        <div class="bg-orange-50 p-3 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-orange-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700">Expiring Soon</span>
                            </div>
                            <span class="text-lg font-bold text-orange-600">{{ $stockAlerts['expiring_soon'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activities</h2>
                    <div class="space-y-3" id="recentActivitiesList">
                        @if(isset($recentActivities) && count($recentActivities) > 0)
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-{{ $activity['icon'] }} text-blue-600 text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-500">{{ $activity['user'] }} • {{ $activity['time'] }}</p>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-clock text-3xl mb-3 text-gray-300"></i>
                            <p class="text-sm">No recent activities</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Analytics Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Customer Insights -->
            <div id="customer_insights_container" class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Customer Insights</h3>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 p-3 rounded-lg text-center">
                        <div class="text-sm text-gray-500">Total Customers</div>
                        <div id="total_customers_count" class="text-xl font-bold text-blue-600">0</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg text-center">
                        <div class="text-sm text-gray-500">New Customers</div>
                        <div id="new_customers_count" class="text-xl font-bold text-green-600">0</div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg text-center">
                        <div class="text-sm text-gray-500">Retention Rate</div>
                        <div id="retention_rate" class="text-xl font-bold text-purple-600">0%</div>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-lg text-center">
                        <div class="text-sm text-gray-500">Avg Sale/Customer</div>
                        <div id="avg_sale_per_customer" class="text-xl font-bold text-indigo-600">TSH 0</div>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-medium mb-3">Top Customers</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Orders</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody id="top_customers_table" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="3" class="px-3 py-3 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Business KPIs -->
            <div id="business_kpis_container" class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Business KPIs</h3>
                <div class="grid grid-cols-1 gap-4 mb-6">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg">
                        <div class="text-sm text-gray-500">Sales Growth</div>
                        <div id="sales_growth" class="text-2xl font-bold text-green-600">0%</div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg">
                        <div class="text-sm text-gray-500">Profit Growth</div>
                        <div id="profit_growth" class="text-2xl font-bold text-blue-600">0%</div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg">
                        <div class="text-sm text-gray-500">Customer Growth</div>
                        <div id="customer_growth" class="text-2xl font-bold text-purple-600">0%</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium mb-2 text-gray-700">Current Period</h4>
                        <div class="space-y-1">
                            <div class="text-xs text-gray-500">Sales: <span id="current_period_sales" class="font-medium">TSH 0</span></div>
                            <div class="text-xs text-gray-500">Profit: <span id="current_period_profit" class="font-medium">TSH 0</span></div>
                            <div class="text-xs text-gray-500">Customers: <span id="current_period_customers" class="font-medium">0</span></div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium mb-2 text-gray-700">Previous Period</h4>
                        <div class="space-y-1">
                            <div class="text-xs text-gray-500">Sales: <span id="previous_period_sales" class="font-medium">TSH 0</span></div>
                            <div class="text-xs text-gray-500">Profit: <span id="previous_period_profit" class="font-medium">TSH 0</span></div>
                            <div class="text-xs text-gray-500">Customers: <span id="previous_period_customers" class="font-medium">0</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Flow Analysis -->
        <div id="cash_flow_container" class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Cash Flow Analysis</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-green-50 p-4 rounded-lg text-center">
                    <div class="text-sm text-gray-500">Total Inflow</div>
                    <div id="total_inflow" class="text-2xl font-bold text-green-600">TSH 0</div>
                </div>
                <div class="bg-red-50 p-4 rounded-lg text-center">
                    <div class="text-sm text-gray-500">Total Outflow</div>
                    <div id="total_outflow" class="text-2xl font-bold text-red-600">TSH 0</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <div class="text-sm text-gray-500">Net Cash Flow</div>
                    <div id="net_cash_flow" class="text-2xl font-bold text-blue-600">TSH 0</div>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-lg font-medium mb-3">Monthly Cash Flow</h4>
                <div class="h-64">
                    <canvas id="monthly_cash_flow_chart"></canvas>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-medium mb-3">Cash Flow by Payment Method</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Inflow</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Outflow</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net</th>
                            </tr>
                        </thead>
                        <tbody id="payment_methods_table" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Data Script -->
    <script id="dashboard-data" type="application/json">
        {
            "chartDays": @json($chartDays),
            "chartSales": @json($chartSales)
        }
    </script>

    <!-- Replace the script section in your HTML with this updated version -->
    <script>
        $(document).ready(function() {
            // Set default dates
            setDefaultDates();

            // Initialize charts
            initializeSalesChart();

            // Load comprehensive statistics
            loadStatistics();

            // Set up event listeners
            setupEventListeners();

            // Start real-time updates
            startRealTimeUpdates();
        });

        function setDefaultDates() {
            if (!$('#from_Date').val()) {
                const sixMonthsAgo = new Date();
                sixMonthsAgo.setMonth(sixMonthsAgo.getMonth() - 6);
                $('#from_Date').val(sixMonthsAgo.toISOString().split('T')[0]);
            }

            if (!$('#to_Date').val()) {
                const today = new Date();
                $('#to_Date').val(today.toISOString().split('T')[0]);
            }
        }

        function initializeSalesChart() {
            var dashboardData = JSON.parse(document.getElementById('dashboard-data').textContent);

            var salesChartElement = document.getElementById('salesChart');
            if (salesChartElement) {
                var ctx = salesChartElement.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dashboardData.chartDays,
                        datasets: [{
                            label: 'Daily Sales (TSH)',
                            data: dashboardData.chartSales,
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'TSH ' + value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });
            }
        }

        function setupEventListeners() {
            // Toggle sales visibility
            $('#toggleSales').on('click', function() {
                toggleVisibility('#hiddenSales', '#realSales', '#eyeIcon');
            });

            // Toggle revenue visibility
            $('#toggleRevenue').on('click', function() {
                toggleVisibility('#hiddenRevenue', '#realRevenue', '#revenueEyeIcon');
            });

            // Date filter changes
            $('#from_Date, #to_Date').on('change', function() {
                loadStatistics();
            });

            // Refresh button
            $('#refreshStats').on('click', function() {
                loadStatistics();
            });
        }

        function toggleVisibility(hiddenSelector, realSelector, iconSelector) {
            var hidden = $(hiddenSelector);
            var real = $(realSelector);
            var icon = $(iconSelector);

            if (hidden.is(':visible')) {
                hidden.hide();
                real.show();
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                hidden.show();
                real.hide();
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        }

        function loadStatistics() {
            const filters = {
                from_Date: $('#from_Date').val() || '',
                to_Date: $('#to_Date').val() || '',
            };

            $('#loadingIndicator').removeClass('hidden');

            $.ajax({
                type: "GET",
                url: 'getBusinessStatistics',
                data: filters,
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        renderComprehensiveStatistics(response.statistics);
                    }
                    $('#loadingIndicator').addClass('hidden');
                },
                error: function(xhr, status, error) {
                    $('#loadingIndicator').addClass('hidden');
                    console.error('Failed to load statistics:', error);
                }
            });
        }

        function renderComprehensiveStatistics(stats) {
            // Update financial metrics
            updateFinancialMetrics(stats.financial_metrics);

            // Update sales analytics
            updateSalesAnalytics(stats.sales_analytics);

            // Update inventory health
            updateInventoryHealth(stats.inventory_health);

            // Update customer insights
            updateCustomerInsights(stats.customer_insights);

            // Update business KPIs
            updateBusinessKPIs(stats.business_kpis);

            // Update cash flow
            updateCashFlow(stats.cash_flow);
        }

        function updateFinancialMetrics(metrics) {
            $('#fin_revenue').text(formatPrice(metrics.total_revenue || 0));
            $('#fin_gross_profit').text(formatPrice(metrics.gross_profit || 0));
            $('#fin_gross_margin').text(`${metrics.gross_profit_margin || 0}% margin`);
            $('#fin_expenses').text(formatPrice(metrics.total_expenses || 0));
            $('#fin_net_profit').text(formatPrice(metrics.net_profit || 0));
            $('#fin_net_margin').text(`${metrics.net_profit_margin || 0}% margin`);
            $('#fin_receivables').text(formatPrice(metrics.outstanding_receivables || 0));
            $('#fin_payables').text(formatPrice(metrics.outstanding_payables || 0));

            // Render monthly profit chart
            if (metrics.monthly_profits && metrics.monthly_profits.length > 0) {
                renderMonthlyProfitChart(metrics.monthly_profits);
            }
        }

        function updateSalesAnalytics(analytics) {
            $('#total_sales_count').text(analytics.total_sales || 0);
            $('#total_sales_amount').text(formatPrice(analytics.total_sales_amount || 0));
            $('#average_sale_value').text(formatPrice(analytics.average_sale_value || 0));

            // Update best selling products table with display names from backend
            if (analytics.best_selling_items && analytics.best_selling_items.length > 0) {
                let tableHtml = '';
                analytics.best_selling_items.forEach(item => {
                    // Use the display_name from backend - it's already properly formatted
                    let displayName = item.display_name || `Product #${item.id}`;

                    tableHtml += `
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">${displayName}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900">${item.total_quantity}</td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900">${formatPrice(item.total_revenue)}</td>
                        </tr>
                    `;
                });
                $('#best_selling_products_table').html(tableHtml);
            }

            // Render charts
            if (analytics.monthly_sales_trend) {
                renderMonthlySalesChart(analytics.monthly_sales_trend);
            }
            if (analytics.sales_by_day_of_week) {
                renderSalesByDayChart(analytics.sales_by_day_of_week);
            }
        }

        function updateInventoryHealth(inventory) {
            $('#total_inventory_value').text(formatPrice(inventory.total_inventory_value || 0));
            $('#low_stock_count').text(inventory.low_stock_items?.length || 0);
            $('#out_of_stock_count').text(inventory.out_of_stock_items?.length || 0);
            $('#dead_stock_count').text(inventory.dead_stock?.length || 0);

            // Update stock turnover table with display names from backend
            if (inventory.stock_turnover && inventory.stock_turnover.length > 0) {
                let tableHtml = '';
                inventory.stock_turnover.forEach(item => {
                    const rate = parseFloat(item.turnover_rate || 0);
                    const colorClass = rate > 3 ? 'text-green-600' : (rate < 1 ? 'text-red-600' : 'text-yellow-600');

                    // Use the display_name from backend - it's already properly formatted
                    let displayName = item.display_name || item.name || `Product #${item.id}`;

                    tableHtml += `
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-900">${displayName}</td>
                            <td class="px-3 py-2 text-sm text-right ${colorClass} font-medium">${rate.toFixed(2)}</td>
                        </tr>
                    `;
                });
                $('#stock_turnover_table').html(tableHtml);
            }

            // Update slow moving items with display names from backend
            renderSlowMovingItems(inventory.slow_moving_items || []);
        }

        function updateCustomerInsights(insights) {
            $('#total_customers_count').text(insights.total_customers || 0);
            $('#new_customers_count').text(insights.new_customers || 0);
            $('#retention_rate').text(`${insights.retention_rate || 0}%`);
            $('#avg_sale_per_customer').text(formatPrice(insights.average_sale_per_customer || 0));

            // Update top customers table
            if (insights.top_customers && insights.top_customers.length > 0) {
                let tableHtml = '';
                insights.top_customers.forEach(customer => {
                    let customerName = customer.name || `Customer #${customer.id}`;

                    tableHtml += `
                        <tr>
                            <td class="px-3 py-2 text-sm text-gray-900">${customerName}</td>
                            <td class="px-3 py-2 text-sm text-right text-gray-900">${customer.total_orders || 0}</td>
                            <td class="px-3 py-2 text-sm text-right text-gray-900">${formatPrice(customer.total_spent || 0)}</td>
                        </tr>
                    `;
                });
                $('#top_customers_table').html(tableHtml);
            }
        }

        function renderSlowMovingItems(items) {
            if (!items || items.length === 0) {
                $('#slow_moving_table').html('<p class="text-center text-gray-500 py-4">No slow-moving items found</p>');
                return;
            }

            let html = `
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Sold</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
            `;

            items.forEach(item => {
                // Use the name from backend (already properly formatted)
                let displayName = item.name || `Product #${item.id}`;

                html += `
                    <tr>
                        <td class="px-3 py-2 text-sm text-gray-900">${displayName}</td>
                        <td class="px-3 py-2 text-sm text-right text-gray-900">${item.total_sold}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            $('#slow_moving_table').html(html);
        }

        function updateBusinessKPIs(kpis) {
            const salesGrowth = kpis.sales_growth_rate || 0;
            const profitGrowth = kpis.profit_growth_rate || 0;
            const customerGrowth = kpis.customer_growth_rate || 0;

            $('#sales_growth').text(`${salesGrowth > 0 ? '+' : ''}${salesGrowth}%`)
                .removeClass('text-green-600 text-red-600')
                .addClass(salesGrowth >= 0 ? 'text-green-600' : 'text-red-600');

            $('#profit_growth').text(`${profitGrowth > 0 ? '+' : ''}${profitGrowth}%`)
                .removeClass('text-green-600 text-red-600')
                .addClass(profitGrowth >= 0 ? 'text-green-600' : 'text-red-600');

            $('#customer_growth').text(`${customerGrowth > 0 ? '+' : ''}${customerGrowth}%`)
                .removeClass('text-green-600 text-red-600')
                .addClass(customerGrowth >= 0 ? 'text-green-600' : 'text-red-600');

            // Update period data
            if (kpis.current_period) {
                $('#current_period_sales').text(formatPrice(kpis.current_period.sales || 0));
                $('#current_period_profit').text(formatPrice(kpis.current_period.profit || 0));
                $('#current_period_customers').text(kpis.current_period.customer_count || 0);
            }

            if (kpis.previous_period) {
                $('#previous_period_sales').text(formatPrice(kpis.previous_period.sales || 0));
                $('#previous_period_profit').text(formatPrice(kpis.previous_period.profit || 0));
                $('#previous_period_customers').text(kpis.previous_period.customer_count || 0);
            }
        }

        function updateCashFlow(cashFlow) {
            $('#total_inflow').text(formatPrice(cashFlow.totals?.inflow || 0));
            $('#total_outflow').text(formatPrice(cashFlow.totals?.outflow || 0));

            const netFlow = cashFlow.totals?.net || 0;
            $('#net_cash_flow').text(formatPrice(netFlow))
                .removeClass('text-blue-600 text-red-600')
                .addClass(netFlow >= 0 ? 'text-blue-600' : 'text-red-600');

            // Update payment methods table
            if (cashFlow.payment_methods && cashFlow.payment_methods.length > 0) {
                let tableHtml = '';
                cashFlow.payment_methods.forEach(method => {
                    const netAmount = method.net || 0;
                    const netColorClass = netAmount >= 0 ? 'text-blue-600' : 'text-red-600';

                    tableHtml += `
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">${method.method || 'Unknown'}</td>
                            <td class="px-4 py-3 text-sm text-right text-green-600">${formatPrice(method.inflow || 0)}</td>
                            <td class="px-4 py-3 text-sm text-right text-red-600">${formatPrice(method.outflow || 0)}</td>
                            <td class="px-4 py-3 text-sm text-right font-medium ${netColorClass}">${formatPrice(netAmount)}</td>
                        </tr>
                    `;
                });
                $('#payment_methods_table').html(tableHtml);
            }

            // Render monthly cash flow chart
            if (cashFlow.monthly_cash_flow) {
                renderMonthlyCashFlowChart(cashFlow.monthly_cash_flow);
            }
        }

        function startRealTimeUpdates() {
            // Update live users every 30 seconds
            setInterval(function() {
                refreshLiveUsers();
            }, 30000);

            // Activity ping every 2 minutes
            setInterval(function() {
                activityPing();
            }, 120000);
        }

        function refreshLiveUsers() {
            $.ajax({
                url: '/api/dashboard/refresh/live_users',
                method: 'GET',
                success: function(data) {
                    $('#liveUsersCount').text(data.active_count);
                    $('#todayLoginsCount').text(data.total_logins_today);
                },
                error: function(xhr, status, error) {
                    console.error('Error refreshing live users:', error);
                }
            });
        }

        function activityPing() {
            $.ajax({
                url: '/api/dashboard/activity-ping',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log('Activity ping sent successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Activity ping failed:', error);
                }
            });
        }

        function formatPrice(price) {
            return 'TSH ' + new Intl.NumberFormat().format(price);
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

        // Global variables to store chart instances
        let monthlyProfitChart = null;
        let monthlySalesChart = null;
        let salesByDayChart = null;
        let monthlyCashFlowChart = null;

        function renderMonthlyProfitChart(data) {
            // Destroy existing chart if it exists
            if (monthlyProfitChart) {
                monthlyProfitChart.destroy();
                monthlyProfitChart = null;
            }

            const ctx = document.getElementById('monthly_profit_chart').getContext('2d');
            monthlyProfitChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.month),
                    datasets: [{
                        label: 'Revenue',
                        data: data.map(item => item.revenue),
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Revenue Trend'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatPrice(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderMonthlySalesChart(data) {
            // Destroy existing chart if it exists
            if (monthlySalesChart) {
                monthlySalesChart.destroy();
                monthlySalesChart = null;
            }

            const ctx = document.getElementById('monthly_sales_chart').getContext('2d');
            monthlySalesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.month),
                    datasets: [{
                        label: 'Sales Count',
                        data: data.map(item => item.count),
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Sales Count'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderSalesByDayChart(data) {
            // Destroy existing chart if it exists
            if (salesByDayChart) {
                salesByDayChart.destroy();
                salesByDayChart = null;
            }

            const ctx = document.getElementById('sales_by_day_chart').getContext('2d');
            salesByDayChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.day),
                    datasets: [{
                        label: 'Sales Count',
                        data: data.map(item => item.count),
                        backgroundColor: 'rgba(79, 70, 229, 0.6)',
                        borderColor: 'rgb(79, 70, 229)',
                        borderWidth: 1
                    }]
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
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function renderMonthlyCashFlowChart(data) {
            // Destroy existing chart if it exists
            if (monthlyCashFlowChart) {
                monthlyCashFlowChart.destroy();
                monthlyCashFlowChart = null;
            }

            const ctx = document.getElementById('monthly_cash_flow_chart').getContext('2d');
            monthlyCashFlowChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.month),
                    datasets: [{
                        label: 'Inflow',
                        data: data.map(item => item.inflow),
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    }, {
                        label: 'Outflow',
                        data: data.map(item => -item.outflow),
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Cash Flow'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatPrice(Math.abs(value));
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

    <!-- Custom Styles -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }
    </style>
</body>

</html>