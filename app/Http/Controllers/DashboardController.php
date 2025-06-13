<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Part;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\SaleItem;
use App\Models\PurchaseItem;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard
     */
    public function index()
    {
        // Get basic stats for dashboard cards
        $stats = $this->getBasicStats();

        // Get stock alerts
        $stockAlerts = $this->getStockAlerts();

        // Get live users data
        $liveUsers = $this->getLiveUsersData();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get chart data for sales (last 7 days)
        $chartData = $this->getChartData();
        $chartDays = $chartData['chartDays'];
        $chartSales = $chartData['chartSales'];

        return view('dashboard', compact(
            'stats',
            'stockAlerts',
            'liveUsers',
            'recentActivities',
            'chartDays',
            'chartSales'
        ));
    }

    /**
     * Refresh live users for AJAX calls (GET method)
     */
    public function getLiveUsers()
    {
        return response()->json($this->getLiveUsersData());
    }

    /**
     * Get basic dashboard statistics
     */
    private function getBasicStats()
    {
        return [
            'total_products' => Item::count(),
            'total_employees' => User::where('is_active', true)->whereNotNull('role')->count(), // Fixed this line
            'total_sales_today' => Sale::whereDate('sale_date', today())->sum('total_amount'),
            'total_sales_month' => Sale::whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)
                ->sum('total_amount'),
            'profit_month' => $this->calculateMonthlyProfit()
        ];
    }

    /**
     * Calculate monthly profit
     */
    private function calculateMonthlyProfit()
    {
        $monthlyRevenue = Sale::whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->sum('total_amount');

        $monthlyCosts = Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total_amount');

        $monthlyExpenses = Transaction::where('type', 'Payment')
            ->whereNull('purchase_id')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('payment_amount');

        return $monthlyRevenue - $monthlyCosts - $monthlyExpenses;
    }

    /**
     * Get stock alerts
     */
    private function getStockAlerts()
    {
        $currentStock = Item::select('id', 'name', 'sku', 'expiry_date')
            ->withCount(['purchaseItems as total_purchased' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['saleItems as total_sold' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get()
            ->map(function ($item) {
                $stockQuantity = $item->total_purchased - $item->total_sold;
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'stock_quantity' => $stockQuantity,
                    'expiry_date' => $item->expiry_date,
                ];
            });

        return [
            'low_stock' => $currentStock->filter(function ($item) {
                return $item['stock_quantity'] > 0 && $item['stock_quantity'] < 5;
            })->count(),
            'expired' => $currentStock->filter(function ($item) {
                return $item['expiry_date'] && Carbon::parse($item['expiry_date'])->isPast();
            })->count(),
            'expiring_soon' => $currentStock->filter(function ($item) {
                return $item['expiry_date'] &&
                    Carbon::parse($item['expiry_date'])->between(now(), now()->addDays(30));
            })->count(),
        ];
    }

    /**
     * Get live users data
     */
    private function getLiveUsersData()
    {
        $activeUsers = User::where('last_activity', '>=', now()->subMinutes(15))
            ->where('is_active', true)  // Changed from status = 'Active' to is_active = true
            ->whereNotNull('role')      // Only include users with roles
            ->select('id', 'name', 'last_activity')
            ->get();

        $todayLogins = UserSession::whereDate('login_at', today())->count();

        return [
            'active_count' => $activeUsers->count(),
            'total_logins_today' => $todayLogins,
            'active_users' => $activeUsers->toArray()
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        return UserActivity::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'description' => $activity->description,
                    'user' => $activity->user->name ?? 'System',
                    'time' => $activity->created_at->diffForHumans(),
                    'icon' => $this->getActivityIcon($activity->action)
                ];
            })
            ->toArray();
    }

    /**
     * Get activity icon based on action
     */
    private function getActivityIcon($action)
    {
        $icons = [
            'sale' => 'shopping-cart',
            'purchase' => 'truck',
            'payment' => 'credit-card',
            'login' => 'sign-in-alt',
            'logout' => 'sign-out-alt',
            'create' => 'plus',
            'update' => 'edit',
            'delete' => 'trash',
        ];

        return $icons[$action] ?? 'info-circle';
    }

    /**
     * Get chart data for sales
     */
    private function getChartData()
    {
        $chartDays = [];
        $chartSales = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartDays[] = $date->format('M d');

            $dailySales = Sale::whereDate('sale_date', $date)->sum('total_amount');
            $chartSales[] = $dailySales;
        }

        return compact('chartDays', 'chartSales');
    }

    /**
     * API endpoint for dashboard data
     */
    public function getDashboardDataApi()
    {
        return response()->json([
            'stats' => $this->getBasicStats(),
            'stock_alerts' => $this->getStockAlerts(),
            'live_users' => $this->getLiveUsersData(),
            'chart_data' => $this->getChartData()
        ]);
    }

    /**
     * Refresh specific dashboard component
     */
    public function refreshComponent(Request $request, $component)
    {
        switch ($component) {
            case 'stats':
                return response()->json($this->getBasicStats());
            case 'stock_alerts':
                return response()->json($this->getStockAlerts());
            case 'live_users':
                return response()->json($this->getLiveUsersData());
            case 'activities':
                return response()->json($this->getRecentActivities());
            default:
                return response()->json(['error' => 'Component not found'], 404);
        }
    }

    /**
     * Activity ping to keep user active
     */
    public function activityPing()
    {
        if (Auth::check()) {
            // Use DB update instead of model update
            DB::table('users')
                ->where('id', Auth::id())
                ->update(['last_activity' => now()]);

            UserActivity::log('ping', 'User activity ping');

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }

    /**
     * Get notifications
     */
    public function getNotifications()
    {
        $notifications = [];

        // Low stock notifications
        $lowStockItems = Item::select('name')
            ->withCount(['purchaseItems as total_purchased' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['saleItems as total_sold' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get()
            ->filter(function ($item) {
                $stock = $item->total_purchased - $item->total_sold;
                return $stock > 0 && $stock < 5;
            });

        foreach ($lowStockItems as $item) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Low Stock Alert',
                'message' => "Item '{$item->name}' is running low on stock",
                'time' => now()->diffForHumans()
            ];
        }

        // Expiring items notifications
        $expiringItems = Item::where('expiry_date', '<=', now()->addDays(7))
            ->where('expiry_date', '>', now())
            ->get();

        foreach ($expiringItems as $item) {
            $notifications[] = [
                'type' => 'info',
                'title' => 'Expiring Soon',
                'message' => "Item '{$item->name}' expires on " . Carbon::parse($item->expiry_date)->format('M d, Y'),
                'time' => now()->diffForHumans()
            ];
        }

        return response()->json($notifications);
    }

    /**
     * Refresh dashboard stats for AJAX calls
     */
    public function refreshStats()
    {
        return response()->json($this->getBasicStats());
    }

    /**
     * Refresh live users for AJAX calls
     */
    public function refreshLiveUsers()
    {
        return response()->json($this->getLiveUsersData());
    }

    /**
     * Get comprehensive business statistics
     */
    public function getBusinessStatistics(Request $request)
    {
        // Parse date filters
        $fromDate = $request->input('from_Date') ? Carbon::parse($request->input('from_Date')) : Carbon::now()->subMonths(6);
        $toDate = $request->input('to_Date') ? Carbon::parse($request->input('to_Date')) : Carbon::now();

        // Start collecting all statistics
        $statistics = [
            // Financial metrics
            'financial_metrics' => $this->getFinancialMetrics($fromDate, $toDate),

            // Sales analytics
            'sales_analytics' => $this->getSalesAnalytics($fromDate, $toDate),

            // Purchase analytics
            'purchase_analytics' => $this->getPurchaseAnalytics($fromDate, $toDate),

            // Inventory health
            'inventory_health' => $this->getInventoryHealth($fromDate, $toDate),

            // Customer insights
            'customer_insights' => $this->getCustomerInsights($fromDate, $toDate),

            // Supplier performance
            'supplier_performance' => $this->getSupplierPerformance($fromDate, $toDate),

            // Product performance
            'product_performance' => $this->getProductPerformance($fromDate, $toDate),

            // Business performance KPIs
            'business_kpis' => $this->getBusinessKPIs($fromDate, $toDate),

            // Cash flow analysis
            'cash_flow' => $this->getCashFlowAnalysis($fromDate, $toDate),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $statistics
        ]);
    }

    /**
     * Get financial metrics
     */
    private function getFinancialMetrics($fromDate, $toDate)
    {
        // Total revenue in date range
        $totalRevenue = Sale::whereBetween('sale_date', [$fromDate, $toDate])
            ->sum('total_amount');

        // Total costs/purchases in date range
        $totalCost = Purchase::whereBetween('purchase_date', [$fromDate, $toDate])
            ->sum('total_amount');

        // Calculate gross profit
        $grossProfit = $totalRevenue - $totalCost;

        // Gross profit margin
        $grossProfitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        // Total expenses (from transactions marked as Payment without a purchase_id)
        $totalExpenses = Transaction::where('type', 'Payment')
            ->whereNull('purchase_id')
            ->whereBetween('transaction_date', [$fromDate, $toDate])
            ->sum('payment_amount');

        // Net profit (gross profit minus expenses)
        $netProfit = $grossProfit - $totalExpenses;

        // Net profit margin
        $netProfitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        // Monthly profit trends
        $monthlyProfits = Sale::selectRaw('
                YEAR(sale_date) as year, 
                MONTH(sale_date) as month, 
                SUM(total_amount) as revenue,
                SUM(total_discount) as discounts
            ')
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                // Format month name
                $monthName = Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');

                return [
                    'month' => $monthName,
                    'revenue' => $item->revenue,
                    'discounts' => $item->discounts,
                ];
            });

        // Outstanding receivables (customer debt)
        $outstandingReceivables = Sale::where('status', '!=', 'Paid')
            ->where('status', '!=', 'Cancelled')
            ->sum('dept');

        // Outstanding payables (supplier debt)
        $outstandingPayables = Purchase::where('status', '!=', 'Paid')
            ->where('status', '!=', 'Cancelled')
            ->sum('dept');

        // Return all financial metrics
        return [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'gross_profit' => $grossProfit,
            'gross_profit_margin' => round($grossProfitMargin, 2),
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'net_profit_margin' => round($netProfitMargin, 2),
            'monthly_profits' => $monthlyProfits,
            'outstanding_receivables' => $outstandingReceivables,
            'outstanding_payables' => $outstandingPayables,
        ];
    }

    /**
     * Get purchase analytics
     */
    private function getPurchaseAnalytics($fromDate, $toDate)
    {
        // Total number of purchases
        $totalPurchases = Purchase::whereBetween('purchase_date', [$fromDate, $toDate])->count();

        // Total purchase amount
        $totalPurchaseAmount = Purchase::whereBetween('purchase_date', [$fromDate, $toDate])->sum('total_amount');

        // Average purchase value
        $averagePurchaseValue = $totalPurchases > 0 ? $totalPurchaseAmount / $totalPurchases : 0;

        // Purchases by payment status
        $purchasesByStatus = Purchase::selectRaw('status, COUNT(*) as count, SUM(total_amount) as amount')
            ->whereBetween('purchase_date', [$fromDate, $toDate])
            ->groupBy('status')
            ->get();

        // Monthly purchase trend
        $monthlyPurchaseTrend = Purchase::selectRaw('
                DATE_FORMAT(purchase_date, "%Y-%m") as month,
                COUNT(*) as count,
                SUM(total_amount) as amount,
                SUM(total_discount) as discounts
            ')
            ->whereBetween('purchase_date', [$fromDate, $toDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'count' => $item->count,
                    'amount' => $item->amount,
                    'discounts' => $item->discounts,
                ];
            });

        // Most purchased items
        $mostPurchasedItems = DB::table('purchase_items')
            ->join('items', 'purchase_items.item_id', '=', 'items.id')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->whereBetween('purchases.purchase_date', [$fromDate, $toDate])
            ->select(
                'items.id',
                'items.name',
                'items.sku',
                DB::raw('SUM(purchase_items.quantity) as total_quantity'),
                DB::raw('SUM(purchase_items.quantity * purchase_items.purchase_price) as total_cost')
            )
            ->groupBy('items.id', 'items.name', 'items.sku')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Return all purchase analytics
        return [
            'total_purchases' => $totalPurchases,
            'total_purchase_amount' => $totalPurchaseAmount,
            'average_purchase_value' => round($averagePurchaseValue, 2),
            'purchases_by_status' => $purchasesByStatus,
            'monthly_purchase_trend' => $monthlyPurchaseTrend,
            'most_purchased_items' => $mostPurchasedItems,
        ];
    }

    /**
     * Get customer insights
     */
    private function getCustomerInsights($fromDate, $toDate)
    {
        // Top customers by revenue
        $topCustomers = DB::table('sales')
            ->join('parts', 'sales.part_id', '=', 'parts.id')
            ->whereBetween('sales.sale_date', [$fromDate, $toDate])
            ->where('parts.type', 'Customer')
            ->select(
                'parts.id',
                'parts.name',
                DB::raw('COUNT(sales.id) as total_orders'),
                DB::raw('SUM(sales.total_amount) as total_spent')
            )
            ->groupBy('parts.id', 'parts.name')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        // New customers in date range
        $newCustomers = DB::table('parts')
            ->where('type', 'Customer')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        // Customer retention rate (customers who made repeat purchases)
        $repeatCustomers = DB::table('sales')
            ->join('parts', 'sales.part_id', '=', 'parts.id')
            ->where('parts.type', 'Customer')
            ->whereBetween('sales.sale_date', [$fromDate, $toDate])
            ->select('parts.id', DB::raw('COUNT(sales.id) as purchase_count'))
            ->groupBy('parts.id')
            ->having('purchase_count', '>', 1)
            ->count();

        $totalCustomers = DB::table('sales')
            ->join('parts', 'sales.part_id', '=', 'parts.id')
            ->where('parts.type', 'Customer')
            ->whereBetween('sales.sale_date', [$fromDate, $toDate])
            ->select('parts.id')
            ->distinct()
            ->count();

        $retentionRate = $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;

        // Customer debt statistics
        $customerDebt = Sale::where('sales.status', '!=', 'Paid')
            ->where('sales.status', '!=', 'Cancelled')
            ->whereNotNull('sales.part_id')
            ->join('parts', 'sales.part_id', '=', 'parts.id')
            ->where('parts.type', 'Customer')
            ->select(
                'parts.id',
                'parts.name',
                DB::raw('SUM(sales.dept) as total_debt'),
                DB::raw('COUNT(sales.id) as unpaid_orders')
            )
            ->groupBy('parts.id', 'parts.name')
            ->orderBy('total_debt', 'desc')
            ->limit(10)
            ->get();

        // Average sale per customer
        $avgSalePerCustomer = $totalCustomers > 0
            ? Sale::whereBetween('sale_date', [$fromDate, $toDate])
            ->whereNotNull('part_id')
            ->sum('total_amount') / $totalCustomers
            : 0;

        // Return customer insights
        return [
            'top_customers' => $topCustomers,
            'new_customers' => $newCustomers,
            'repeat_customers' => $repeatCustomers,
            'total_customers' => $totalCustomers,
            'retention_rate' => round($retentionRate, 2),
            'customer_debt' => $customerDebt,
            'average_sale_per_customer' => round($avgSalePerCustomer, 2),
        ];
    }

    /**
     * Get supplier performance
     */
    private function getSupplierPerformance($fromDate, $toDate)
    {
        // Top suppliers by purchase amount
        $topSuppliers = DB::table('purchases')
            ->join('parts', 'purchases.part_id', '=', 'parts.id')
            ->whereBetween('purchases.purchase_date', [$fromDate, $toDate])
            ->where('parts.type', 'Supplier')
            ->select(
                'parts.id',
                'parts.name',
                DB::raw('COUNT(purchases.id) as total_orders'),
                DB::raw('SUM(purchases.total_amount) as total_amount')
            )
            ->groupBy('parts.id', 'parts.name')
            ->orderBy('total_amount', 'desc')
            ->limit(10)
            ->get();

        // Supplier debt
        $supplierDebt = Purchase::where('purchases.status', '!=', 'Paid')
            ->where('purchases.status', '!=', 'Cancelled')
            ->whereNotNull('purchases.part_id')
            ->join('parts', 'purchases.part_id', '=', 'parts.id')
            ->where('parts.type', 'Supplier')
            ->select(
                'parts.id',
                'parts.name',
                DB::raw('SUM(purchases.dept) as total_debt'),
                DB::raw('COUNT(purchases.id) as unpaid_orders')
            )
            ->groupBy('parts.id', 'parts.name')
            ->orderBy('total_debt', 'desc')
            ->limit(10)
            ->get();

        // Average items per purchase by supplier
        $avgItemsPerPurchase = DB::table('purchases')
            ->join('parts', 'purchases.part_id', '=', 'parts.id')
            ->join('purchase_items', 'purchases.id', '=', 'purchase_items.purchase_id')
            ->whereBetween('purchases.purchase_date', [$fromDate, $toDate])
            ->where('parts.type', 'Supplier')
            ->select(
                'parts.id',
                'parts.name',
                DB::raw('COUNT(purchase_items.id) as items_count'),
                DB::raw('COUNT(DISTINCT purchases.id) as purchase_count'),
                DB::raw('COUNT(purchase_items.id) / COUNT(DISTINCT purchases.id) as avg_items')
            )
            ->groupBy('parts.id', 'parts.name')
            ->orderBy('avg_items', 'desc')
            ->limit(10)
            ->get();

        // Return supplier insights
        return [
            'top_suppliers' => $topSuppliers,
            'supplier_debt' => $supplierDebt,
            'avg_items_per_purchase' => $avgItemsPerPurchase,
        ];
    }

    /**
     * Get business KPIs
     */
    private function getBusinessKPIs($fromDate, $toDate)
    {
        // Calculate the previous period for comparison
        $periodLength = $fromDate->diffInDays($toDate);
        $previousFromDate = $fromDate->copy()->subDays($periodLength);
        $previousToDate = $fromDate->copy()->subDay();

        // Current period sales
        $currentSales = Sale::whereBetween('sale_date', [$fromDate, $toDate])->sum('total_amount');
        // Previous period sales
        $previousSales = Sale::whereBetween('sale_date', [$previousFromDate, $previousToDate])->sum('total_amount');
        // Sales growth rate
        $salesGrowthRate = $previousSales > 0 ? (($currentSales - $previousSales) / $previousSales) * 100 : 0;

        // Current period purchases
        $currentPurchases = Purchase::whereBetween('purchase_date', [$fromDate, $toDate])->sum('total_amount');
        // Previous period purchases
        $previousPurchases = Purchase::whereBetween('purchase_date', [$previousFromDate, $previousToDate])->sum('total_amount');
        // Purchase growth rate
        $purchaseGrowthRate = $previousPurchases > 0 ? (($currentPurchases - $previousPurchases) / $previousPurchases) * 100 : 0;

        // Current period profit
        $currentProfit = $currentSales - $currentPurchases;
        // Previous period profit
        $previousProfit = $previousSales - $previousPurchases;
        // Profit growth rate
        $profitGrowthRate = $previousProfit > 0 ? (($currentProfit - $previousProfit) / $previousProfit) * 100 : 0;

        // Current customer count
        $currentCustomerCount = DB::table('sales')
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->whereNotNull('part_id')
            ->select('part_id')
            ->distinct()
            ->count();

        // Previous customer count
        $previousCustomerCount = DB::table('sales')
            ->whereBetween('sale_date', [$previousFromDate, $previousToDate])
            ->whereNotNull('part_id')
            ->select('part_id')
            ->distinct()
            ->count();

        // Customer growth rate
        $customerGrowthRate = $previousCustomerCount > 0
            ? (($currentCustomerCount - $previousCustomerCount) / $previousCustomerCount) * 100
            : 0;

        // Average order value
        $avgOrderValue = Sale::whereBetween('sale_date', [$fromDate, $toDate])->count() > 0
            ? Sale::whereBetween('sale_date', [$fromDate, $toDate])->avg('total_amount')
            : 0;

        // Previous avg order value
        $prevAvgOrderValue = Sale::whereBetween('sale_date', [$previousFromDate, $previousToDate])->count() > 0
            ? Sale::whereBetween('sale_date', [$previousFromDate, $previousToDate])->avg('total_amount')
            : 0;

        // Avg order value growth
        $avgOrderValueGrowth = $prevAvgOrderValue > 0
            ? (($avgOrderValue - $prevAvgOrderValue) / $prevAvgOrderValue) * 100
            : 0;

        // Return business KPIs
        return [
            'sales_growth_rate' => round($salesGrowthRate, 2),
            'purchase_growth_rate' => round($purchaseGrowthRate, 2),
            'profit_growth_rate' => round($profitGrowthRate, 2),
            'customer_growth_rate' => round($customerGrowthRate, 2),
            'avg_order_value' => round($avgOrderValue, 2),
            'avg_order_value_growth' => round($avgOrderValueGrowth, 2),
            'current_period' => [
                'from' => $fromDate->format('Y-m-d'),
                'to' => $toDate->format('Y-m-d'),
                'sales' => $currentSales,
                'purchases' => $currentPurchases,
                'profit' => $currentProfit,
                'customer_count' => $currentCustomerCount,
            ],
            'previous_period' => [
                'from' => $previousFromDate->format('Y-m-d'),
                'to' => $previousToDate->format('Y-m-d'),
                'sales' => $previousSales,
                'purchases' => $previousPurchases,
                'profit' => $previousProfit,
                'customer_count' => $previousCustomerCount,
            ],
        ];
    }

    /**
     * Get cash flow analysis
     */
    private function getCashFlowAnalysis($fromDate, $toDate)
    {
        // Get monthly transaction data
        $monthlyTransactions = Transaction::selectRaw('
                DATE_FORMAT(transaction_date, "%Y-%m") as month,
                type,
                SUM(payment_amount) as amount
            ')
            ->whereBetween('transaction_date', [$fromDate, $toDate])
            ->groupBy('month', 'type')
            ->orderBy('month')
            ->get();

        // Format into inflow/outflow by month
        $cashFlow = [];
        foreach ($monthlyTransactions as $transaction) {
            if (!isset($cashFlow[$transaction->month])) {
                $cashFlow[$transaction->month] = [
                    'month' => $transaction->month,
                    'inflow' => 0,
                    'outflow' => 0,
                    'net' => 0
                ];
            }

            if ($transaction->type === 'Receipt') {
                $cashFlow[$transaction->month]['inflow'] += $transaction->amount;
            } else {
                $cashFlow[$transaction->month]['outflow'] += $transaction->amount;
            }

            $cashFlow[$transaction->month]['net'] =
                $cashFlow[$transaction->month]['inflow'] - $cashFlow[$transaction->month]['outflow'];
        }

        // Convert to array and calculate totals
        $cashFlow = array_values($cashFlow);

        $totals = [
            'inflow' => array_sum(array_column($cashFlow, 'inflow')),
            'outflow' => array_sum(array_column($cashFlow, 'outflow')),
            'net' => array_sum(array_column($cashFlow, 'net')),
        ];

        // Cash flow by payment method
        $paymentMethods = Transaction::selectRaw('
                method,
                SUM(CASE WHEN type = "Receipt" THEN payment_amount ELSE 0 END) as inflow,
                SUM(CASE WHEN type = "Payment" THEN payment_amount ELSE 0 END) as outflow,
                SUM(CASE WHEN type = "Receipt" THEN payment_amount ELSE -payment_amount END) as net
            ')
            ->whereBetween('transaction_date', [$fromDate, $toDate])
            ->groupBy('method')
            ->orderBy('net', 'desc')
            ->get();

        // Return cash flow analysis
        return [
            'monthly_cash_flow' => $cashFlow,
            'totals' => $totals,
            'payment_methods' => $paymentMethods,
        ];
    }

    /**
     * Get item statistics (original method from your code)
     */
    public function getItemsStatistics(Request $request)
    {
        // Parse date filters
        $fromDate = $request->input('from_Date') ? Carbon::parse($request->input('from_Date')) : null;
        $toDate = $request->input('to_Date') ? Carbon::parse($request->input('to_Date')) : null;

        // Build query with date filters if provided
        $saleItemsQuery = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id');

        if ($fromDate) {
            $saleItemsQuery->where('sales.sale_date', '>=', $fromDate);
        }

        if ($toDate) {
            $saleItemsQuery->where('sales.sale_date', '<=', $toDate);
        }

        // Best-Selling Items - Top 5 items with highest sales
        $bestSellingItems = $saleItemsQuery->clone()
            ->select('item_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * sale_price) as total_revenue'))
            ->groupBy('item_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Total Revenue based on date range
        $totalRevenueQuery = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id');

        if ($fromDate) {
            $totalRevenueQuery->where('sales.sale_date', '>=', $fromDate);
        }

        if ($toDate) {
            $totalRevenueQuery->where('sales.sale_date', '<=', $toDate);
        }

        $totalRevenue = $totalRevenueQuery->sum(DB::raw('sale_items.quantity * sale_items.sale_price'));

        return response()->json([
            'success' => true,
            'statistics' => [
                'best_selling_items' => $bestSellingItems,
                'total_revenue' => $totalRevenue,
            ]
        ]);
    }


    private function getSalesAnalytics($fromDate, $toDate)
    {
        // Total number of sales
        $totalSales = Sale::whereBetween('sale_date', [$fromDate, $toDate])->count();

        // Total sales amount
        $totalSalesAmount = Sale::whereBetween('sale_date', [$fromDate, $toDate])->sum('total_amount');

        // Average sale value
        $averageSaleValue = $totalSales > 0 ? $totalSalesAmount / $totalSales : 0;

        // Sales by payment status
        $salesByStatus = Sale::selectRaw('status, COUNT(*) as count, SUM(total_amount) as amount')
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->groupBy('status')
            ->get();

        // Monthly sales trend
        $monthlySalesTrend = Sale::selectRaw('
                DATE_FORMAT(sale_date, "%Y-%m") as month,
                COUNT(*) as count,
                SUM(total_amount) as amount,
                SUM(total_discount) as discounts
            ')
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'count' => $item->count,
                    'amount' => $item->amount,
                    'discounts' => $item->discounts,
                ];
            });

        // Sales by day of week
        $salesByDayOfWeek = Sale::selectRaw('
                DAYOFWEEK(sale_date) as day_number,
                COUNT(*) as count,
                SUM(total_amount) as amount
            ')
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->groupBy('day_number')
            ->orderBy('day_number')
            ->get()
            ->map(function ($item) {
                $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                return [
                    'day' => $days[$item->day_number - 1],
                    'count' => $item->count,
                    'amount' => $item->amount,
                ];
            });

        // Best selling items with proper formatting
        $bestSellingItems = $this->getBestSellingItems($fromDate, $toDate);

        // Items with highest revenue
        $highestRevenueItems = $this->getHighestRevenueItems($fromDate, $toDate);

        // Return all sales analytics
        return [
            'total_sales' => $totalSales,
            'total_sales_amount' => $totalSalesAmount,
            'average_sale_value' => round($averageSaleValue, 2),
            'sales_by_status' => $salesByStatus,
            'monthly_sales_trend' => $monthlySalesTrend,
            'sales_by_day_of_week' => $salesByDayOfWeek,
            'best_selling_items' => $bestSellingItems,
            'highest_revenue_items' => $highestRevenueItems,
        ];
    }

    /**
     * Get best selling items with proper name formatting
     */
    private function getBestSellingItems($fromDate, $toDate)
    {
        // Get raw data with item IDs and quantities
        $rawData = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$fromDate, $toDate])
            ->select(
                'sale_items.item_id',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.sale_price) as total_revenue')
            )
            ->groupBy('sale_items.item_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Get items with relationships
        $itemIds = $rawData->pluck('item_id');
        $items = Item::with(['brand', 'mattressType', 'mattressSize'])
            ->whereIn('id', $itemIds)
            ->get()
            ->keyBy('id');

        // Format the results
        return $rawData->map(function ($data) use ($items) {
            $item = $items[$data->item_id];

            return [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'is_mattress' => $item->is_mattress,
                'brand_name' => $item->brand?->name,
                'type_name' => $item->mattressType?->name,
                'size_code' => $item->mattressSize?->size_code,
                'total_quantity' => $data->total_quantity,
                'total_revenue' => $data->total_revenue,
                'display_name' => $this->getItemDisplayName($item)
            ];
        });
    }

    /**
     * Get highest revenue items with proper name formatting
     */
    private function getHighestRevenueItems($fromDate, $toDate)
    {
        // Get raw data with item IDs and revenue
        $rawData = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$fromDate, $toDate])
            ->select(
                'sale_items.item_id',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.sale_price) as total_revenue')
            )
            ->groupBy('sale_items.item_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        // Get items with relationships
        $itemIds = $rawData->pluck('item_id');
        $items = Item::with(['brand', 'mattressType', 'mattressSize'])
            ->whereIn('id', $itemIds)
            ->get()
            ->keyBy('id');

        // Format the results
        return $rawData->map(function ($data) use ($items) {
            $item = $items[$data->item_id];

            return [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'is_mattress' => $item->is_mattress,
                'brand_name' => $item->brand?->name,
                'type_name' => $item->mattressType?->name,
                'size_code' => $item->mattressSize?->size_code,
                'total_quantity' => $data->total_quantity,
                'total_revenue' => $data->total_revenue,
                'display_name' => $this->getItemDisplayName($item)
            ];
        });
    }

    /**
     * Get inventory health
     */
    private function getInventoryHealth($fromDate, $toDate)
    {
        // Current stock levels
        $currentStock = Item::select('id', 'name', 'sku', 'sale_price', 'status')
            ->withCount(['purchaseItems as total_purchased' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->withCount(['saleItems as total_sold' => function ($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'sale_price' => $item->sale_price,
                    'status' => $item->status,
                    'stock_quantity' => $item->total_purchased - $item->total_sold,
                    'stock_value' => ($item->total_purchased - $item->total_sold) * $item->sale_price,
                ];
            });

        // Out of stock items
        $outOfStockItems = $currentStock->filter(function ($item) {
            return $item['stock_quantity'] <= 0;
        })->values();

        // Low stock items (less than 5 items)
        $lowStockItems = $currentStock->filter(function ($item) {
            return $item['stock_quantity'] > 0 && $item['stock_quantity'] < 5;
        })->values();

        // Slow-moving items (less than 3 sales in the last 30 days)
        $slowMovingItems = $this->getSlowMovingItems();

        // Dead stock (items not sold in the last 90 days but have stock)
        $deadStock = $this->getDeadStock();

        // Stock turnover with proper name formatting
        $stockTurnover = $this->getStockTurnover($fromDate, $toDate);

        // Return inventory health data
        return [
            'current_stock' => $currentStock,
            'out_of_stock_items' => $outOfStockItems,
            'low_stock_items' => $lowStockItems,
            'slow_moving_items' => $slowMovingItems,
            'dead_stock' => $deadStock,
            'stock_turnover' => $stockTurnover,
            'total_inventory_value' => $currentStock->sum('stock_value'),
        ];
    }

    /**
     * Get stock turnover with proper name formatting
     */
    private function getStockTurnover($fromDate, $toDate)
    {
        // Get raw turnover data
        $rawData = DB::table('items')
            ->select('items.id')
            ->selectRaw('(
                SELECT SUM(sale_items.quantity) 
                FROM sale_items 
                JOIN sales ON sale_items.sale_id = sales.id
                WHERE sale_items.item_id = items.id 
                AND sales.sale_date BETWEEN ? AND ?
            ) as sold_quantity', [$fromDate, $toDate])
            ->selectRaw('(
                (SELECT SUM(pi.quantity) FROM purchase_items pi WHERE pi.item_id = items.id) - 
                (SELECT COALESCE(SUM(si.quantity), 0) FROM sale_items si WHERE si.item_id = items.id)
            ) as avg_inventory')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sale_items')
                    ->whereRaw('sale_items.item_id = items.id');
            })
            ->get();

        // Get items with relationships
        $itemIds = $rawData->pluck('id');
        $items = Item::with(['brand', 'mattressType', 'mattressSize'])
            ->whereIn('id', $itemIds)
            ->get()
            ->keyBy('id');

        // Format the results
        return $rawData->map(function ($data) use ($items) {
            $item = $items[$data->id];
            $turnoverRate = $data->avg_inventory > 0 ? $data->sold_quantity / $data->avg_inventory : 0;

            return [
                'id' => $item->id,
                'name' => $this->getItemDisplayName($item),
                'sku' => $item->sku,
                'sold_quantity' => $data->sold_quantity,
                'avg_inventory' => $data->avg_inventory,
                'turnover_rate' => round($turnoverRate, 2),
                'is_mattress' => $item->is_mattress,
                'brand_name' => $item->brand?->name,
                'type_name' => $item->mattressType?->name,
                'size_code' => $item->mattressSize?->size_code,
                'display_name' => $this->getItemDisplayName($item)
            ];
        })
            ->sortByDesc('turnover_rate')
            ->take(10)
            ->values();
    }

    /**
     * Get slow moving items with proper name formatting
     */
    private function getSlowMovingItems()
    {
        $rawData = DB::table('items')
            ->leftJoin('sale_items', function ($join) {
                $join->on('items.id', '=', 'sale_items.item_id')
                    ->where('sale_items.created_at', '>=', Carbon::now()->subDays(30));
            })
            ->select(
                'items.id',
                DB::raw('COALESCE(SUM(sale_items.quantity), 0) as total_sold')
            )
            ->groupBy('items.id')
            ->having('total_sold', '<', 3)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('purchase_items')
                    ->whereRaw('purchase_items.item_id = items.id');
            })
            ->limit(20)
            ->get();

        // Get items with relationships
        $itemIds = $rawData->pluck('id');
        $items = Item::with(['brand', 'mattressType', 'mattressSize'])
            ->whereIn('id', $itemIds)
            ->get()
            ->keyBy('id');

        return $rawData->map(function ($data) use ($items) {
            $item = $items[$data->id];
            return [
                'id' => $item->id,
                'name' => $this->getItemDisplayName($item),
                'sku' => $item->sku,
                'total_sold' => $data->total_sold
            ];
        });
    }

    /**
     * Get dead stock items with proper name formatting
     */
    private function getDeadStock()
    {
        $rawData = DB::table('items')
            ->select('items.id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sale_items')
                    ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                    ->whereRaw('sale_items.item_id = items.id')
                    ->where('sales.sale_date', '>=', Carbon::now()->subDays(90));
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('purchase_items')
                    ->whereRaw('purchase_items.item_id = items.id');
            })
            ->whereRaw('(SELECT SUM(purchase_items.quantity) FROM purchase_items WHERE purchase_items.item_id = items.id) > 
                    COALESCE((SELECT SUM(sale_items.quantity) FROM sale_items WHERE sale_items.item_id = items.id), 0)')
            ->limit(20)
            ->get();

        // Get items with relationships
        $itemIds = $rawData->pluck('id');
        $items = Item::with(['brand', 'mattressType', 'mattressSize'])
            ->whereIn('id', $itemIds)
            ->get()
            ->keyBy('id');

        return $rawData->map(function ($data) use ($items) {
            $item = $items[$data->id];
            return [
                'id' => $item->id,
                'name' => $this->getItemDisplayName($item),
                'sku' => $item->sku
            ];
        });
    }

    /**
     * Get product performance
     */
    private function getProductPerformance($fromDate, $toDate)
    {
        // Best selling items with proper formatting
        $bestSellingItems = $this->getBestSellingItems($fromDate, $toDate);

        // Highest margin items
        $highestMarginItems = $this->getHighestMarginItems();

        // Discount impact
        $discountImpact = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$fromDate, $toDate])
            ->select(
                DB::raw('SUM(CASE WHEN sale_items.discount > 0 THEN sale_items.quantity * sale_items.sale_price ELSE 0 END) as with_discount'),
                DB::raw('SUM(CASE WHEN sale_items.discount = 0 OR sale_items.discount IS NULL THEN sale_items.quantity * sale_items.sale_price ELSE 0 END) as without_discount'),
                DB::raw('SUM(sale_items.discount) as total_discount_amount')
            )
            ->first();

        // Return product performance data
        return [
            'best_selling_items' => $bestSellingItems,
            'highest_margin_items' => $highestMarginItems,
            'discount_impact' => $discountImpact,
        ];
    }

    /**
     * Get highest margin items with proper name formatting
     */
    private function getHighestMarginItems()
    {
        $items = Item::with(['brand', 'mattressType', 'mattressSize'])
            ->select('items.*')
            ->selectRaw('(
                SELECT AVG(purchase_price) 
                FROM purchase_items 
                WHERE purchase_items.item_id = items.id
            ) as avg_purchase_price')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('purchase_items')
                    ->whereRaw('purchase_items.item_id = items.id');
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $this->getItemDisplayName($item),
                    'sku' => $item->sku,
                    'sale_price' => $item->sale_price,
                    'avg_purchase_price' => $item->avg_purchase_price,
                    'profit_margin' => $item->sale_price - $item->avg_purchase_price,
                    'profit_percentage' => $item->avg_purchase_price > 0
                        ? (($item->sale_price - $item->avg_purchase_price) / $item->avg_purchase_price) * 100
                        : 0,
                    'is_mattress' => $item->is_mattress,
                    'brand_name' => $item->brand?->name,
                    'type_name' => $item->mattressType?->name,
                    'size_code' => $item->mattressSize?->size_code,
                    'display_name' => $this->getItemDisplayName($item)
                ];
            })
            ->sortByDesc('profit_percentage')
            ->take(10)
            ->values();

        return $items;
    }

    /**
     * Get the display name for an item (mattress or regular item)
     */
    private function getItemDisplayName($item)
    {
        if (!$item->is_mattress) {
            // For non-mattress items, return the name or a fallback
            return $item->name ?: "Product #{$item->id}";
        }

        // For mattress items, combine brand, type, and size
        $nameParts = [];

        if ($item->brand && $item->brand->name) {
            $nameParts[] = $item->brand->name;
        }

        if ($item->mattressType && $item->mattressType->name) {
            $nameParts[] = $item->mattressType->name;
        }

        if ($item->mattressSize && $item->mattressSize->size_code) {
            $nameParts[] = $item->mattressSize->size_code;
        }

        // If we have parts, join them; otherwise use fallback
        return !empty($nameParts) ? implode(' ', $nameParts) : "Mattress #{$item->id}";
    }

    // ... [Keep all your other existing methods] ...
}
