<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RetailStoreController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);

    // Acheteur et Gestionnaire : consultation des produits, commandes, catégories, fournisseurs et magasins (read-only)
    Route::middleware('role:acheteur,gestionnaire')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{product}', [ProductController::class, 'show']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/statuses', [OrderController::class, 'getOrderStatuses']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/suppliers', [SupplierController::class, 'index']);
    });

    // Acheteur uniquement : création de commandes et consultation
    Route::middleware('role:acheteur')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
    });

    // Gestionnaire uniquement : CRUD des produits, commandes, catégories, fournisseurs et magasins 
    Route::middleware('role:gestionnaire')->group(function () {
        Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('orders', OrderController::class)->only(['update', 'destroy']);
        Route::apiResource('categories', CategoryController::class)->except(['index']);
        Route::apiResource('suppliers', SupplierController::class)->except(['index']);
        Route::apiResource('retail-stores', RetailStoreController::class);
    });

    // Admin + Gestionnaire: CRUD des utilisateurs (seulement acheteurs pour les gestionnaires)
    Route::middleware('role:gestionnaire,admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // Administrateur uniquement : gestion des rôles
    Route::middleware('role:admin')->group(function () {
        Route::get('/users-roles', [UserController::class, 'getUserRoles']);
    });
});
