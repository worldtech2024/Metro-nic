<?php

use App\Http\Controllers\Api\AuthAdminController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\HiddenCostController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductGroupController;
use App\Http\Middleware\EnsureUser;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthAdminController::class, 'register']);
Route::post('/login', [AuthAdminController::class, 'login']);
Route::post('/verify-otp', [AuthAdminController::class, 'verifyOTP'])->name('verify-otp');
Route::post('/forget-password', [AuthAdminController::class, 'forgetPassword'])->name('forget-password');
Route::post('/reset-password', [AuthAdminController::class, 'changePassword']);
Route::post('/resend-code', [AuthAdminController::class, 'resetCode']);
// -----------------------Auth----------------------------
Route::get('/products/export', [ProductController::class, 'export']);
Route::get('/customers/export/', [CustomerController::class, 'export']);

Route::middleware(['auth:sanctum', EnsureUser::class])->group(function () {
    Route::post('/logout', [AuthAdminController::class, 'logout']);
    Route::get('/profile', [AuthAdminController::class, 'profile']);
    Route::put('/profile', [AuthAdminController::class, 'updateProfile']);

    Route::get('employees', [AuthAdminController::class, 'index']);
    Route::put('employees/{admin}', [AuthAdminController::class, 'update']);

    // -----------------Countries-----------------
    Route::apiResource('countries', CountryController::class);

    // -----------------Brands-----------------
    Route::apiResource('brands', BrandController::class);

    // -----------------Products-----------------
    Route::apiResource('products', ProductController::class);
    Route::post('/products/import', [ProductController::class, 'import']);

    // -----------------Hidden Costs-----------------
    Route::apiResource('hidden-costs', HiddenCostController::class);

    // -----------------Product Groups-----------------
    Route::apiResource('product-groups', ProductGroupController::class);

    // ----------------Customer Groups-----------------
    Route::post('/customers/import', [CustomerController::class, 'import']);
    Route::apiResource('customers', CustomerController::class);

    // -----------------Orders-----------------


    Route::get('orders/completed', [OrderController::class, 'myCompletedOrders']);
    Route::get('orders/employee', [OrderController::class, 'myOrders']);
    Route::get('orders/purchases', [OrderController::class, 'myOderBuy']);
    Route::get('orders/installs', [OrderController::class, 'myOderInstall']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/orders/add-invoice', [OrderController::class, 'addInvoice']);
    Route::put('/orders/{order}', [OrderController::class, 'updateStatus']);
    //--------------------Dashboard------------------
    Route::get('/dashboard', [HomeController::class, 'dashboard']);

});

