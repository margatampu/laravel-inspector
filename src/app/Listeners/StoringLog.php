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

        // Define client using guzzle http client
        $client = new Client();

        $trace   = '[]';
        $context = $event->context;
        if (count($context) && array_key_exists('exception', $context)) {
            // $trace = json_encode($event->context['exception']->getTrace());
            $trace = json_encode($event->context['exception']);
        }

        // Send json data using guzzle http post
        $client->post($endpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . config('inspector.authorization'),
            ],
            'json' => [
                'level'   => $event->level,
                'message' => $event->message,
                'trace'   => $trace,
            ],
        ]);
    }
}
