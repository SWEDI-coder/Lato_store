<?php

use App\Http\Controllers\BusinessStatisticsController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Welcomecontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', [Welcomecontroller::class, 'welcome'])->name('welcome');

// items controller
Route::get('/Items', [ItemsController::class, 'Items'])->name('Items');
Route::get('/edit_item_details/{id}', [ItemsController::class, 'edit_item_details'])->name('edit_item_details');
Route::get('/fetch_Items_Counts', [ItemsController::class, 'fetch_Items_Counts'])->name('fetch_Items_Counts');
Route::post('/fetch_item', [ItemsController::class, 'fetch_item'])->name('fetch_item');
Route::get('find_item_details', [ItemsController::class, 'find_item_details'])->name('find_item_details');

Route::post('/store_item', [ItemsController::class, 'store_item'])->name('store_item');
Route::get('/fetch_inventory', [ItemsController::class, 'fetch_inventory'])->name('fetch_inventory');
Route::get('/edit_item_details/{id}', [ItemsController::class, 'edit_item_details'])->name('edit_item_details');
Route::put('/update_item/{id}', [ItemsController::class, 'update_item'])->name('update_item');
Route::delete('/delete_item/{id}', [ItemsController::class, 'delete_item'])->name('delete_item');
Route::get('/fetch_all_items', [ItemsController::class, 'fetch_all_items'])->name('fetch_all_items');

// sales controller 
Route::get('/Sales', [SalesController::class, 'Sales'])->name('Sales');
Route::get('fetch_Customer_Sale', [SalesController::class, 'fetch_Customer_Sale'])->name('fetch_Customer_Sale');
Route::post('/record_Sale', [SalesController::class, 'record_Sale'])->name('record_Sale');
Route::get('/fetch_Sales', [SalesController::class, 'fetch_Sales'])->name('fetch_Sales');
Route::get('/getSales/{id}', [SalesController::class, 'getSales'])->name('getSales');
Route::put('/update_Sales/{id}', [SalesController::class, 'update_Sales'])->name('update_Sales');
Route::delete('/delete_sale/{id}', [SalesController::class, 'delete_sale'])->name('delete_sale');
Route::get('/get_sale/{id}', [SalesController::class, 'get_sale'])->name('get_sale');

// purchases controller 
Route::get('/Purchases_orders', [PurchasesController::class, 'Purchases_orders'])->name('Purchases_orders');
Route::post('/record_purchases', [PurchasesController::class, 'record_Purchases'])->name('record_Purchases');
Route::get('/fetch_purchases', [PurchasesController::class, 'fetch_purchases'])->name('fetch_purchases');
Route::delete('/delete_purchase/{id}', [PurchasesController::class, 'delete_purchase'])->name('delete_purchase');
Route::get('/getPurchase/{id}', [PurchasesController::class, 'getPurchase'])->name('getPurchase');
Route::put('/update_purchase/{id}', [PurchasesController::class, 'update_purchase'])->name('update_purchase');
Route::get('fetch_supplier_purchases', [PurchasesController::class, 'fetch_supplier_purchases'])->name('fetch_supplier_purchases');
Route::get('/get_purchase/{id}', [PurchasesController::class, 'get_purchase'])->name('get_purchase');


// part controller 
Route::get('/Customers', [PartController::class, 'Parties'])->name('Customers');
Route::post('/store_party', [PartController::class, 'store_party'])->name('store_party');
Route::get('/fetch_parties', [PartController::class, 'fetch_parties'])->name('fetch_parties');
Route::get('/edit_part/{id}', [PartController::class, 'edit_part'])->name('edit_part');
Route::put('/update_part/{id}', [PartController::class, 'update_part'])->name('update_part');
Route::delete('/delete_part/{id}', [PartController::class, 'delete_part'])->name('delete_part');
Route::post('/search_supplier', [PartController::class, 'search_supplier'])->name('search_supplier');
Route::post('/search_Customer', [PartController::class, 'search_Customer'])->name('search_Customer');
Route::get('find_supplier_balance', [PartController::class, 'find_supplier_balance'])->name('find_supplier_balance');
Route::get('find_Customer_balance', [PartController::class, 'find_Customer_balance'])->name('find_Customer_balance');
Route::post('/search_part', [PartController::class, 'search_part'])->name('search_part');
Route::get('/find_part_balance', [PartController::class, 'find_part_balance'])->name('find_part_balance');
Route::get('/fetch_part_transactions', [PartController::class, 'fetch_part_transactions'])->name('fetch_part_transactions');

// transaction controller
Route::post('/record_payment_out', [TransactionController::class, 'record_payment_out'])->name('record_payment_out');
Route::post('/record_payment_in', [TransactionController::class, 'record_payment_in'])->name('record_payment_in');
Route::get('/fetch_transactions', [TransactionController::class, 'fetch_transactions'])->name('fetch_transactions');
Route::get('/populate_transaction/{id}', [TransactionController::class, 'populate_transaction'])
    ->name('populate_transaction');
Route::put('/transaction_update/{id}', [TransactionController::class, 'transaction_update'])
    ->name('transaction_update');
Route::delete('/delete_transaction/{id}', [TransactionController::class, 'delete_transaction'])->name('delete_transaction');
// Add this route to your routes/web.php or routes/api.php file
Route::get('getBusinessStatistics', [BusinessStatisticsController::class, 'getBusinessStatistics'])->name('getBusinessStatistics');