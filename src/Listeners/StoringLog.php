<?php

namespace MargaTampu\LaravelInspector\Listeners;

use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Queue\InteractsWithQueue;

class StoringLog implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  MessageLogged $event
     * @return void
     */
    public function handle(MessageLogged $event)
    {
        // Use endpoint from config frist
        $endpoint = config('inspector.endpoints.log');

        // No endpoint mean use default endpoint
        if (!$endpoint) {
            $endpoint = route('inspector::logs.store');
        }

        // Error trace
        $trace = json_encode($event->context['exception']->getTrace());

        // Make sure the error not come from package
        // Prevent endless loop condition
        if (strpos($trace, 'laravel-inspector') !== false) {
            // Define client using guzzle http client
            $client = new Client();

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
    }
}
