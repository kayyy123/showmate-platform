<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreProfileController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::post('/catalog/{user}/checkout', [CheckoutController::class, 'store'])->name('catalog.checkout');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});

Route::middleware(['auth', 'verified', 'role:merchant'])->group(function () {
    Route::get('/merchant/catalog', [MerchantController::class, 'catalog'])->name('merchant.catalog');
    Route::get('/merchant/manage', [MerchantController::class, 'manage'])->name('merchant.manage');
    Route::get('/merchant/stats', [MerchantController::class, 'stats'])->name('merchant.stats');
});

Route::middleware(['auth', 'role:merchant'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/store-profile', [StoreProfileController::class, 'edit'])->name('store-profile.edit');
    Route::put('/store-profile', [StoreProfileController::class, 'update'])->name('store-profile.update');

    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/toggle-visibility', [ProductController::class, 'toggleVisibility'])->name('products.toggle-visibility');

    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::put('/links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::post('/links/reorder', [LinkController::class, 'reorder'])->name('links.reorder');
    Route::post('/links/{link}/toggle-active', [LinkController::class, 'toggleActive'])->name('links.toggle-active');

    Route::get('/upgrade-pro', [PlanController::class, 'upgradePage'])->name('upgrade-pro.page');
    Route::post('/upgrade-pro', [PlanController::class, 'upgrade'])->name('upgrade-pro.upgrade');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
    Route::get('/products', [AdminDashboardController::class, 'products'])->name('products');
    Route::get('/links', [AdminDashboardController::class, 'links'])->name('links');
    Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('orders');
    Route::get('/statistics', [AdminDashboardController::class, 'statistics'])->name('statistics');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
});

require __DIR__.'/auth.php';
