<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Categories - conditionally register based on config
    if (config('fitur.admin.categories', true)) {
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    }

    // Products - conditionally register based on config
    if (config('fitur.admin.products', true)) {
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    }

    // Transactions - conditionally register based on config
    if (config('fitur.admin.transactions', true)) {
        Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
        Route::post('transactions/{transaction}/complete', [\App\Http\Controllers\Admin\TransactionController::class, 'complete'])->name('transactions.complete');
        Route::delete('transactions/{transaction}', [\App\Http\Controllers\Admin\TransactionController::class, 'deleteTransaction'])->name('transactions.delete');
        Route::post('transactions/delete-user-history', [\App\Http\Controllers\Admin\TransactionController::class, 'deleteUserHistory'])->name('transactions.delete-user-history');
    }

    // Reports - conditionally register based on config
    if (config('fitur.admin.reports', true)) {
        Route::get('reports', [\App\Http\Controllers\Admin\TransactionController::class, 'reports'])->name('reports');
    }
});

// User Routes
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('user.pos');
    })->name('dashboard');

    // POS
    Route::get('/pos', [App\Http\Controllers\User\POSController::class, 'index'])->name('pos');
    Route::post('/checkout', [App\Http\Controllers\User\POSController::class, 'checkout'])->name('checkout');

    // Payment
    Route::get('/payment', [App\Http\Controllers\User\POSController::class, 'payment'])->name('payment');
    Route::post('/payment/process', [App\Http\Controllers\User\POSController::class, 'processPayment'])->name('payment.process');

    // Receipt
    Route::get('/receipt/{transaction}', [App\Http\Controllers\User\POSController::class, 'receipt'])->name('receipt');
    Route::get('/receipt/{transaction}/pdf', [App\Http\Controllers\User\POSController::class, 'downloadPdf'])->name('receipt.pdf');

    // Cart
    Route::get('/cart', [App\Http\Controllers\User\CartController::class, 'index'])->name('cart');
    Route::post('/cart/decrease-stock', [App\Http\Controllers\User\CartController::class, 'decreaseStock'])->name('cart.decrease-stock');
    Route::post('/cart/increase-stock', [App\Http\Controllers\User\CartController::class, 'increaseStock'])->name('cart.increase-stock');
    Route::post('/cart/print-invoice', [App\Http\Controllers\User\CartController::class, 'printInvoice'])->name('cart.print-invoice');

    // History
    Route::get('/history', [App\Http\Controllers\User\HistoryController::class, 'index'])->name('history');
    Route::get('/history/{transaction}/print-invoice', [App\Http\Controllers\User\HistoryController::class, 'printInvoice'])->name('history.print-invoice');
});

require __DIR__.'/auth.php';
