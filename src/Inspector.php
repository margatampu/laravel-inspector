<?php

namespace MargaTampu\LaravelInspector;

use GuzzleHttp\Client;

class Inspector
{
    /**
     * Web routes
     * Append web route to user project
     */
    public static function web()
    {
        require __DIR__ . '/routes/web.php';
    }

    /**
     * Api routes
     * Append api route to user project
     */
    public static function api()
    {
        require __DIR__ . '/routes/api.php';
    }

    /**
     * Store model event via rest api
     * Allow user to rewrite their own store model function
     */
    public static function storeModel($event)
    {
        // Use endpoint from config frist
        $endpoint = config('inspector.endpoints.model');

        // No endpoint mean use default endpoint
        if (!$endpoint) {
            $endpoint = route('inspector::models.store');
        }

        // Define method, is it created, updated, or deleted?
        $method = 'updated';
        if (!$event->exists) {
            $method = 'deleted';
        } elseif ($event->wasRecentlyCreated) {
            $method = 'created';
        }

        // // Define client using guzzle http client
        $client = new \GuzzleHttp\Client();

        // Send json data using guzzle http post
        $client->post($endpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . config('inspector.authorization'),
            ],
            'json' => [
                'inspectable_type' => get_class($event),
                'inspectable_id'   => $event->getAttributes()['id'],
                'method'           => $method,
                'original'         => $method === 'created' ? json_encode($event->getAttributes()) : json_encode($event->getOriginal()),
                'changes'          => $method === 'updated' ? json_encode($event->getChanges()) : '{}',
            ],
        ]);
    }

    /**
     * Store log event via rest api
     * Allow user to rewrite their own store log function
     */
    public static function storeLog($event)
    {
        // Use endpoint from config frist
        $endpoint = config('inspector.endpoints.log');

        // No endpoint mean use default endpoint
        if (!$endpoint) {
            $endpoint = route('inspector::logs.store');
        }

        // Define client using guzzle http client
        $client = new \GuzzleHttp\Client();

        // Send json data using guzzle http post
        $client->post($endpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . config('inspector.authorization'),
            ],
            'json' => [
                'level'   => $event->level,
                'message' => $event->message,
                'trace'   => count($event->context) ? json_encode($event->context['exception']->getTrace()) : '[]'
            ],
        ]);
    }

    /**
     * Store request event via rest api
     * Allow user to rewrite their own store request function
     */
    public static function storeRequest($routeRequest)
    {
        // Use endpoint from config frist
        $endpoint = config('inspector.endpoints.request');

        // No endpoint mean use default endpoint
        if (!$endpoint) {
            $endpoint = route('inspector::requests.store');
        }

        // Define client using guzzle http client
        $client = new \GuzzleHttp\Client();

        // Send json data using guzzle http post
        $client->post($endpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . config('inspector.authorization'),
            ],
            'json' => [
                'method'     => $routeRequest->request->getMethod(),
                'uri'        => $routeRequest->request->getRequestUri(),
                'ip'         => $_SERVER['REMOTE_ADDR'],
                'headers'    => json_encode($routeRequest->request->header()),
                'start_time' => LARAVEL_START,
                'end_time'   => microtime(true)
            ],
        ]);
    }
}
