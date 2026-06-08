<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreProfileController;
use App\Http\Controllers\LinkController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::post('/catalog/{user}/checkout', [CheckoutController::class, 'store'])->name('catalog.checkout');

Route::permanentRedirect('/dashboard', '/merchant/manage')
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/merchant/catalog', [MerchantController::class, 'catalog'])->name('merchant.catalog');
    Route::get('/merchant/manage', [MerchantController::class, 'manage'])->name('merchant.manage');
    Route::get('/merchant/stats', [MerchantController::class, 'stats'])->name('merchant.stats');
});

Route::middleware('auth')->group(function () {
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
});

require __DIR__.'/auth.php';
