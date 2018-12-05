<?php

namespace MargaTampu\LaravelInspector\Listeners;

use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoringModel implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle($event)
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

        // Define client using guzzle http client
        $client = new Client();

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
}
