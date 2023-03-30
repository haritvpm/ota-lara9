<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
//use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Pagination\Paginator; 


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Paginator::defaultView('vendor.pagination.default');
 
        Paginator::defaultSimpleView('vendor.pagination.default');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        
        if ($this->app->environment('local', 'testing')) {
           // $this->app->register(DuskServiceProvider::class);
        }

    }
}
