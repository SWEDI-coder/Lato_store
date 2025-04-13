<?php

use App\Http\Controllers\ItemsController;
use App\Http\Controllers\Welcomecontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', [Welcomecontroller::class, 'welcome'])->name('welcome');

Route::post('/store_item', [ItemsController::class, 'store_item'])->name('store_item');
Route::get('/fetch_inventory', [ItemsController::class, 'fetch_inventory'])->name('fetch_inventory');
Route::get('/edit_item_details/{id}', [ItemsController::class, 'edit_item_details'])->name('edit_item_details');
Route::put('/update_item/{id}', [ItemsController::class, 'update_item'])->name('update_item');

