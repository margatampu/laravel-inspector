<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class InspectorModelServiceProvider extends ServiceProvider
{
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

        // Listening to events
        Event::listen($events, function ($event) {
            Inspector::storeModel($event);
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
