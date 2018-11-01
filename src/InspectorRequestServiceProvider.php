<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class InspectorRequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::matched(function ($routeRequest) {
            $this->app->terminating(function () use ($routeRequest) {
                $ignoreRequest = false;
                $actions = $routeRequest->route->action;

                // Ignore request if current url come from laravel inspector constructor
                // or come from user custom endpoints
                if (
                    (isset($actions['controller']) && str_contains($actions['controller'], 'LaravelInspector')) ||
                    in_array($routeRequest->request->fullUrl(), config('inspector.endpoints'))
                ) {
                    $ignoreRequest = true;
                }

                if (!$ignoreRequest) {
                    Inspector::storeRequest($routeRequest);
                }
            });
        });
    }
}
