<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
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

    public function record_Sale(Request $request)
    {
        try {
            $SaleData = $request->input('SaleData');

            $validator = Validator::make($SaleData, [
                'Sales_date' => 'required|date',
                'part_id' => 'nullable|exists:parts,id', // Ensure part_id can be nullable
                'paid' => 'required|numeric|min:0',
                'dept' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.Sales_price' => 'required|numeric|min:0',
                'items.*.discounts' => 'nullable|numeric|min:0',
                'efd_number' => 'nullable|string',
                'z_number' => 'nullable|string',
                'receipt_number' => 'nullable|string',
                'barcode_text' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Custom validation: If there's debt, a customer (part_id) is required
            if (($SaleData['total_amount'] - $SaleData['paid']) > 0 && empty($SaleData['part_id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => "You can't record sale debt if there is no Customer."
                ], 422);
            }

            DB::beginTransaction();

            $reference_no = 'SL-' . time() . rand(100, 999);

            // Generate EFD related information
            $efd_number = $SaleData['efd_number'] ?? 'EFD' . rand(1000000, 9999999);
            $z_number = $SaleData['z_number'] ?? 'Z' . rand(100, 999);
            $receipt_number = $SaleData['receipt_number'] ?? 'RC' . rand(10000, 99999);
            $barcode_text = $SaleData['barcode_text'] ?? $reference_no;

            $sale = new Sale([
                'reference_no' => $reference_no,
                'sale_date' => $SaleData['Sales_date'],
                'part_id' => $SaleData['part_id'], // Can be null
                'total_amount' => 0, // Will be updated later
                'total_discount' => 0, // Will be updated later
                'paid' => $SaleData['paid'],
                'dept' => $SaleData['dept'],
                'status' => 'Unpaid', // Default status, will be updated later
                'efd_number' => $efd_number,
                'z_number' => $z_number,
                'receipt_number' => $receipt_number,
                'barcode_text' => $barcode_text
            ]);

            $sale->save();

            $totalAmount = 0;
            $totalDiscount = 0;

            foreach ($SaleData['items'] as $itemData) {
                $saleItem = new SaleItem([
                    'sale_id' => $sale->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'sale_price' => $itemData['Sales_price'],
                    'discount' => $itemData['discounts'] ?? 0,
                    'part_id' => $SaleData['part_id'], // Can be null
                ]);
                $saleItem->save();

                $itemAmount = ($itemData['Sales_price'] * $itemData['quantity']);
                $itemDiscount = ($itemData['discounts'] ?? 0);

                $totalAmount += $itemAmount;
                $totalDiscount += $itemDiscount;
            }

            $finalAmount = $totalAmount - $totalDiscount;
            $deptAmount = $finalAmount - $SaleData['paid'];

            $sale->update([
                'total_amount' => $finalAmount,
                'total_discount' => $totalDiscount,
                'dept' => $deptAmount
            ]);

            // Update status based on payment
            $sale->status = $this->determineInvoiceStatus($finalAmount, $SaleData['paid']);
            $sale->save();

            // Create transaction record with all required fields
            if ($SaleData['paid'] >= 0) {
                $description = isset($SaleData['description']) && !empty($SaleData['description'])
                    ? $SaleData['description']
                    : 'Initial sale payment';

                $transaction = new Transaction([
                    'reference_no' => $reference_no,
                    'sale_id' => $sale->id,
                    'part_id' => $SaleData['part_id'],
                    'type' => 'Receipt',
                    'method' => 'Cash',
                    'payment_amount' => $SaleData['paid'],
                    'dept_paid' => $SaleData['paid'],
                    'dept_remain' => $deptAmount,
                    'transaction_date' => $SaleData['Sales_date'],
                    'journal_memo' => $description,
                    'person_name' => null // Get customer name if needed
                ]);
                $transaction->save();
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale recorded successfully',
                'data' => $sale->load('saleItems.item', 'transactions')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Debug line - to be removed in production
            Log::error('Sale transaction error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to record sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update_Sales(Request $request, $sale_id)
    {
        try {
            $SaleData = $request->input('SaleData');

            $validator = Validator::make([
                'sale_date' => $SaleData['sale_date'],
                'part_id' => $SaleData['part_id'],
                'paid' => $SaleData['paid'],
                'dept' => $SaleData['dept'],
                'items' => $SaleData['items']
            ], [
                'sale_date' => 'required|date',
                'part_id' => 'nullable|exists:parts,id', // Allow part_id to be nullable
                'paid' => 'required|numeric|min:0',
                'dept' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.sale_price' => 'required|numeric|min:0',
                'items.*.discounts' => 'nullable|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Custom validation: If there's debt, a customer (part_id) is required
            if (($SaleData['total_amount'] - $SaleData['paid']) > 0 && empty($SaleData['part_id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => "You can't update sale with debt if there is no Customer."
                ], 422);
            }

            DB::beginTransaction();

            $sale = Sale::findOrFail($sale_id);

            // Calculate old paid amount
            $oldPaidAmount = $sale->paid;

            // Update sale basic info
            $sale->sale_date = $SaleData['sale_date'];
            $sale->part_id = $SaleData['part_id']; // Can be null
            $sale->paid = $SaleData['paid'];

            // Delete old sale items
            SaleItem::where('sale_id', $sale_id)->delete();

            $totalAmount = 0;
            $totalDiscount = 0;

            // Create new sale items
            foreach ($SaleData['items'] as $itemData) {
                $saleItem = new SaleItem([
                    'sale_id' => $sale->id,
                    'item_id' => $itemData['item_id'],
                    'part_id' => $SaleData['part_id'], // Can be null
                    'quantity' => $itemData['quantity'],
                    'sale_price' => $itemData['sale_price'],
                    'discount' => $itemData['discounts'] ?? 0,
                ]);
                $saleItem->save();

                $totalAmount += $itemData['sale_price'] * $itemData['quantity'];
                $totalDiscount += ($itemData['discounts'] ?? 0);
            }

            $finalAmount = $totalAmount - $totalDiscount;

            // Update sale totals
            $sale->total_amount = $finalAmount;
            $sale->total_discount = $totalDiscount;
            $sale->dept = $finalAmount - $SaleData['paid'];

            // Update status based on payment
            $sale->status = $this->determineInvoiceStatus($finalAmount, $SaleData['paid']);
            $sale->save();

            // Update or create transaction for the payment
            if ($SaleData['paid'] > 0 || $oldPaidAmount > 0) {
                $transaction = Transaction::where('sale_id', $sale_id)
                    ->where('type', 'Receipt')
                    ->first();

                if ($transaction) {
                    $transaction->update([
                        'payment_amount' => $SaleData['paid'],
                        'dept_paid' => $SaleData['paid'],
                        'dept_remain' => $sale->dept,
                        'transaction_date' => $SaleData['sale_date'],
                        'journal_memo' => $SaleData['description'] ?? 'Sale Payment Update'
                    ]);
                } else if ($SaleData['paid'] > 0) {
                    Transaction::create([
                        'reference_no' => $sale->reference_no,
                        'sale_id' => $sale->id,
                        'part_id' => $SaleData['part_id'], // Can be null
                        'type' => 'Receipt',
                        'method' => 'Cash',
                        'payment_amount' => $SaleData['paid'],
                        'transaction_date' => $SaleData['sale_date'],
                        'journal_memo' => $SaleData['description'] ?? 'Sale Payment'
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale updated successfully',
                'data' => $sale->load('saleItems.item')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetch_Sales(Request $request)
    {
        $search = $request->get('search');
        $sale_startDate = $request->get('sale_startDate');
        $sale_endDate = $request->get('sale_endDate');

        $query = Sale::with(['customer'])  // this loads the Part model via the supplier relation
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('type', 'Customer')  // only get Parts that are suppliers
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

        if ($sale_startDate) {
            $query->whereDate('sale_date', '>=', $sale_startDate);
        }

        if ($sale_endDate) {
            $query->whereDate('sale_date', '<=', $sale_endDate);
        }

        $sales = $query->take(20)->get();
        return response()->json(['items' => $sales]);
    }

    public function getSales($id)
    {
        try {
            $sale = Sale::with(['saleItems.item' => function ($query) {
                $query->withCount(['saleItems as total_sold' => function ($q) {
                    $q->select(DB::raw('COALESCE(SUM(quantity), 0)'));
                }])
                    ->withCount(['purchaseItems as total_purchased' => function ($q) {
                        $q->select(DB::raw('COALESCE(SUM(quantity), 0)'));
                    }]);
            }, 'customer'])
                ->findOrFail($id);

            // Calculate current stock for each item
            foreach ($sale->saleItems as $saleItem) {
                if ($saleItem->item) {
                    $saleItem->item->current_stock =
                        $saleItem->item->total_purchased - $saleItem->item->total_sold;
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => $sale
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch purchase details'
            ], 500);
        }
    }

    public function delete_sale($id)
    {
        try {

            DB::beginTransaction();

            $sale = Sale::with('saleItems')->findOrFail($id);

            if (!$sale) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Sale not found!',
                ], 404);
            }

            Transaction::where('sale_id', $id)->delete();

            SaleItem::where('sale_id', $id)->delete();

            $sale->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale and all associated records deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to delete sale.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function fetch_Customer_Sale(Request $request)
    {
        $customerId = $request->input('id');

        $sales = Sale::where('part_id', $customerId)
            ->where('dept', '>', 0)
            ->get();

        return response()->json($sales);
    }
}
