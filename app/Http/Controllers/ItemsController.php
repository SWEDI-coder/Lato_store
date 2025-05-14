<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Brand;
use App\Models\MattressType;
use App\Models\MattressSize;
use App\Models\PurchaseItem;
use App\Models\SaleItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class ItemsController extends Controller
{
    public function store_item(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sale_price' => 'nullable|numeric|min:0',
            'is_mattress' => 'nullable|in:0,1,true,false', // Allow string values
            'brand_id' => 'nullable|exists:brands,id',
            'mattress_type_id' => 'nullable|exists:mattress_types,id',
            'mattress_size_id' => 'nullable|exists:mattress_sizes,id',
            'color' => 'nullable|string|max:50',
            'warranty_period' => 'nullable|string|max:50',
        ]);

        try {
            $item = new Item([
                'name' => $request->name,
                'description' => $request->description,
                'sale_price' => $request->sale_price,
                'status' => 'Inactive',
                'is_mattress' => filter_var($request->is_mattress, FILTER_VALIDATE_BOOLEAN), // Convert to boolean
                'brand_id' => $request->brand_id,
                'mattress_type_id' => $request->mattress_type_id,
                'mattress_size_id' => $request->mattress_size_id,
                'color' => $request->color,
                'warranty_period' => $request->warranty_period,
            ]);
            $item->save();

            $sku_no = 'SKU-' . time() . rand(10, 99) . '-' . $item->id;
            $item->update(['sku' => $sku_no]);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully!',
                'item' => $item,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed! Unable to add item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update_item($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sale_price' => 'nullable|numeric|min:0',
            'is_mattress' => 'nullable|in:0,1,true,false', // Allow string values
            'brand_id' => 'nullable|exists:brands,id',
            'mattress_type_id' => 'nullable|exists:mattress_types,id',
            'mattress_size_id' => 'nullable|exists:mattress_sizes,id',
            'color' => 'nullable|string|max:50',
            'warranty_period' => 'nullable|string|max:50',
        ]);

        try {
            $item = Item::findOrFail($id);

            // Convert is_mattress to boolean
            if (isset($validated['is_mattress'])) {
                $validated['is_mattress'] = filter_var($validated['is_mattress'], FILTER_VALIDATE_BOOLEAN);
            }

            $item->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully!',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function edit_item_details($id)
    {
        try {
            // Find the item
            $item = Item::with(['brand', 'mattressType', 'mattressSize'])->findOrFail($id);

            // Get the latest purchase details for this item
            $latestPurchase = PurchaseItem::where('item_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

            $latestSale = SaleItem::where('item_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

            // Get purchase statistics
            $purchaseStats = PurchaseItem::where('item_id', $id)
                ->selectRaw('
                    COUNT(*) as total_purchases,
                    SUM(quantity) as total_quantity_purchased,
                    AVG(purchase_price) as average_purchase_price,
                    MAX(purchase_price) as highest_purchase_price,
                    MIN(purchase_price) as lowest_purchase_price
                ')
                ->first();

            // Get sale statistics
            $saleStats = SaleItem::where('item_id', $id)
                ->selectRaw('
                    COUNT(*) as total_sales,
                    SUM(quantity) as total_quantity_sold,
                    AVG(sale_price) as average_sale_price,
                    MAX(sale_price) as highest_sale_price,
                    MIN(sale_price) as lowest_sale_price
                ')
                ->first();

            $currentStock = $purchaseStats->total_quantity_purchased - $saleStats->total_quantity_sold;
            $newStatus = $this->calculateItemStatus($currentStock, $latestPurchase?->expire_date, $item->status);

            $itemData = [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'description' => $item->description,
                'sale_price' => $item->sale_price,
                'status' => $newStatus,

                'is_mattress' => $item->is_mattress,
                'brand_id' => $item->brand_id,
                'brand_name' => $item->brand?->name,
                'mattress_type_id' => $item->mattress_type_id,
                'mattress_type_name' => $item->mattressType?->name,
                'mattress_size_id' => $item->mattress_size_id,
                'mattress_size' => $item->mattressSize?->size_code,
                'color' => $item->color,
                'warranty_period' => $item->warranty_period,

                'latest_purchase' => $latestPurchase ? [
                    'purchase_price' => $latestPurchase->purchase_price,
                    'expire_date' => $latestPurchase->expire_date,
                    'purchase_date' => $latestPurchase->created_at,
                    'quantity' => $latestPurchase->quantity
                ] : null,
                'latest_sale' => $latestSale ? [
                    'sale_price' => $latestSale->sale_price
                ] : null,
                'purchase_stats' => [
                    'total_purchases' => $purchaseStats->total_purchases ?? 0,
                    'total_quantity' => $purchaseStats->total_quantity_purchased ?? 0,
                    'average_price' => round($purchaseStats->average_purchase_price ?? 0, 2),
                    'highest_price' => $purchaseStats->highest_purchase_price ?? 0,
                    'lowest_price' => $purchaseStats->lowest_purchase_price ?? 0
                ],
                'sale_stats' => [
                    'total_sales' => $saleStats->total_sales ?? 0,
                    'total_quantity' => $saleStats->total_quantity_sold ?? 0,
                    'average_price' => round($saleStats->average_sale_price ?? 0, 2),
                    'highest_price' => $saleStats->highest_sale_price ?? 0,
                    'lowest_price' => $saleStats->lowest_sale_price ?? 0
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $itemData
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching item details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBrands()
    {
        $brands = Brand::where('active', true)->get();
        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }

    public function getMattressTypes()
    {
        $types = MattressType::where('active', true)->get();
        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }

    public function getMattressSizes()
    {
        $sizes = MattressSize::where('active', true)->get();
        return response()->json([
            'success' => true,
            'data' => $sizes
        ]);
    }

    public function fetch_inventory(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $min = $request->get('min');
        $max = $request->get('max');
        $sort_alphabetically = $request->get('sort_alphabetically');

        // Add new filters for mattress-specific options
        $brand_id = $request->get('brand_id');
        $mattress_type_id = $request->get('mattress_type_id');
        $mattress_size_id = $request->get('mattress_size_id');
        $is_mattress = $request->get('is_mattress');

        $mainQuery = Item::query();

        if ($search) {
            $mainQuery->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('mattressType', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('mattressSize', function ($query) use ($search) {
                        $query->where('size_code', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $mainQuery->where('status', $status);
        }

        // Filter by brand
        if ($brand_id) {
            $mainQuery->where('brand_id', $brand_id);
        }

        // Filter by mattress type
        if ($mattress_type_id) {
            $mainQuery->where('mattress_type_id', $mattress_type_id);
        }

        // Filter by mattress size
        if ($mattress_size_id) {
            $mainQuery->where('mattress_size_id', $mattress_size_id);
        }

        // Filter by is_mattress
        if ($is_mattress !== null) {
            $mainQuery->where('is_mattress', $is_mattress);
        }

        if ($min) {
            $mainQuery->whereHas('purchaseItems', function ($q) use ($min) {
                $q->where('quantity', '>=', $min);
            });
        }

        if ($max) {
            $mainQuery->whereHas('purchaseItems', function ($q) use ($max) {
                $q->where('quantity', '<=', $max);
            });
        }

        $query = clone $mainQuery;

        // Include relationships
        $query->with(['purchaseItems', 'saleItems', 'brand', 'mattressType', 'mattressSize']);

        if ($sort_alphabetically) {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $items = $query->get();

        // Status counts
        $total_inventory = $items->count();
        $Available_count = $items->where('status', 'Available')->count();
        $Not_Available_count = $items->where('status', 'Not Available')->count();
        $Expired_count = $items->where('status', 'Expired')->count();
        $Damage_count = $items->where('status', 'Damage')->count();
        $Sold_Out_count = $items->where('status', 'Sold Out')->count();
        $Inactive_count = $items->where('status', 'Inactive')->count();
        $Active_count = $items->where('status', 'Active')->count();

        // Get purchase and sale data
        $purchaseData = DB::table('purchase_items')
            ->select('item_id')
            ->selectRaw('SUM(quantity) as total_purchased')
            ->groupBy('item_id')
            ->pluck('total_purchased', 'item_id');

        $saleData = DB::table('sale_items')
            ->select('item_id')
            ->selectRaw('SUM(quantity) as total_sold')
            ->groupBy('item_id')
            ->pluck('total_sold', 'item_id');

        $latestPurchases = DB::table('purchase_items')
            ->select('item_id', 'purchase_price', 'expire_date', 'created_at')
            ->whereIn('item_id', $items->pluck('id'))
            ->whereRaw('created_at = (
            SELECT MAX(created_at) 
            FROM purchase_items as pi 
            WHERE pi.item_id = purchase_items.item_id
        )')
            ->get()
            ->keyBy('item_id');

        $latestSales = DB::table('sale_items')
            ->select('item_id', 'sale_price', 'created_at')
            ->whereIn('item_id', $items->pluck('id'))
            ->whereRaw('created_at = (
            SELECT MAX(created_at) 
            FROM sale_items as pi 
            WHERE pi.item_id = sale_items.item_id
        )')
            ->get()
            ->keyBy('item_id');

        $transformedItems = $items->map(function ($item) use ($purchaseData, $saleData, $latestPurchases, $latestSales) {
            $totalPurchased = $purchaseData[$item->id] ?? 0;
            $totalSold = $saleData[$item->id] ?? 0;
            $currentStock = $totalPurchased - $totalSold;

            $latestPurchase = $latestPurchases[$item->id] ?? null;
            $latestSale = $latestSales[$item->id] ?? null;

            $newStatus = $this->calculateItemStatus($currentStock, $latestPurchase?->expire_date, $item->status);

            if ($newStatus !== $item->status) {
                DB::table('items')
                    ->where('id', $item->id)
                    ->update(['status' => $newStatus]);
            }

            // Calculate profit per item
            $profitPerItem = $item->sale_price - ($latestPurchase?->purchase_price ?? 0);

            // Generate display name based on item type
            $displayName = $item->name;
            if ($item->is_mattress && ($item->brand || $item->mattressType || $item->mattressSize)) {
                $brandName = $item->brand ? $item->brand->name : '';
                $typeName = $item->mattressType ? $item->mattressType->code : '';
                $sizeCode = $item->mattressSize ? $item->mattressSize->size_code : '';
                $displayName = trim("{$brandName}");
            }

            return [
                'id' => $item->id,
                'sku' => $item->sku ?? 'N/A',
                'name' => $item->name,
                'display_name' => $displayName,
                'description' => $item->description,
                'category' => $item->category ?? 'General',
                'sale_price' => $item->sale_price,
                'status' => $newStatus,
                'current_stock' => $currentStock,
                'expire_date' => $latestPurchase?->expire_date,
                'is_mattress' => $item->is_mattress,
                'brand' => $item->brand ? [
                    'id' => $item->brand->id,
                    'name' => $item->brand->name
                ] : null,
                'mattress_type' => $item->mattressType ? [
                    'id' => $item->mattressType->id,
                    'name' => $item->mattressType->name,
                    'code' => $item->mattressType->code
                ] : null,
                'mattress_size' => $item->mattressSize ? [
                    'id' => $item->mattressSize->id,
                    'size_code' => $item->mattressSize->size_code,
                    'width' => $item->mattressSize->width,
                    'length' => $item->mattressSize->length
                ] : null,
                'color' => $item->color,
                'warranty_period' => $item->warranty_period,
                'stats' => [
                    'total_purchased' => $totalPurchased,
                    'total_sold' => $totalSold,
                    'latest_purchase_price' => $latestPurchase?->purchase_price,
                    'latest_sales_price' => $latestSale?->sale_price,
                    'latest_purchase_date' => $latestPurchase?->created_at,
                    'latest_sales_date' => $latestSale?->created_at,
                    'profit_per_item' => $profitPerItem
                ]
            ];
        });

        if ($request->filled('min_quantity') || $request->filled('max_quantity')) {
            $minQty = $request->input('min_quantity', 0);
            $maxQty = $request->input('max_quantity', PHP_INT_MAX);

            $transformedItems = $transformedItems->filter(function ($item) use ($minQty, $maxQty) {
                return $item['current_stock'] >= $minQty && $item['current_stock'] <= $maxQty;
            });
        }

        return response()->json([
            'success' => true,
            'items' => $transformedItems->values(),
            'counts' => [
                'total_inventory' => $total_inventory,
                'Available_count' => $Available_count,
                'Not_Available_count' => $Not_Available_count,
                'Expired_count' => $Expired_count,
                'Damage_count' => $Damage_count,
                'Sold_Out_count' => $Sold_Out_count,
                'Inactive_count' => $Inactive_count,
                'Active_count' => $Active_count,
            ]
        ]);
    }

    private function calculateItemStatus($currentStock, $expireDate, $currentStatus)
    {
        if ($currentStock <= 0) {
            return 'Sold Out';
        }

        if ($expireDate && now()->gt($expireDate)) {
            return 'Expired';
        }

        // Keep the current status if it's Damage or Inactive as these are manual states
        if (in_array($currentStatus, ['Damage', 'Inactive'])) {
            return $currentStatus;
        }

        return 'Available';
    }

    public function delete_item($id)
    {
        try {
            DB::beginTransaction();

            $item = Item::findOrFail($id);

            $purchaseItems = PurchaseItem::where('item_id', $id)->with('purchase')->get();
            $saleItems = SaleItem::where('item_id', $id)->with('sale')->get();

            // Process purchase items
            foreach ($purchaseItems as $purchaseItem) {
                $purchase = $purchaseItem->purchase;

                $itemAmount = ($purchaseItem->purchase_price * $purchaseItem->quantity);
                $itemDiscount = $purchaseItem->discount ?? 0;
                $itemTotal = $itemAmount - $itemDiscount;

                $isOnlyItem = $purchase->purchaseItems()->count() === 1;

                if ($isOnlyItem) {
                    Transaction::where('purchase_id', $purchase->id)->delete();
                    $purchase->delete();
                } else {
                    $purchase->update([
                        'total_amount' => $purchase->total_amount - $itemTotal,
                        'total_discount' => $purchase->total_discount - $itemDiscount,
                        'dept' => $purchase->dept - ($itemTotal - ($purchase->paid * ($itemTotal / $purchase->total_amount)))
                    ]);

                    $purchaseItem->delete();
                }
            }

            // Process sale items
            foreach ($saleItems as $saleItem) {
                $sale = $saleItem->sale;

                $itemAmount = ($saleItem->sale_price * $saleItem->quantity);
                $itemDiscount = $saleItem->discount ?? 0;
                $itemTotal = $itemAmount - $itemDiscount;

                $isOnlyItem = $sale->saleItems()->count() === 1;

                if ($isOnlyItem) {
                    Transaction::where('sale_id', $sale->id)->delete();
                    $sale->delete();
                } else {
                    $sale->update([
                        'total_amount' => $sale->total_amount - $itemTotal,
                        'total_discount' => $sale->total_discount - $itemDiscount,
                        'dept' => $sale->dept - ($itemTotal - ($sale->paid * ($itemTotal / $sale->total_amount)))
                    ]);
                    $saleItem->delete();
                }
            }

            // Clear relationships before deleting
            $item->update([
                'brand_id' => null,
                'mattress_type_id' => null,
                'mattress_size_id' => null,
            ]);

            // Delete the item
            $item->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Item and associated records deleted successfully',
                'data' => [
                    'affected_purchases' => $purchaseItems->pluck('purchase_id')->unique(),
                    'affected_sales' => $saleItems->pluck('sale_id')->unique()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show_items_stock(Request $request)
    {
        // Get request parameters
        $itemIds = $request->input('item_ids', '');
        $status = $request->input('status', '');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $minQuantity = $request->input('min_quantity');
        $maxQuantity = $request->input('max_quantity');
        $search = $request->input('search', '');

        // Parse item IDs
        $itemIdsArray = !empty($itemIds) ? explode(',', $itemIds) : [];

        // Start query builder with relationships
        $query = Item::with(['brand', 'mattressType', 'mattressSize']);

        // Apply filters
        if (!empty($itemIdsArray)) {
            $query->whereIn('id', $itemIdsArray);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('mattressType', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('mattressSize', function ($query) use ($search) {
                        $query->where('size_code', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Get all matching items
        $items = $query->get();

        // Filter items by stock quantity manually (since it's a computed attribute)
        $filteredItems = $items->filter(function ($item) use ($minQuantity, $maxQuantity) {
            $stock = $item->getCurrentStockAttribute();

            if ($minQuantity !== null && $stock < (int)$minQuantity) {
                return false;
            }

            if ($maxQuantity !== null && $stock > (int)$maxQuantity) {
                return false;
            }

            return true;
        });

        // Prepare the result data
        $result = [];
        $totalStock = 0;
        $totalLatestValue = 0;
        $totalOldestValue = 0;

        foreach ($filteredItems as $item) {
            $currentStock = $item->getCurrentStockAttribute();
            $totalStock += $currentStock;

            // Get latest purchase price
            $latestPurchase = $item->purchaseItems()
                ->orderBy('created_at', 'desc')
                ->first();
            $latestPrice = $latestPurchase ? $latestPurchase->purchase_price : 0;

            // Get oldest purchase price
            $oldestPurchase = $item->purchaseItems()
                ->orderBy('created_at', 'asc')
                ->first();
            $oldestPrice = $oldestPurchase ? $oldestPurchase->purchase_price : 0;

            // Calculate stock values
            $latestValue = $currentStock * $latestPrice;
            $oldestValue = $currentStock * $oldestPrice;

            $totalLatestValue += $latestValue;
            $totalOldestValue += $oldestValue;

            // For date filtering of transactions if specified
            $purchaseQuery = $item->purchaseItems();
            $saleQuery = $item->saleItems();

            if ($fromDate) {
                $purchaseQuery->where('created_at', '>=', $fromDate);
                $saleQuery->where('created_at', '>=', $fromDate);
            }

            if ($toDate) {
                $purchaseQuery->where('created_at', '<=', $toDate . ' 23:59:59');
                $saleQuery->where('created_at', '<=', $toDate . ' 23:59:59');
            }

            $purchaseCount = $purchaseQuery->sum('quantity');
            $saleCount = $saleQuery->sum('quantity');

            // Add to result array
            $result[] = [
                'id' => $item->id,
                'sku' => $item->sku ?? 'N/A',
                'name' => $item->name,
                'description' => $item->description,
                'current_stock' => $currentStock,
                'latest_price' => $latestPrice,
                'oldest_price' => $oldestPrice,
                'latest_value' => $latestValue,
                'oldest_value' => $oldestValue,
                'status' => $item->status,
                'purchases' => $purchaseCount,
                'sales' => $saleCount,
                'is_mattress' => $item->is_mattress,
                'brand' => $item->brand ? [
                    'id' => $item->brand->id,
                    'name' => $item->brand->name
                ] : null,
                'mattress_type' => $item->mattressType ? [
                    'id' => $item->mattressType->id,
                    'name' => $item->mattressType->name,
                    'code' => $item->mattressType->code
                ] : null,
                'mattress_size' => $item->mattressSize ? [
                    'id' => $item->mattressSize->id,
                    'size_code' => $item->mattressSize->size_code,
                    'width' => $item->mattressSize->width,
                    'length' => $item->mattressSize->length
                ] : null,
                'color' => $item->color,
                'warranty_period' => $item->warranty_period
            ];
        }

        // Calculate averages
        $avgLatestPrice = $totalStock > 0 ? $totalLatestValue / $totalStock : 0;
        $avgOldestPrice = $totalStock > 0 ? $totalOldestValue / $totalStock : 0;

        // Return JSON response
        return response()->json([
            'items' => $result,
            'stats' => [
                'total_items' => count($result),
                'total_stock' => $totalStock,
                'total_latest_value' => $totalLatestValue,
                'total_oldest_value' => $totalOldestValue,
                'avg_latest_price' => $avgLatestPrice,
                'avg_oldest_price' => $avgOldestPrice,
            ]
        ]);
    }

    /**
     * Updated fetch_item method to support searching by brand, mattress type, and size
     * It also returns enhanced search results with these details
     */
    public function fetch_item(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');

            // Enhanced search using eager loading and searching across related models
            $items = Item::with(['brand', 'mattressType', 'mattressSize'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('sku', 'LIKE', "%{$query}%")
                        ->orWhereHas('brand', function ($brandQuery) use ($query) {
                            $brandQuery->where('name', 'LIKE', "%{$query}%");
                        })
                        ->orWhereHas('mattressType', function ($typeQuery) use ($query) {
                            $typeQuery->where('name', 'LIKE', "%{$query}%")
                                ->orWhere('code', 'LIKE', "%{$query}%");
                        })
                        ->orWhereHas('mattressSize', function ($sizeQuery) use ($query) {
                            $sizeQuery->where('size_code', 'LIKE', "%{$query}%");
                        });
                })
                ->get();

            $output = '<ul class="item-search-results">';

            if ($items->count() > 0) {
                foreach ($items as $item) {
                    $totalPurchased = $item->purchaseItems()->sum('quantity') ?? 0;
                    $totalSold = $item->saleItems()->sum('quantity') ?? 0;
                    $currentStock = $totalPurchased - $totalSold;

                    // Determine stock status color
                    $stockColorClass = 'text-green-500';
                    if ($currentStock <= 0) {
                        $stockColorClass = 'text-red-500';
                    } elseif ($currentStock < 5) {
                        $stockColorClass = 'text-yellow-500';
                    }

                    // Format item info for display
                    $brandDisplay = $item->brand ? $item->brand->name : '';
                    $typeDisplay = $item->mattressType ? $item->mattressType->name : '';
                    $sizeDisplay = $item->mattressSize ? $item->mattressSize->size_code : '';

                    // Store details in data attributes for use when item is selected
                    $output .= '
                <li value="' . $item->id . '" 
                    class="items_lists p-2 border-b hover:bg-gray-100 cursor-pointer flex justify-between items-center w-fit" 
                    data-id="' . $item->id . '" 
                    data-brand="' . $brandDisplay . '"
                    data-type="' . $typeDisplay . '"
                    data-size="' . $sizeDisplay . '">
                    <div class="flex-1">
                        <div class="font-medium item-name">' . $item->name . '</div>
                        <div class="text-xs text-gray-600">
                            ' . ($brandDisplay ? '<span class="item-brand">' . $brandDisplay . '</span>' : '') . '
                            ' . ($typeDisplay ? '<span class="item-type">' . $typeDisplay . '</span>' : '') . '
                            ' . ($sizeDisplay ? '<span class="item-size">' . $sizeDisplay . '</span>' : '') . '
                        </div>
                    </div>
                </li>';
                }
            } else {
                $output .= '<li class="no-results p-2 text-center text-gray-500">No items found</li>';
            }

            $output .= '</ul>';
            echo $output;
        }
    }

    /**
     * Updated find_item_details method to include brand, mattress type, and size information
     */
    public function find_item_details(Request $request)
    {
        $item = Item::with(['purchaseItems', 'saleItems', 'brand', 'mattressType', 'mattressSize'])
            ->findOrFail($request->id);

        // Format the full name with brand, type and size
        $formattedName = $item->name;

        if ($item->is_mattress) {
            $brandName = $item->brand ? $item->brand->name : '';
            $typeName = $item->mattressType ? $item->mattressType->name : '';
            $sizeCode = $item->mattressSize ? $item->mattressSize->size_code : '';

            // Update the formatted name to include these details
            $formattedName = trim("$brandName $typeName $sizeCode");
        }

        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'formatted_name' => $formattedName,
            'sku' => $item->sku,
            'description' => $item->description,
            'sale_price' => $item->sale_price,
            'status' => $item->status,
            'current_stock' => $item->getCurrentStockAttribute(),
            'latest_purchase_price' => $item->getLatestPurchasePriceAttribute(),
            'latest_sale_price' => $item->getLatestSalePriceAttribute(),
            'latest_discount' => $item->getLatestpurchase_DiscountAttribute(),
            'latest_sale_discount' => $item->getLatestSale_DiscountAttribute(),
            'brand' => $item->brand ? $item->brand->name : null,
            'brand_id' => $item->brand_id,
            'mattress_type' => $item->mattressType ? $item->mattressType->name : null,
            'mattress_type_id' => $item->mattress_type_id,
            'mattress_size' => $item->mattressSize ? $item->mattressSize->size_code : null,
            'mattress_size_id' => $item->mattress_size_id,
            'is_mattress' => $item->is_mattress,
        ]);
    }
}
