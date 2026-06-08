<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StoreRepository;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawalRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StoreRepository::class);
        $this->app->singleton(ProductRepository::class);
        $this->app->singleton(CategoryRepository::class);
        $this->app->singleton(SubscriptionPlanRepository::class);
        $this->app->singleton(OrderRepository::class);
        $this->app->singleton(WithdrawalRepository::class);
        $this->app->singleton(UserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
