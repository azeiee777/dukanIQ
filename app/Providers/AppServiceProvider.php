<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Interfaces\PartyRepositoryInterface;
use App\Repositories\PartyRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(PartyRepositoryInterface::class, PartyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
