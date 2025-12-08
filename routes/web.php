<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/dashboard', function () {
        $totalCategories = \App\Models\Category::count();
        $totalProducts = \App\Models\Product::count();
        $pendingOrders = \App\Models\Transaction::where('status', 'pending')->count();
        $totalRevenue = \App\Models\Transaction::where('status', 'completed')->sum('total_price');

        return view('admin.dashboard', compact('totalCategories', 'totalProducts', 'pendingOrders', 'totalRevenue'));
    })->name('dashboard');

    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

    // Transactions
    Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::post('transactions/{transaction}/complete', [\App\Http\Controllers\Admin\TransactionController::class, 'complete'])->name('transactions.complete');
    Route::get('reports', [\App\Http\Controllers\Admin\TransactionController::class, 'reports'])->name('reports');
});

// User Routes
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('user.pos');
    })->name('dashboard');

    // POS
    Route::get('/pos', [App\Http\Controllers\User\POSController::class, 'index'])->name('pos');
    Route::post('/checkout', [App\Http\Controllers\User\POSController::class, 'checkout'])->name('checkout');

    // Cart
    Route::get('/cart', [App\Http\Controllers\User\CartController::class, 'index'])->name('cart');
    Route::post('/cart/decrease-stock', [App\Http\Controllers\User\CartController::class, 'decreaseStock'])->name('cart.decrease-stock');
    Route::post('/cart/increase-stock', [App\Http\Controllers\User\CartController::class, 'increaseStock'])->name('cart.increase-stock');

    // History
    Route::get('/history', [App\Http\Controllers\User\HistoryController::class, 'index'])->name('history');
});

require __DIR__.'/auth.php';
