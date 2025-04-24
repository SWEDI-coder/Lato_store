<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\SaleItem;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class PartController extends Controller
{
    public function store_party(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'type' => 'required|in:Supplier,Customer',
            'email' => [
                'nullable',
                'email',
                Rule::unique('parts', 'email')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                }),
            ],
        ]);

        try {
            $party = Part::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'gender' => $request->gender,
                'address' => $request->address,
                'vat_number' => $request->vat_number,
                'tin_number' => $request->tin_number,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success! Party Added',
                'party' => $party,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed! Unable To Add Party',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update_part($id, Request $request)
    {
        $party = Part::find($id);

        if (!$party) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Party not found!',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|min:3|max:50',
            'type' => 'required|in:Supplier,Customer',
            'email' => [
                'nullable',
                'email',
                Rule::unique('parts', 'email')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                }),
            ],
        ]);

        try {
            $party->update([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'address' => $request->address,
                'vat_number' => $request->vat_number,
                'tin_number' => $request->tin_number,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success! Party Updated',
                'party' => $party,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed! Unable To Update Party',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete_part($id)
    {
        try {
            $party = Part::find($id);

            if (!$party) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Party not found!',
                ], 404);
            }

            DB::beginTransaction();

            // Delete related transactions
            $party->transactions()->delete();

            // Delete related sales and sale items
            if ($party->type == 'Customer') {
                // Get all sales IDs
                $salesIds = $party->sales()->pluck('id')->toArray();

                // Delete sale items for these sales
                if (!empty($salesIds)) {
                    SaleItem::whereIn('sale_id', $salesIds)->delete();
                }

                // Delete the sales
                $party->sales()->delete();
            }

            // Delete related purchases and purchase items
            if ($party->type == 'Supplier') {
                // Get all purchases IDs
                $purchasesIds = $party->purchases()->pluck('id')->toArray();

                // Delete purchase items for these purchases
                if (!empty($purchasesIds)) {
                    PurchaseItem::whereIn('purchase_id', $purchasesIds)->delete();
                }

                // Delete the purchases
                $party->purchases()->delete();
            }

            // Delete the party
            $party->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Success! Party deleted successfully along with all related records.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed! Unable to delete Party.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function fetch_parties(Request $request)
    {
        try {
            // Apply filters
            $query = Part::query();

            // Search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('tin_number', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Status filter (if used in your UI)
            if ($request->has('status') && !empty($request->status)) {
                // We'll handle status filtering in the collection mapping
                // since status is now a calculated field
            }

            // Gender filter 
            if ($request->has('gender') && !empty($request->min)) {
                $query->where('gender', $request->min);
            }

            // Type filter (if needed)
            if ($request->has('type') && !empty($request->type)) {
                $query->where('type', $request->type);
            }

            // Execute the query with minimal relations needed
            $parties = $query->get();

            // Process each party to calculate summary data
            $formattedParties = $parties->map(function ($party) {
                // Base party data
                $partyData = [
                    'id' => $party->id,
                    'name' => $party->name,
                    'company_name' => $party->company_name,
                    'contact_person' => $party->contact_person,
                    'type' => $party->type,
                    'gender' => $party->gender,
                    'address' => $party->address,
                    'vat_number' => $party->vat_number,
                    'tin_number' => $party->tin_number,
                    'phone_number' => $party->phone_number,
                    'email' => $party->email,
                    'created_at' => $party->created_at,
                ];

                // Initialize summary values
                $total_dept = 0;
                $total_paid = 0;
                $total_discount = 0;
                $total_amount = 0;
                $status = 'N/A';

                if ($party->type === 'Supplier') {
                    // Get summary data for purchases
                    $purchaseSummary = Purchase::where('part_id', $party->id)
                        ->selectRaw('SUM(dept) as total_dept, SUM(paid) as total_paid, SUM(total_discount) as total_discount, SUM(total_amount) as total_amount')
                        ->first();

                    // Get the count of purchases
                    $purchaseCount = Purchase::where('part_id', $party->id)->count();

                    if ($purchaseSummary) {
                        $total_dept = $purchaseSummary->total_dept ?? 0;
                        $total_paid = $purchaseSummary->total_paid ?? 0;
                        $total_discount = $purchaseSummary->total_discount ?? 0;
                        $total_amount = $purchaseSummary->total_amount ?? 0;

                        // Determine overall status
                        if ($total_dept <= 0) {
                            $status = 'Paid';
                        } elseif ($total_paid > 0) {
                            $status = 'Partial paid';
                        } else {
                            $status = 'Unpaid';
                        }
                    }

                    // Add purchases summary to the party data
                    $partyData['purchases'] = [
                        'count' => $purchaseCount,
                        'total_dept' => round($total_dept, 2),
                        'total_paid' => round($total_paid, 2),
                        'total_discount' => round($total_discount, 2),
                        'total_amount' => round($total_amount, 2),
                        'status' => $status
                    ];

                    // Add empty sales data
                    $partyData['sales'] = [
                        'count' => 0,
                        'total_dept' => 0,
                        'total_paid' => 0,
                        'total_discount' => 0,
                        'total_amount' => 0,
                        'status' => 'N/A'
                    ];
                } elseif ($party->type === 'Customer') {
                    // Get summary data for sales
                    $saleSummary = Sale::where('part_id', $party->id)
                        ->selectRaw('SUM(dept) as total_dept, SUM(paid) as total_paid, SUM(total_discount) as total_discount, SUM(total_amount) as total_amount')
                        ->first();

                    // Get the count of sales
                    $saleCount = Sale::where('part_id', $party->id)->count();

                    if ($saleSummary) {
                        $total_dept = $saleSummary->total_dept ?? 0;
                        $total_paid = $saleSummary->total_paid ?? 0;
                        $total_discount = $saleSummary->total_discount ?? 0;
                        $total_amount = $saleSummary->total_amount ?? 0;

                        // Determine overall status
                        if ($total_dept <= 0) {
                            $status = 'Paid';
                        } elseif ($total_paid > 0) {
                            $status = 'Partial paid';
                        } else {
                            $status = 'Unpaid';
                        }
                    }

                    // Add sales summary to the party data
                    $partyData['sales'] = [
                        'count' => $saleCount,
                        'total_dept' => round($total_dept, 2),
                        'total_paid' => round($total_paid, 2),
                        'total_discount' => round($total_discount, 2),
                        'total_amount' => round($total_amount, 2),
                        'status' => $status
                    ];

                    // Add empty purchases data
                    $partyData['purchases'] = [
                        'count' => 0,
                        'total_dept' => 0,
                        'total_paid' => 0,
                        'total_discount' => 0,
                        'total_amount' => 0,
                        'status' => 'N/A'
                    ];
                }

                return $partyData;
            });

            // Additional filtering for status (if requested)
            if ($request->has('status') && !empty($request->status)) {
                $statusFilter = $request->status;

                $formattedParties = $formattedParties->filter(function ($party) use ($statusFilter) {
                    if ($party['type'] === 'Supplier') {
                        return $party['purchases']['status'] === $statusFilter;
                    } elseif ($party['type'] === 'Customer') {
                        return $party['sales']['status'] === $statusFilter;
                    }
                    return false;
                })->values();
            }

            return response()->json([
                'status' => 'success',
                'message' => $formattedParties->isEmpty() ? 'No parties found!' : 'Parties fetched successfully!',
                'parties' => $formattedParties,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch parties!',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit_part($id)
    {
        $part = Part::find($id);
        if ($part) {
            return response()->json([
                'status' => 201,
                'part' => $part,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'part not found',
            ]);
        }
    }

    public function search_supplier(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $suppliers = Part::where('type', 'Supplier')
                ->where('name', 'LIKE', "%{$query}%")
                ->get();

            $output = '<ul class="bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-y-auto divide-y divide-gray-100">';

            if ($suppliers->count() > 0) {
                foreach ($suppliers as $supplier) {
                    $output .= '
                    <li data-id="' . $supplier->id . '" class="supplier_li p-2 hover:bg-blue-50 cursor-pointer flex items-center transition-colors duration-150 ease-in-out">
                        <div class="flex-shrink-0 text-blue-500 mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="block font-medium text-gray-900">' . $supplier->name . '</span>
                        </div>
                    </li>';
                }
            } else {
                $output .= '
                <li class="p-2 text-gray-500 italic text-center">
                    No suppliers found
                </li>';
            }

            $output .= '</ul>';
            echo $output;
        }
    }

    public function search_Customer(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $customers = Part::where('type', 'Customer')
                ->where('name', 'LIKE', "%{$query}%")
                ->get();

            $output = '<ul class="bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-y-auto divide-y divide-gray-100">';

            if ($customers->count() > 0) {
                foreach ($customers as $customer) {
                    $output .= '
                    <li data-id="' . $customer->id . '" class="Customer_li p-2 hover:bg-blue-50 cursor-pointer flex items-center transition-colors duration-150 ease-in-out">
                        <div class="flex-shrink-0 text-green-500 mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="block font-medium text-gray-900">' . $customer->name . '</span>
                        </div>
                    </li>';
                }
            } else {
                $output .= '
                <li class="p-2 text-gray-500 italic text-center">
                    No customers found
                </li>';
            }

            $output .= '</ul>';
            echo $output;
        }
    }

    public function find_part_balance(Request $request)
    {
        $part = Part::find($request->id);

        if (!$part) {
            return response()->json(['error' => 'Part not found'], 404);
        }

        $dept = 0;

        if ($part->type == 'Supplier') {
            $dept = $part->purchases()->sum('dept');
        } else if ($part->type == 'Customer') {
            $dept = $part->sales()->sum('dept');
        }

        return response()->json([
            'dept' => $dept,
            'type' => $part->type
        ]);
    }

    public function search_part(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $type = $request->get('type'); // 'Supplier' or 'Customer' or empty for both

            $partsQuery = Part::where('name', 'LIKE', "%{$query}%");

            if ($type) {
                $partsQuery->where('type', $type);
            }

            $parts = $partsQuery->get();

            $output = '<ul class="bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-y-auto divide-y divide-gray-100">';

            if ($parts->count() > 0) {
                foreach ($parts as $part) {
                    $iconClass = $part->type == 'Supplier' ? 'text-blue-500' : 'text-green-500';
                    $iconSvg = $part->type == 'Supplier'
                        ? '<path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />'
                        : '<path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />';

                    $output .= '
                <li data-id="' . $part->id . '" data-type="' . $part->type . '" class="part_li p-2 hover:bg-blue-50 cursor-pointer flex items-center transition-colors duration-150 ease-in-out">
                    <div class="flex-shrink-0 ' . $iconClass . ' mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            ' . $iconSvg . '
                        </svg>
                    </div>
                    <div class="flex-1">
                        <span class="block font-medium text-gray-900">' . $part->name . '</span>
                        <span class="block text-xs text-gray-500">' . $part->type . '</span>
                    </div>
                </li>';
                }
            } else {
                $output .= '
            <li class="p-2 text-gray-500 italic text-center">
                No ' . ($type ? strtolower($type) . 's' : 'parts') . ' found
            </li>';
            }

            $output .= '</ul>';
            echo $output;
        }
    }

    public function fetch_part_transactions(Request $request)
    {
        $part = Part::find($request->id);

        if (!$part) {
            return response()->json(['error' => 'Part not found'], 404);
        }

        if ($request->type == 'Supplier' || $part->type == 'Supplier') {
            $transactions = $part->purchases()->orderBy('purchase_date', 'desc')->get();
            return response()->json($transactions);
        } else if ($request->type == 'Customer' || $part->type == 'Customer') {
            $transactions = $part->sales()->orderBy('sale_date', 'desc')->get();
            return response()->json($transactions);
        }

        return response()->json([]);
    }
}
