<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use App\Models\Product;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/secure-data', function(Request $request) {
    // Manual check API key
    if ($request->header('senikolor-api-key') !== env('API_KEY')) {
        return response()->json(['error' => 'Invalid API Key'], 401);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'List Produk (API Key)',
        'data' => Product::all(),
        'authenticated_via' => 'API Key'
    ]);
});

// JWT Protected Routes
Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    // Products - REST API Standard
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);
    Route::post('/products', [ProductApiController::class, 'store']);
    
    // Update methods
    Route::put('/products/{id}', [ProductApiController::class, 'update']);
    Route::post('/products/{id}/update', [ProductApiController::class, 'update']);
    
    // Delete method
    Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);
});

// Basic Auth - HANYA GET (Read Only)
Route::middleware('auth.basic')->group(function() {
    Route::get('/products-basic', [ProductApiController::class, 'indexBasic']);
    Route::get('/products-basic/{id}', [ProductApiController::class, 'showBasic']);
});