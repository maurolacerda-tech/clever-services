<?php

namespace Modules\Services\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ServiceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::namespace('Modules\Services\Http\Controllers')
            ->middleware(['web'])
            ->group(__DIR__. '/../Routes/web.php');

            $this->loadViewsFrom(__DIR__.'/../Views', 'Service');

            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            $this->publishes([
                __DIR__.'/../Views' => resource_path('views/vendor/Service'),
            ], 'views');

            
            $this->publishes([
                __DIR__.'/../Config/services.php' => config_path('services.php'),
            ], 'config');
            
    }
    public function register()
    {        
        $this->mergeConfigFrom(
            __DIR__.'/../Config/services.php',
            'services'
        );
        
    }
}