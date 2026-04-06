<?php

namespace App\Providers;

use App\Models\ImportantLink;
use App\Models\RentalRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

        // Share pending counts with sidebar (استعارة، روابط مهمة بانتظار الموافقة)
        View::composer('layouts.sidebar', function ($view) {
            $pendingRentalRequestsCount = RentalRequest::where('status', 'pending')->count();
            $pendingImportantLinksCount = ImportantLink::where('status', 'pending')->count();
            $view->with([
                'pendingRentalRequestsCount' => $pendingRentalRequestsCount,
                'pendingImportantLinksCount' => $pendingImportantLinksCount,
            ]);
        });
    }
}
