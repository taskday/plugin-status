<?php

namespace Performing\Taskday\Status;

use Taskday\Facades\Taskday;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class StatusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register plugin
        Taskday::register(new StatusPlugin, 'status');

        // Registering extra web routes
        Route::middleware('web')->group(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
