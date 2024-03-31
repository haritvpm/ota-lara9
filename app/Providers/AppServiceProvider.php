<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
//use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Pagination\Paginator; 
use App\Services\EmployeeService;
use App\Services\PunchingService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;


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

        Paginator::useBootstrap();

        // RateLimiter::for('fetchaebas', function ($job) {
        //     return Limit::perMinute(1)->by($job->user->id);
        // });

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


//injecting ExampleDependantService into SyncProfile service
        // $this->app->bind(SyncProfile::class, function (Application $app) {
        //     return new SyncProfile($app->make(ExampleDependantService::class));
        // });
        //   $this->app->bind(PunchingService::class, function (Application $app) {
        //     return new PunchingService($app->make(EmployeeService::class));
        // });

    }
}
