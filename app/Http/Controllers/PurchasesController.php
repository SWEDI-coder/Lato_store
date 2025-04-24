<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PurchasesController extends Controller
{
    private function determineInvoiceStatus($totalAmount, $paid, $currentStatus = null)
    {
        // Calculate the unpaid amount
        $unpaidAmount = $totalAmount - $paid;

        // Determine status based on payment
        if ($unpaidAmount <= 0) {
            return 'Paid';
        } elseif ($paid > 0) {
            return 'Partial paid';
        } else {
            return 'Unpaid';
        }
    }

    public function record_Purchases(Request $request)
    {
        try {
            $purchasesData = $request->input('purchasesData');

            $validator = Validator::make($purchasesData, [
                'purchase_date' => 'required|date',
                'part_id' => 'nullable|exists:parts,id', // Allow part_id to be nullable
                'paid' => 'required|numeric|min:0',
                'dept' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.purchase_price' => 'required|numeric|min:0',
                'items.*.expire_date' => 'nullable|date',
                'items.*.discounts' => 'nullable|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Custom validation: If there's debt, a supplier (part_id) is required
            if (($purchasesData['total_amount'] - $purchasesData['paid']) > 0 && empty($purchasesData['part_id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => "You can't record purchase debt if there is no Supplier."
                ], 422);
            }

            DB::beginTransaction();

            $reference_no = 'PUR-' . time() . rand(100, 999);

            $purchase = new Purchase([
                'reference_no' => $reference_no,
                'purchase_date' => $purchasesData['purchase_date'],
                'part_id' => $purchasesData['part_id'], // Can be null
                'total_amount' => 0, // Will be updated later
                'total_discount' => 0, // Will be updated later
                'paid' => $purchasesData['paid'],
                'dept' => $purchasesData['dept'],
                'status' => 'Unpaid' // Default status, will be updated later
            ]);

            $purchase->save();

            $totalAmount = 0;
            $totalDiscount = 0;

            foreach ($purchasesData['items'] as $itemData) {
                $purchaseItem = new PurchaseItem([
                    'purchase_id' => $purchase->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'purchase_price' => $itemData['purchase_price'],
                    'discount' => $itemData['discounts'] ?? 0,
                    'part_id' => $purchasesData['part_id'], // Can be null
                    'expire_date' => $itemData['expire_date'] ?? null
                ]);
                $purchaseItem->save();

                $itemAmount = ($itemData['purchase_price'] * $itemData['quantity']);
                $itemDiscount = ($itemData['discounts'] ?? 0);

                $totalAmount += $itemAmount;
                $totalDiscount += $itemDiscount;
            }

            $finalAmount = $totalAmount - $totalDiscount;
            $deptAmount = $finalAmount - (float)$purchasesData['paid'];

            $purchase->update([
                'total_amount' => $finalAmount,
                'total_discount' => $totalDiscount,
                'dept' => $deptAmount
            ]);

            // Update status based on payment
            $purchase->status = $this->determineInvoiceStatus($finalAmount, $purchasesData['paid']);
            $purchase->save();

            // Explicitly check for paid amount to create transaction
            if (isset($purchasesData['paid']) && is_numeric($purchasesData['paid']) && (float)$purchasesData['paid'] >= 0) {
                $description = isset($purchasesData['description']) && !empty($purchasesData['description'])
                    ? $purchasesData['description']
                    : 'Initial purchase payment';

                try {

                    $transaction = new Transaction([
                        'reference_no' => $reference_no,
                        'purchase_id' => $purchase->id,
                        'part_id' => $purchasesData['part_id'],
                        'type' => 'Payment',
                        'method' => 'Cash',
                        'payment_amount' => (float)$purchasesData['paid'],
                        'dept_paid' => (float)$purchasesData['paid'],
                        'dept_remain' => $deptAmount,
                        'transaction_date' => $purchasesData['purchase_date'],
                        'journal_memo' => $description
                    ]);
                    $transaction->save();
                } catch (\Exception $e) {
                    Log::error('Error creating transaction', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue execution - don't throw the exception
                }
            } else {
                Log::info('No transaction created - zero payment', [
                    'paid' => $purchasesData['paid'],
                    'paid_type' => gettype($purchasesData['paid'])
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase recorded successfully',
                'data' => $purchase->load('purchaseItems.item')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to record purchase', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to record purchase: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update_purchase(Request $request, $purchase_id)
    {
        try {
            $purchasesData = $request->input('purchasesData');

            $validator = Validator::make([
                'purchase_date' => $purchasesData['purchase_date'],
                'part_id' => $purchasesData['part_id'],
                'paid' => $purchasesData['paid'],
                'dept' => $purchasesData['dept'],
                'items' => $purchasesData['items']
            ], [
                'purchase_date' => 'required|date',
                'part_id' => 'nullable|exists:parts,id', // Allow part_id to be nullable
                'paid' => 'required|numeric|min:0',
                'dept' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.purchase_price' => 'required|numeric|min:0',
                'items.*.expire_date' => 'nullable|date',
                'items.*.discounts' => 'nullable|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Custom validation: If there's debt, a supplier (part_id) is required
            if (($purchasesData['total_amount'] - $purchasesData['paid']) > 0 && empty($purchasesData['part_id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => "You can't update purchase with debt if there is no Supplier."
                ], 422);
            }

            DB::beginTransaction();

            $purchase = Purchase::findOrFail($purchase_id);

            // Calculate old paid amount
            $oldPaidAmount = $purchase->paid;

            // Update purchase basic info
            $purchase->purchase_date = $purchasesData['purchase_date'];
            $purchase->part_id = $purchasesData['part_id']; // Can be null
            $purchase->paid = $purchasesData['paid'];

            // Delete old purchase items
            PurchaseItem::where('purchase_id', $purchase_id)->delete();

            $totalAmount = 0;
            $totalDiscount = 0;

            // Create new purchase items
            foreach ($purchasesData['items'] as $itemData) {
                $purchaseItem = new PurchaseItem([
                    'purchase_id' => $purchase->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'purchase_price' => $itemData['purchase_price'],
                    'discount' => $itemData['discounts'] ?? 0,
                    'part_id' => $purchasesData['part_id'], // Can be null
                    'expire_date' => isset($itemData['expire_date']) ? $itemData['expire_date'] : null
                ]);
                $purchaseItem->save();

                $totalAmount += $itemData['purchase_price'] * $itemData['quantity'];
                $totalDiscount += ($itemData['discounts'] ?? 0);
            }

            $finalAmount = $totalAmount - $totalDiscount;

            // Update purchase totals
            $purchase->total_amount = $finalAmount;
            $purchase->total_discount = $totalDiscount;
            $purchase->dept = $finalAmount - $purchasesData['paid'];

            // Update status based on payment
            $purchase->status = $this->determineInvoiceStatus($finalAmount, $purchasesData['paid']);
            $purchase->save();

            // Update or create transaction for the payment
            if ($purchasesData['paid'] > 0 || $oldPaidAmount > 0) {
                $transaction = Transaction::where('purchase_id', $purchase_id)
                    ->where('type', 'Payment')
                    ->first();

                if ($transaction) {
                    $transaction->update([
                        'payment_amount' => $purchasesData['paid'],
                        'dept_paid' => $purchasesData['paid'],
                        'dept_remain' => $purchase->dept,
                        'transaction_date' => $purchasesData['purchase_date'],
                        'journal_memo' => $purchasesData['description'] ?? 'Purchase Payment Update'
                    ]);
                } else if ($purchasesData['paid'] > 0) {
                    Transaction::create([
                        'reference_no' => $purchase->reference_no,
                        'purchase_id' => $purchase->id,
                        'part_id' => $purchasesData['part_id'], // Can be null
                        'type' => 'Payment',
                        'method' => 'Cash',
                        'payment_amount' => $purchasesData['paid'],
                        'transaction_date' => $purchasesData['purchase_date'],
                        'journal_memo' => $purchasesData['description'] ?? 'Purchase Payment'
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase updated successfully',
                'data' => $purchase->load('purchaseItems.item')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update purchase: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetch_purchases(Request $request)
    {
        $search = $request->get('search');
        $PurchasesstartDate = $request->get('PurchasesstartDate');
        $PurchasesendDate = $request->get('PurchasesendDate');

        $query = Purchase::with(['supplier'])  // this loads the Part model via the supplier relation
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->whereHas('supplier', function ($q) use ($search) {
                $q->where('type', 'Supplier')  // only get Parts that are suppliers
                    ->where(function ($subQ) use ($search) {
                        $subQ->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('vat_number', 'LIKE', "%{$search}%")
                            ->orWhere('tin_number', 'LIKE', "%{$search}%")
                            ->orWhere('phone_number', 'LIKE', "%{$search}%")
                            ->orWhere('address', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($PurchasesstartDate) {
            $query->whereDate('purchase_date', '>=', $PurchasesstartDate);
        }

        if ($PurchasesendDate) {
            $query->whereDate('purchase_date', '<=', $PurchasesendDate);
        }

        $purchases = $query->take(20)->get();
        return response()->json(['items' => $purchases]);
    }

    public function getPurchase($id)
    {
        try {
            $purchase = Purchase::with(['purchaseItems.item' => function ($query) {
                $query->withCount(['purchaseItems as total_purchased' => function ($q) {
                    $q->select(DB::raw('COALESCE(SUM(quantity), 0)'));
                }])
                    ->withCount(['saleItems as total_sold' => function ($q) {
                        $q->select(DB::raw('COALESCE(SUM(quantity), 0)'));
                    }]);
            }, 'supplier'])
                ->findOrFail($id);

            // Calculate current stock for each item
            foreach ($purchase->purchaseItems as $purchaseItem) {
                if ($purchaseItem->item) {
                    $purchaseItem->item->current_stock =
                        $purchaseItem->item->total_purchased - $purchaseItem->item->total_sold;
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $purchase
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch purchase details'
            ], 500);
        }
    }

    public function fetch_supplier_purchases(Request $request)
    {
        $supplierId = $request->input('id');

        $purchases = Purchase::where('part_id', $supplierId)
            ->where('dept', '>', 0)
            ->get();

        return response()->json($purchases);
    }

    public function delete_purchase($id)
    {
        try {
            DB::beginTransaction();

            $purchase = Purchase::with('purchaseItems')->findOrFail($id);

            if (!$purchase) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Purchase not found!',
                ], 404);
            }

            Transaction::where('purchase_id', $id)->delete();

            PurchaseItem::where('purchase_id', $id)->delete();

            $purchase->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase and all associated records deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to delete purchase.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
