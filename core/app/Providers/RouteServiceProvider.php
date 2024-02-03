<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route; 

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */

    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::namespace($this->namespace)->group(function(){
                Route::middleware(['web'])
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));

                Route::middleware(['web','maintenance'])
                     ->namespace('Manager')
                     ->prefix('manager')
                     ->name('manager.')
                     ->group(base_path('routes/manager.php'));

                Route::middleware(['web','maintenance'])
                    ->namespace('Staff')
                    ->prefix('staff')
                    ->name('staff.')
                    ->group(base_path('routes/staff.php'));

                Route::middleware(['web','maintenance'])
                    ->group(base_path('routes/web.php'));

                Route::prefix('api')
                    ->middleware('api','maintenance')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php'));
            });

        });

        Route::get('maintenance-mode','App\Http\Controllers\SiteController@maintenance')->name('maintenance');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
