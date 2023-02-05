<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return [
        'subtotal' => $subtotal = round(rand(100, 999) / 3, 2),
        'commission_owed' => round($subtotal * 0.1, 2),
        round(rand(1, 5) / 10, 1)
    ];
});

Route::post('/webhook', WebhookController::class)->name('webhook');

Route::get('/merchant/order-stats', [MerchantController::class, 'orderStats'])->name('merchant.order-stats');
