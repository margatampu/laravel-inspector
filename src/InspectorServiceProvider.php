<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Support\ServiceProvider;

class InspectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/inspector.php' => config_path('inspector.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Enable inspector for model service provider using inspector config file
        // if (config('inspector.enableModelInspector') === true) {
        $this->app->register('MargaTampu\LaravelInspector\InspectorModelServiceProvider');
        // }

        // Enable inspector for log service provider using inspector config file
        if (config('inspector.enableLogInspector') === true) {
            $this->app->register('MargaTampu\LaravelInspector\InspectorLogServiceProvider');
        }

        // Enable inspector for request service provider using inspector config file
        if (config('inspector.enableRequestInspector') === true) {
            $this->app->register('MargaTampu\LaravelInspector\InspectorRequestServiceProvider');
        }

        $this->commands('MargaTampu\LaravelInspector\Console\Commands\InspectorAuthorizationCommand');
    }
}
