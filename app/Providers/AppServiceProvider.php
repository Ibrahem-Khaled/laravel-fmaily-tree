<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\RentalRequest;
use App\Models\ImportantLink;

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

        // Share important links with header
        View::composer('partials.main-header', function ($view) {
            if (Auth::check()) {
                $importantLinks = ImportantLink::orderBy('order')->get();
            } else {
                $importantLinks = ImportantLink::getActiveLinks();
            }
            $view->with('importantLinks', $importantLinks);
        });
    }
}
