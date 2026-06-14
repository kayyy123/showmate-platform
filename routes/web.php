<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\InclusiveApplicationController as AdminInclusiveApplicationController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\InclusiveApplicationController;
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
});
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

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

    Route::prefix('/inclusive-applications')->name('inclusive-applications.')->group(function () {
        Route::get('/', [InclusiveApplicationController::class, 'index'])->name('index');
        Route::get('/create', [InclusiveApplicationController::class, 'create'])->name('create');
        Route::post('/', [InclusiveApplicationController::class, 'store'])->name('store');
        Route::get('/{inclusive_application}', [InclusiveApplicationController::class, 'show'])->name('show');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/stores')->name('stores.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::get('/create', [StoreController::class, 'create'])->name('create');
        Route::post('/', [StoreController::class, 'store'])->name('store');
        Route::get('/{store}/edit', [StoreController::class, 'edit'])->name('edit');
        Route::put('/{store}', [StoreController::class, 'update'])->name('update');
        Route::delete('/{store}', [StoreController::class, 'destroy'])->name('destroy');
        Route::post('/{store}/restore', [StoreController::class, 'restore'])->name('restore');
        Route::delete('/{store}/force', [StoreController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::post('/{product}/toggle-visibility', [AdminProductController::class, 'toggleVisibility'])->name('toggle-visibility');
        Route::post('/{product}/restore', [AdminProductController::class, 'restore'])->name('restore');
        Route::delete('/{product}/force', [AdminProductController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/{category}/restore', [CategoryController::class, 'restore'])->name('restore');
        Route::delete('/{category}/force', [CategoryController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/subscriptions')->name('subscriptions.')->group(function () {
        Route::prefix('/plans')->name('plans.')->group(function () {
            Route::get('/', [SubscriptionPlanController::class, 'index'])->name('index');
            Route::get('/create', [SubscriptionPlanController::class, 'create'])->name('create');
            Route::post('/', [SubscriptionPlanController::class, 'store'])->name('store');
            Route::get('/{subscription_plan}/edit', [SubscriptionPlanController::class, 'edit'])->name('edit');
            Route::put('/{subscription_plan}', [SubscriptionPlanController::class, 'update'])->name('update');
            Route::delete('/{subscription_plan}', [SubscriptionPlanController::class, 'destroy'])->name('destroy');
        });
        Route::get('/assign', [SubscriptionPlanController::class, 'assignForm'])->name('assign-form');
        Route::post('/assign', [SubscriptionPlanController::class, 'assign'])->name('assign');
    });

    Route::prefix('/orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
        Route::post('/{order}/restore', [OrderController::class, 'restore'])->name('restore');
        Route::delete('/{order}/force', [OrderController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::get('/{withdrawal}', [WithdrawalController::class, 'show'])->name('show');
        Route::post('/{withdrawal}/approve', [WithdrawalController::class, 'approve'])->name('approve');
        Route::post('/{withdrawal}/reject', [WithdrawalController::class, 'reject'])->name('reject');
        Route::delete('/{withdrawal}', [WithdrawalController::class, 'destroy'])->name('destroy');
        Route::post('/{withdrawal}/restore', [WithdrawalController::class, 'restore'])->name('restore');
        Route::delete('/{withdrawal}/force', [WithdrawalController::class, 'forceDelete'])->name('force-delete');
    });

    Route::prefix('/inclusive-applications')->name('inclusive-applications.')->group(function () {
        Route::get('/', [AdminInclusiveApplicationController::class, 'index'])->name('index');
        Route::get('/{inclusive_application}', [AdminInclusiveApplicationController::class, 'show'])->name('show');
        Route::post('/{inclusive_application}/approve', [AdminInclusiveApplicationController::class, 'approve'])->name('approve');
        Route::post('/{inclusive_application}/reject', [AdminInclusiveApplicationController::class, 'reject'])->name('reject');
    });
});

require __DIR__.'/auth.php';
