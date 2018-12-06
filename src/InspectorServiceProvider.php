<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Support\ServiceProvider;

class InspectorServiceProvider extends ServiceProvider
{
    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Normalized Laravel Version
     *
     * @var string
     */
    protected $version;

    /**
     * True when booted.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * True when enabled, false disabled an null for still unknown
     *
     * @var bool
     */
    protected $enabled = null;

    /**
     * True when this is a Lumen application
     *
     * @var bool
     */
    protected $is_lumen = false;

    /**
     * @param Application $app
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }
        $this->app      = $app;
        $this->version  = $app->version();
        $this->is_lumen = str_contains($this->version, 'Lumen');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

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
        $this->app->bind('inspector', 'MargaTampu\LaravelInspector\Inspector');

        // Enable inspector for model service provider using inspector config file
        if (config('inspector.enableModelInspector') === true) {
            $this->app->register('MargaTampu\LaravelInspector\InspectorModelServiceProvider');
        }

        // Enable inspector for log service provider using inspector config file
        if (config('inspector.enableLogInspector') === true) {
            $this->app->register('MargaTampu\LaravelInspector\InspectorLogServiceProvider');
        }

        // Enable inspector for request service provider using inspector config file
        if (!$this->is_lumen) { // Not allowed for lumen
            if (config('inspector.enableRequestInspector') === true) {
                $this->app->register('MargaTampu\LaravelInspector\InspectorRequestServiceProvider');
            }
        }

        $this->commands('MargaTampu\LaravelInspector\Console\Commands\InspectorAuthorizationCommand');
        $this->commands('MargaTampu\LaravelInspector\Console\Commands\InspectorTestCommand');
    }
}
