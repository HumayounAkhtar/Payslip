<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [ReceiptController::class, 'showEditor'])->name('editor');
    Route::post('/receipt/generate', [ReceiptController::class, 'generateReceipt'])->name('generate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
