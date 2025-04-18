<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class PartController extends Controller
{
    public function Parties()
    {
        return view('Customers');
    }

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

    public function fetch_parties()
    {
        try {
            // Retrieve all parties (Suppliers and Customers)
            $parties = Part::all();

            // Check if there are any records
            if ($parties->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No parties found!',
                    'parties' => [],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Parties fetched successfully!',
                'parties' => $parties,
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

            $party->delete(); // Delete the part

            return response()->json([
                'status' => 'success',
                'message' => 'Success! Party deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed! Unable to delete Party.',
                'error' => $e->getMessage(),
            ], 500);
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

    public function find_supplier_balance(Request $request)
    {
        $supplier = Part::with('purchases')->find($request->id);
        $dept = $supplier->purchases->sum('dept');

        return response()->json(['dept' => $dept]);
    }

    public function find_Customer_balance(Request $request)
    {
        $customer = Part::with('sales')->find($request->id);
        $dept = $customer->sales->sum('dept');

        return response()->json(['dept' => $dept]);
    }
}
