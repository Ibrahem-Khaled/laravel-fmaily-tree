<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\RentalRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Share pending rental requests count with sidebar
        View::composer('layouts.sidebar', function ($view) {
            $pendingRentalRequestsCount = RentalRequest::where('status', 'pending')->count();
            $view->with('pendingRentalRequestsCount', $pendingRentalRequestsCount);
        });
    }
}
