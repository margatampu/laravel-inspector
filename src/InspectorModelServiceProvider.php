<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MargaTampu\LaravelInspector\Jobs\LumenStoringModel;
use MargaTampu\LaravelInspector\Jobs\StoringModel;

class InspectorModelServiceProvider extends ServiceProvider
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
        $events   = [];

        // No point to use this package if there is no models registered in config file
        if (!config('inspector.models')) {
            return;
        }

        // Wrapping all models to saved and deleted listener
        foreach (config('inspector.models') as $model) {
            array_push(
                $events,
                'eloquent.saved: ' . $model,
                'eloquent.deleted: ' . $model
            );
        }

        $is_lumen = $this->is_lumen;

        // Listening to events
        Event::listen($events, function ($event) use ($is_lumen) {
            // Define method, is it created, updated, or deleted?
            $method = 'updated';
            $original = json_encode($event->getOriginal());
            $changes = json_encode($event->getChanges());

            if (!$event->exists) {
                $method = 'deleted';
                $changes = '{}';
            } elseif ($event->wasRecentlyCreated) {
                $method = 'created';
                $original = json_encode($event->getAttributes());
                $changes = '{}';
            }

            // Separate job for lumen and laravel
            if ($this->is_lumen) {
                dispatch(new LumenStoringModel(
                    get_class($event),
                    $event->getAttributes()['id'],
                    $method,
                    $original,
                    $changes
                ));
            } else {
                StoringModel::dispatch(
                    get_class($event),
                    $event->getAttributes()['id'],
                    $method,
                    $original,
                    $changes
                );
            }
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
