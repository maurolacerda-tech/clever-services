<?php

namespace Modules\Banners\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class BannerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::namespace('Modules\Banners\Http\Controllers')
            ->middleware(['web'])
            ->group(__DIR__. '/../Routes/web.php');

            $this->loadViewsFrom(__DIR__.'/../Views', 'Banner');

            $this->loadMigrationsFrom(__DIR__.'/../Migrations');

            $this->publishes([
                __DIR__.'/../Views' => resource_path('views/vendor/Banner'),
            ], 'views');

            
            $this->publishes([
                __DIR__.'/../Config/banners.php' => config_path('banners.php'),
            ], 'config');
            
    }
    public function register()
    {
        
        $this->mergeConfigFrom(
            __DIR__.'/../Config/banners.php',
            'banners'
        );
        
    }
}