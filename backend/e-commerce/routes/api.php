<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// ENDPOINTS PÚBLICOS (Sin autenticación)

// Auth - Registro y Login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Productos - Solo lectura (público)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);


// ENDPOINTS PROTEGIDOS (Con auth:sanctum)

Route::middleware('auth:sanctum')->group(function () {

    // Auth - Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // PRODUCTOS (CRUD - Crear, Leer, Actualizar, Eliminar)

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // ÓRDENES (Compras)

    Route::post('/orders', [OrderController::class, 'store']);           // Crear orden
    Route::get('/orders', [OrderController::class, 'index']);            // Mis órdenes
    Route::get('/orders/{id}', [OrderController::class, 'show']);        // Detalle de orden
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']); // Cancelar orden


    // PAGOS (Stripe)

    Route::post('/payments', [PaymentController::class, 'store']);       // Procesar pago
    Route::get('/payments/{orderId}', [PaymentController::class, 'show']); // Ver estado del pago
    Route::post('/payments/{paymentId}/refund', [PaymentController::class, 'refund']); // Reembolsar

});