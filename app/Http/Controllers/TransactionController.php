<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
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
                            'status' => $newDept == 0 ? 'Confirmed' : 'Draft'
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
                            'status' => $newDept == 0 ? 'Confirmed' : 'Draft'
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

    public function populate_tranEDITmodals(int $id): JsonResponse
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

    public function delete_transaction_out(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $transaction = Transaction::findOrFail($id);

            if ($transaction->purchase_id) {

                $purchase = Purchase::findOrFail($transaction->purchase_id);

                $newDept = $purchase->dept + $transaction->payment_amount;
                $newPaid = $purchase->total_amount - $newDept;

                $purchase->update([
                    'dept' => $newDept,
                    'paid' => $newPaid,
                    'status' => 'Draft'
                ]);
            }

            $transaction->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully',
                'data' => [
                    'transaction_id' => $request->transaction_id,
                    'affected_purchase_id' => $transaction->purchase_id ?? null
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function withsale_transaction_update(Request $request, $id)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'method' => 'required|string',
            'journal_memo' => 'required|string',
            'payment_amount' => 'required_without:part_id|numeric|min:0|nullable',
            'person_name' => 'required_without:part_id|string|nullable',
            'part_id' => 'required_without:person_name|numeric|nullable',
            'dept_paid' => 'required_with:part_id|numeric|nullable',
        ]);

        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->part_id && $transaction->sale_id) {
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
                ]);
            } else {
                $transaction->update([
                    'transaction_date' => $request->transaction_date,
                    'method' => $request->method,
                    'journal_memo' => $request->journal_memo,
                    'person_name' => $request->person_name,
                    'payment_amount' => $request->payment_amount,
                    'part_id' => null,
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
}
