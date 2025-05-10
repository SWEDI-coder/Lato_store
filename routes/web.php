<?php

use App\Http\Controllers\BusinessStatisticsController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Welcomecontroller;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SmsPasswordResetController;

// Login Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('loginUser');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/website', [WelcomeController::class, 'website'])->name('website');

// Registration Routes
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('store_user', [RegisterController::class, 'store_user'])->name('store_user');

// SMS Password Reset Routes
Route::get('forgot-password', [SmsPasswordResetController::class, 'showSendCodeForm'])
    ->name('password.sms.request');

Route::post('forgot-password', [SmsPasswordResetController::class, 'sendResetCode'])
    ->name('password.sms.send');

Route::get('verify-code', [SmsPasswordResetController::class, 'showVerifyCodeForm'])
    ->name('password.sms.verify.form');

Route::post('verify-code', [SmsPasswordResetController::class, 'verifyCode'])
    ->name('password.sms.verify');

Route::get('reset-password/{token}', [SmsPasswordResetController::class, 'showResetForm'])
    ->name('password.sms.reset.form');

Route::post('reset-password', [SmsPasswordResetController::class, 'reset'])
    ->name('password.sms.update');

    // Phone verification routes
Route::get('phone-verify', [RegisterController::class, 'showVerificationForm'])->name('phone.verify');
Route::post('phone-verify', [RegisterController::class, 'verifyCode'])->name('phone.verify.submit');
Route::post('phone-resend', [RegisterController::class, 'resendCode'])->name('phone.verify.resend');

    Route::get('/', [Welcomecontroller::class, 'welcome'])->name('welcome');

    // items controller
    Route::post('/fetch_item', [ItemsController::class, 'fetch_item'])->name('fetch_item');
    Route::get('find_item_details', [ItemsController::class, 'find_item_details'])->name('find_item_details');
    Route::post('/store_item', [ItemsController::class, 'store_item'])->name('store_item');
    Route::get('/fetch_inventory', [ItemsController::class, 'fetch_inventory'])->name('fetch_inventory');
    Route::get('/edit_item_details/{id}', [ItemsController::class, 'edit_item_details'])->name('edit_item_details');
    Route::put('/update_item/{id}', [ItemsController::class, 'update_item'])->name('update_item');
    Route::delete('/delete_item/{id}', [ItemsController::class, 'delete_item'])->name('delete_item');
    Route::get('/items/stock', [ItemsController::class, 'show_items_stock'])->name('items.stock');

    // Reference data endpoints
Route::get('/brands', [ItemsController::class, 'getBrands'])->name('brands');
Route::get('/mattress-types', [ItemsController::class, 'getMattressTypes'])->name('mattress_types');
Route::get('/mattress-sizes', [ItemsController::class, 'getMattressSizes'])->name('mattress_sizes');

    // sales controller 
    Route::get('fetch_Customer_Sale', [SalesController::class, 'fetch_Customer_Sale'])->name('fetch_Customer_Sale');
    Route::post('/record_Sale', [SalesController::class, 'record_Sale'])->name('record_Sale');
    Route::get('/fetch_Sales', [SalesController::class, 'fetch_Sales'])->name('fetch_Sales');
    Route::get('/getSales/{id}', [SalesController::class, 'getSales'])->name('getSales');
    Route::put('/update_Sales/{id}', [SalesController::class, 'update_Sales'])->name('update_Sales');
    Route::delete('/delete_sale/{id}', [SalesController::class, 'delete_sale'])->name('delete_sale');
    Route::get('/get_sale/{id}', [SalesController::class, 'get_sale'])->name('get_sale');

    // purchases controller 
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

    Route::get('fetch_employees', [RegisterController::class, 'fetch_employees'])->name('fetch_employees');
    Route::post('/add_employee', [RegisterController::class, 'add_employee'])->name('add_employee');
    Route::post('/update_employee/{id}', [RegisterController::class, 'update_employee'])->name('update_employee');
    Route::get('/get_employee/{id}', [RegisterController::class, 'get_employee'])->name('get_employee');
    Route::delete('/delete_employee/{id}', [RegisterController::class, 'delete_employee'])->name('delete_employee');
    Route::post('/change-password', [RegisterController::class, 'changePassword'])->name('change.password');
