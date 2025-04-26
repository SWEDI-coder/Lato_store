<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Record payment out (for suppliers or other expenses)
    public function record_payment_out(Request $request)
    {
        // First, determine if this is a part payment or other payment
        $isPartPayment = $request->has('part_id') && $request->filled('part_id');
        $isOtherPayment = $request->has('person_name') && $request->filled('person_name');

        // Get the payment type (Payment or Receipt)
        $paymentType = $request->input('type', 'Payment');

        // Apply validation based on the type of payment
        if ($isPartPayment) {
            $request->validate([
                'transaction_date' => 'required|date',
                'method' => 'required|string',
                'journal_memo' => 'required|string',
                'part_id' => 'required|numeric',
                'part_type' => 'required|string|in:Supplier',
                'transactions_data' => 'required|string',
                'dept_paid' => 'required|numeric|min:0',
                'type' => 'required|string|in:Payment,Receipt'
            ]);
        } else if ($isOtherPayment) {
            $request->validate([
                'transaction_date' => 'required|date',
                'method' => 'required|string',
                'journal_memo' => 'required|string',
                'person_name' => 'required|string',
                'payment_amount' => 'required|numeric|min:0',
                'type' => 'required|string|in:Payment,Receipt'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment data. Please provide either part payment or other payment details.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            if ($isPartPayment && $request->part_type === 'Supplier') {
                $purchaseData = json_decode($request->transactions_data, true);
                $createdTransactions = [];

                foreach ($purchaseData as $purchase) {
                    $purchaseModel = Purchase::findOrFail($purchase['id']);

                    $oldDept = $purchaseModel->dept;
                    $newDept = $purchase['newBalance'];
                    $paidAmount = $oldDept - $newDept;

                    if ($paidAmount > 0) {
                        // Use appropriate reference based on payment type
                        $prefix = $paymentType === 'Payment' ? 'PAY-' : 'RCV-';
                        $reference_no = $prefix . time() . rand(100, 999) . '-' . $purchase['id'];

                        $transaction = new Transaction([
                            'reference_no' => $reference_no,
                            'transaction_date' => $request->transaction_date,
                            'method' => $request->method,
                            'journal_memo' => $request->journal_memo,
                            'part_id' => $request->part_id,
                            'purchase_id' => $purchase['id'],
                            'payment_amount' => $paidAmount,
                            'type' => $paymentType,
                        ]);

                        $transaction->save();
                        $createdTransactions[] = $transaction->id;

                        $purchaseModel->update([
                            'dept' => $newDept,
                            'paid' => $purchaseModel->total_amount - $newDept,
                            'status' => $newDept == 0 ? 'Paid' : 'Partial paid'
                        ]);
                    }
                }
            } else {
                // Use appropriate reference based on payment type
                $prefix = $paymentType === 'Payment' ? 'EXP-' : 'INC-';
                $reference_no = $prefix . time() . rand(100, 999);

                $transaction = new Transaction([
                    'reference_no' => $reference_no,
                    'transaction_date' => $request->transaction_date,
                    'method' => $request->method,
                    'journal_memo' => $request->journal_memo,
                    'person_name' => $request->person_name,
                    'type' => $paymentType,
                    'payment_amount' => $request->payment_amount
                ]);

                $transaction->save();
                $createdTransactions = [$transaction->id];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction(s) recorded successfully',
                'transaction_ids' => $createdTransactions
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    // Record payment in (for customers or other income)
    public function record_payment_in(Request $request)
    {
        // First, determine if this is a part payment or other payment
        $isPartPayment = $request->has('part_id') && $request->filled('part_id');
        $isOtherPayment = $request->has('person_name') && $request->filled('person_name');

        // Get the payment type (Payment or Receipt)
        $paymentType = $request->input('type', 'Receipt');

        // Apply validation based on the type of payment
        if ($isPartPayment) {
            $request->validate([
                'transaction_date' => 'required|date',
                'method' => 'required|string',
                'journal_memo' => 'required|string',
                'part_id' => 'required|numeric',
                'part_type' => 'required|string|in:Customer',
                'transactions_data' => 'required|string',
                'dept_paid' => 'required|numeric|min:0',
                'type' => 'required|string|in:Payment,Receipt'
            ]);
        } else if ($isOtherPayment) {
            $request->validate([
                'transaction_date' => 'required|date',
                'method' => 'required|string',
                'journal_memo' => 'required|string',
                'person_name' => 'required|string',
                'payment_amount' => 'required|numeric|min:0',
                'type' => 'required|string|in:Payment,Receipt'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment data. Please provide either part payment or other payment details.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            if ($isPartPayment && $request->part_type === 'Customer') {
                $saleData = json_decode($request->transactions_data, true);
                $createdTransactions = [];

                foreach ($saleData as $sale) {
                    $saleModel = Sale::findOrFail($sale['id']);

                    $oldDept = $saleModel->dept;
                    $newDept = $sale['newBalance'];
                    $paidAmount = $oldDept - $newDept;

                    if ($paidAmount > 0) {
                        // Use appropriate reference based on payment type
                        $prefix = $paymentType === 'Payment' ? 'PAY-' : 'RCV-';
                        $reference_no = $prefix . time() . rand(100, 999) . '-' . $sale['id'];

                        $transaction = new Transaction([
                            'reference_no' => $reference_no,
                            'transaction_date' => $request->transaction_date,
                            'method' => $request->method,
                            'journal_memo' => $request->journal_memo,
                            'part_id' => $request->part_id,
                            'sale_id' => $sale['id'],
                            'payment_amount' => $paidAmount,
                            'type' => $paymentType,
                        ]);

                        $transaction->save();
                        $createdTransactions[] = $transaction->id;

                        $saleModel->update([
                            'dept' => $newDept,
                            'paid' => $saleModel->total_amount - $newDept,
                            'status' => $newDept == 0 ? 'Paid' : 'Partial paid'
                        ]);
                    }
                }
            } else {
                // Use appropriate reference based on payment type
                $prefix = $paymentType === 'Payment' ? 'EXP-' : 'INC-';
                $reference_no = $prefix . time() . rand(100, 999);

                $transaction = new Transaction([
                    'reference_no' => $reference_no,
                    'transaction_date' => $request->transaction_date,
                    'method' => $request->method,
                    'journal_memo' => $request->journal_memo,
                    'person_name' => $request->person_name,
                    'type' => $paymentType,
                    'payment_amount' => $request->payment_amount
                ]);

                $transaction->save();
                $createdTransactions = [$transaction->id];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction(s) recorded successfully',
                'transaction_ids' => $createdTransactions
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to record transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetch_transactions(Request $request)
    {
        $search = $request->get('search');
        $transactionstartDate = $request->get('transactionstartDate');
        $transactionendDate = $request->get('transactionendDate');

        $query = Transaction::with([
            'part',
            'purchase',
            'sale'
        ])->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'LIKE', "%{$search}%")
                    ->orWhere('person_name', 'LIKE', "%{$search}%")
                    ->orWhereHas('part', function ($partQuery) use ($search) {
                        $partQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($transactionstartDate) {
            $query->whereDate('transaction_date', '>=', $transactionstartDate);
        }

        if ($transactionendDate) {
            $query->whereDate('transaction_date', '<=', $transactionendDate);
        }

        $transactions = $query->take(50)->get();

        // Ensure all related data is properly loaded
        $transactions->each(function ($transaction) {
            if ($transaction->purchase_id && !$transaction->purchase) {
                $transaction->purchase = Purchase::find($transaction->purchase_id);
            }

            if ($transaction->sale_id && !$transaction->sale) {
                $transaction->sale = Sale::find($transaction->sale_id);
            }
        });

        return response()->json(['items' => $transactions]);
    }

    public function populate_transaction(int $id): JsonResponse
    {
        try {

            $transaction = Transaction::with(['part', 'purchase', 'sale'])->findOrFail($id);

            return response()->json($transaction);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Transaction not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update transaction
     */
    public function transaction_update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // First, determine if this is a part payment or other payment update
        $isPartPayment = $request->has('part_id') && $request->filled('part_id');
        $isOtherPayment = $request->has('person_name') && $request->filled('person_name');

        // Get the payment type (Payment or Receipt)
        $paymentType = $request->input('type', $transaction->type ?? 'Payment');

        // Apply validation based on the type of payment
        if ($isPartPayment) {
            $request->validate([
                'transaction_date' => 'required|date',
                'method' => 'required|string',
                'journal_memo' => 'required|string',
                'part_id' => 'required|numeric',
                'part_type' => 'required|string|in:Supplier,Customer',
                'dept_paid' => 'required|numeric|min:0',
                'type' => 'required|string|in:Payment,Receipt'
            ]);
        } else if ($isOtherPayment) {
            $request->validate([
                'transaction_date' => 'required|date',
                'method' => 'required|string',
                'journal_memo' => 'required|string',
                'person_name' => 'required|string',
                'payment_amount' => 'required|numeric|min:0',
                'type' => 'required|string|in:Payment,Receipt'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment data. Please provide either part payment or other payment details.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Handle supplier transaction (purchase)
            if ($transaction->part_id && $transaction->purchase_id) {
                $purchase = Purchase::findOrFail($transaction->purchase_id);

                $newPayment = $request->dept_paid;
                $newDept = $purchase->total_amount - $newPayment;

                if ($newPayment > ($purchase->total_amount)) {
                    throw new \Exception('Payment amount exceeds the remaining balance');
                }

                $purchase->update([
                    'paid' => $newPayment,
                    'dept' => $newDept,
                    'status' => $newDept <= 0 ? 'Confirmed' : 'Draft'
                ]);

                $transaction->update([
                    'transaction_date' => $request->transaction_date,
                    'method' => $request->method,
                    'journal_memo' => $request->journal_memo,
                    'payment_amount' => $newPayment,
                    'type' => $paymentType, // Update payment type
                ]);
            }
            // Handle customer transaction (sale)
            else if ($transaction->part_id && $transaction->sale_id) {
                $sale = Sale::findOrFail($transaction->sale_id);

                $newPayment = $request->dept_paid;
                $newDept = $sale->total_amount - $newPayment;

                if ($newPayment > ($sale->total_amount)) {
                    throw new \Exception('Payment amount exceeds the remaining balance');
                }

                $sale->update([
                    'paid' => $newPayment,
                    'dept' => $newDept,
                    'status' => $newDept <= 0 ? 'Confirmed' : 'Draft'
                ]);

                $transaction->update([
                    'transaction_date' => $request->transaction_date,
                    'method' => $request->method,
                    'journal_memo' => $request->journal_memo,
                    'payment_amount' => $newPayment,
                    'type' => $paymentType, // Update payment type
                ]);
            }
            // Handle other payment (no part)
            else {
                // Update the reference_no based on the payment type if it changes
                if ($transaction->type !== $paymentType) {
                    $prefix = $paymentType === 'Payment' ? 'EXP-' : 'INC-';
                    $reference_no = $prefix . time() . rand(100, 999);

                    $transaction->reference_no = $reference_no;
                }

                $transaction->update([
                    'transaction_date' => $request->transaction_date,
                    'method' => $request->method,
                    'journal_memo' => $request->journal_memo,
                    'person_name' => $request->person_name,
                    'payment_amount' => $request->payment_amount,
                    'type' => $paymentType, // Update payment type
                    'part_id' => null,
                    'purchase_id' => null,
                    'sale_id' => null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete_transaction($id)
    {
        DB::beginTransaction();

        try {
            // Find the transaction to delete
            $transaction = Transaction::findOrFail($id);

            // Track if we need to delete the parent record (purchase/sale)
            $shouldDeleteParent = false;

            // Handle purchase-related transactions
            if ($transaction->purchase_id) {
                $purchase = Purchase::findOrFail($transaction->purchase_id);

                // Delete the transaction first
                $transaction->delete();

                // Count remaining transactions for this purchase
                $remainingTransactions = Transaction::where('purchase_id', $purchase->id)->get();
                $transactionCount = $remainingTransactions->count();

                if ($transactionCount === 0) {
                    // If no transactions left, delete the purchase
                    $shouldDeleteParent = true;

                    // Get purchase items before deleting the purchase
                    $purchaseItems = PurchaseItem::where('purchase_id', $purchase->id)->get();

                    // Delete purchase items
                    PurchaseItem::where('purchase_id', $purchase->id)->delete();

                    // Delete the purchase
                    $purchase->delete();

                    // Update affected items' status
                    foreach ($purchaseItems as $purchaseItem) {
                        $item = Item::find($purchaseItem->item_id);
                        if ($item) {
                            $currentStock = $item->getCurrentStockAttribute();
                            if ($currentStock <= 0) {
                                $item->status = 'Sold Out';
                            } else {
                                $item->status = 'Available';
                            }
                            $item->save();
                        }
                    }
                } else {
                    // Recalculate the paid amount by summing all remaining transactions
                    $totalPaid = $remainingTransactions->sum('payment_amount');

                    // Update the purchase record
                    $purchase->paid = $totalPaid;
                    $purchase->dept = $purchase->total_amount - $totalPaid;

                    // Update status based on new paid amount
                    if ($totalPaid <= 0) {
                        $purchase->status = 'Unpaid';
                    } else if ($totalPaid < $purchase->total_amount) {
                        $purchase->status = 'Partial paid';
                    } else {
                        $purchase->status = 'Paid';
                    }

                    $purchase->save();
                }
            }

            // Handle sale-related transactions
            else if ($transaction->sale_id) {
                $sale = Sale::findOrFail($transaction->sale_id);

                // Delete the transaction first
                $transaction->delete();

                // Count remaining transactions for this sale
                $remainingTransactions = Transaction::where('sale_id', $sale->id)->get();
                $transactionCount = $remainingTransactions->count();

                if ($transactionCount === 0) {
                    // If no transactions left, delete the sale
                    $shouldDeleteParent = true;

                    // Get sale items before deleting the sale
                    $saleItems = SaleItem::where('sale_id', $sale->id)->get();

                    // Delete sale items
                    SaleItem::where('sale_id', $sale->id)->delete();

                    // Delete the sale
                    $sale->delete();

                    // Update affected items' status
                    foreach ($saleItems as $saleItem) {
                        $item = Item::find($saleItem->item_id);
                        if ($item) {
                            $currentStock = $item->getCurrentStockAttribute();
                            if ($currentStock <= 0) {
                                $item->status = 'Sold Out';
                            } else {
                                $item->status = 'Available';
                            }
                            $item->save();
                        }
                    }
                } else {
                    // Recalculate the paid amount by summing all remaining transactions
                    $totalPaid = $remainingTransactions->sum('payment_amount');

                    // Update the sale record
                    $sale->paid = $totalPaid;
                    $sale->dept = $sale->total_amount - $totalPaid;

                    // Update status based on new paid amount
                    if ($totalPaid <= 0) {
                        $sale->status = 'Unpaid';
                    } else if ($totalPaid < $sale->total_amount) {
                        $sale->status = 'Partial paid';
                    } else {
                        $sale->status = 'Paid';
                    }

                    $sale->save();
                }
            }

            // If it has no purchase_id or sale_id, it's a standalone transaction
            else {
                // Just delete the transaction
                $transaction->delete();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction deleted successfully',
                'deleted_parent' => $shouldDeleteParent
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getItemsStatistics()
    {
        // Best-Selling Items - Top 5 items with highest sales
        $bestSellingItems = DB::table('sale_items')
            ->select('item_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * sale_price) as total_revenue'))
            ->groupBy('item_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Slow-Moving Items - Items with less than 5 sales in the last 30 days
        $slowMovingItems = DB::table('items')
            ->leftJoin('sale_items', function ($join) {
                $join->on('items.id', '=', 'sale_items.item_id')
                    ->where('sale_items.created_at', '>=', now()->subDays(30));
            })
            ->select('items.id', 'items.name', DB::raw('COALESCE(SUM(sale_items.quantity), 0) as total_sold'))
            ->groupBy('items.id', 'items.name')
            ->having('total_sold', '<', 5)
            ->limit(10)
            ->get();

        // Sales Trends - Last 6 months
        $salesTrends = DB::table('sale_items')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * sale_price) as total_revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Total Revenue 
        $totalRevenue = DB::table('sale_items')
            ->sum(DB::raw('quantity * sale_price'));

        // Discount Impact
        $discountImpact = DB::table('sale_items')
            ->select(
                DB::raw('SUM(CASE WHEN discount > 0 THEN quantity * sale_price ELSE 0 END) as with_discount'),
                DB::raw('SUM(CASE WHEN discount = 0 OR discount IS NULL THEN quantity * sale_price ELSE 0 END) as without_discount')
            )
            ->first();

        // Purchase Trends - Monthly
        $purchaseTrends = DB::table('purchase_items')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * purchase_price) as total_cost')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Dead Stock - Items not sold in the last 90 days but have stock
        $deadStock = DB::table('items')
            ->select('items.id', 'items.name', 'items.sku')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sale_items')
                    ->whereRaw('sale_items.item_id = items.id')
                    ->where('sale_items.created_at', '>=', now()->subDays(90));
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('purchase_items')
                    ->whereRaw('purchase_items.item_id = items.id');
            })
            ->whereRaw('(SELECT SUM(purchase_items.quantity) FROM purchase_items WHERE purchase_items.item_id = items.id) > 
                    COALESCE((SELECT SUM(sale_items.quantity) FROM sale_items WHERE sale_items.item_id = items.id), 0)')
            ->limit(10)
            ->get();

        // Profit Per Item - Average profit margin across all items
        $profitCalculation = DB::table('items')
            ->select('items.id', 'items.name', 'items.sale_price')
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
                    'name' => $item->name,
                    'sale_price' => $item->sale_price,
                    'avg_purchase_price' => $item->avg_purchase_price,
                    'profit_margin' => $item->sale_price - $item->avg_purchase_price,
                    'profit_percentage' => $item->avg_purchase_price > 0
                        ? (($item->sale_price - $item->avg_purchase_price) / $item->avg_purchase_price) * 100
                        : 0
                ];
            })
            ->sortByDesc('profit_margin')
            ->take(10)
            ->values();

        return response()->json([
            'success' => true,
            'statistics' => [
                'best_selling_items' => $bestSellingItems,
                'slow_moving_items' => $slowMovingItems,
                'sales_trends' => $salesTrends,
                'total_revenue' => $totalRevenue,
                'discount_impact' => $discountImpact,
                'purchase_trends' => $purchaseTrends,
                'dead_stock' => $deadStock,
                'profit_calculation' => $profitCalculation
            ]
        ]);
    }
}
