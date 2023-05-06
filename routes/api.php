<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Home\ProductController;
use App\Http\Controllers\Api\Home\ProfileController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\TransactionController;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:passport')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class , 'register'])->name('register');
Route::post('/login', [AuthController::class , 'login'])->name('login');
Route::post('/logout', [AuthController::class , 'logout'])->middleware('auth:api')->name('logout');;
Route::post('/check', [AuthController::class , 'check'])->middleware('auth:api');

Route::prefix('v1')->name('v1.')->group(function(){


    Route::get('/products', [ProductController::class , 'index'])->name('home.product.index');
    Route::get('/products/{product:id}', [ProductController::class , 'show'])->name('home.product.show');
    Route::post('/products/{product:id}/isPurchased', [ProductController::class , 'isPurchased'])->middleware('auth:api')->name('home.product.isPurchased');

    Route::post('/getUserCart', [ProfileController::class , 'getUserCart'])->middleware('auth:api')->name('profile.cart.index');
    Route::post('/addUserCart', [ProfileController::class , 'addUserCart'])->middleware('auth:api')->name('cart.add');
    Route::post('/getUserProducts', [ProfileController::class , 'getUserProducts'])->middleware('auth:api')->name('profile.user.products');

    Route::post('/goToPayment', [PaymentController::class , 'goToPayment'])->middleware('auth:api');
    Route::post('/paystare/callback', [PaymentController::class , 'paymentCallback'])->name('paystar.callback');
    Route::get('/transaction/{transaction:transaction_id}', [TransactionController::class , 'show'])->name('transaction.show');

});

Route::get('/test', function(Request $request){

});
