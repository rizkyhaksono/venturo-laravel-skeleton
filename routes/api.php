<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ReportSalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api;" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::get('/', [SiteController::class, 'index']);

    Route::post('/auth/login', [AuthController::class, 'login']); //->middleware(['signature']);
    Route::post('/auth/logout', [AuthController::class, 'logout']); //->middleware(['signature']);
    Route::get('/auth/profile', [AuthController::class, 'profile'])->middleware(['auth.api']);

    Route::get('/users', [UserController::class, 'index']); //->middleware(['auth.api', 'role:user.view']);
    Route::get('/users/{id}', [UserController::class, 'show']); //->middleware(['auth.api', 'role:user.view']);
    Route::post('/users', [UserController::class, 'store']); //->middleware(['auth.api', 'role:user.create|roles.view']);
    Route::put('/users/{id}', [UserController::class, 'update']); //->middleware(['auth.api', 'role:user.update||roles.view']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']); //->middleware(['auth.api', 'role:user.delete']);

    Route::get('/roles', [RoleController::class, 'index']); //->middleware(['auth.api', 'role:roles.view']);
    Route::get('/roles/{id}', [RoleController::class, 'show']); //->middleware(['auth.api', 'role:roles.view']);
    Route::post('/roles', [RoleController::class, 'store']); //->middleware(['auth.api', 'role:roles.create']);
    Route::put('/roles', [RoleController::class, 'update']); //->middleware(['auth.api', 'role:roles.update']);
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']); //->middleware(['auth.api', 'role:roles.delete']);

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::put('/customers', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    Route::get('/categories', [ProductCategoryController::class, 'index']);
    Route::get('/categories/{id}', [ProductCategoryController::class, 'show']);
    Route::post('/categories', [ProductCategoryController::class, 'store']);
    Route::put('/categories', [ProductCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [ProductCategoryController::class, 'destroy']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/sales/{id}', [SaleController::class, 'show']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::put('/sales/{id}', [SaleController::class, 'update']);
    Route::delete('/sales/{id}', [SaleController::class, 'destroy']);

    Route::get('/sale-customer', [SaleController::class, 'saleCustomer']);
    Route::get('/report/sales-menu', [ReportSalesController::class, 'viewSalesCategories']);
    Route::get('/download/sales-category', [ReportSalesController::class, 'viewSalesCategories']);
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});
