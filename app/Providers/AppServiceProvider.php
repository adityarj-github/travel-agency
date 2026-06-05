<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
        Paginator::useTailwind();

        // Share website settings with every view (guarded for pre-migration state).
        View::composer('*', function ($view) {
            $settings = [];

            if (Schema::hasTable('website_settings')) {
                $settings = Setting::all();
            }

            $view->with('settings', $settings);
        });

        // Share the signed-in customer's wishlist package IDs (for heart buttons).
        View::composer('*', function ($view) {
            $ids = [];

            if (Auth::check() && Auth::user()->isCustomer() && Schema::hasTable('wishlists')) {
                $ids = Auth::user()->wishlist()->pluck('packages.id')->all();
            }

            $view->with('myWishlistIds', $ids);
        });
    }
}
