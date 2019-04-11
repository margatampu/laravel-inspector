<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MargaTampu\LaravelInspector\Jobs\StoringRequest;

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
                    in_array($routeRequest->request->fullUrl(), config('inspector.endpoints')) ||
                    in_array($routeRequest->request->getPathInfo(), config('inspector.endpoints'))
                ) {
                    $ignoreRequest = true;
                }

                if (!$ignoreRequest) {
                    StoringRequest::dispatch(
                        $routeRequest->request->getMethod(),
                        $routeRequest->request->getRequestUri(),
                        $_SERVER['REMOTE_ADDR'],
                        json_encode($routeRequest->request->header()),
                        LARAVEL_START,
                        microtime(true)
                    );
                }
            });
        });
    }
}
