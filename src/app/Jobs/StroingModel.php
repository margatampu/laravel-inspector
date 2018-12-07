<?php

namespace MargaTampu\LaravelInspector\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;

class StoringModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Storing model variables
     */
    public $inspectable_type;
    public $inspectable_id;
    public $method;
    public $original;
    public $changes;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($inspectable_type, $inspectable_id, $method, $original, $changes)
    {
        $this->inspectable_type = $inspectable_type;
        $this->inspectable_id   = $inspectable_id;
        $this->method           = $method;
        $this->original         = $original;
        $this->changes          = $changes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Use endpoint from config frist
        $endpoint = config('inspector.endpoints.model');

        // No endpoint mean use default endpoint
        if (!$endpoint) {
            $endpoint = route('inspector::models.store');
        }

        // Define client using guzzle http client
        $client = new Client();

        // Send json data using guzzle http post
        $client->post($endpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . config('inspector.authorization'),
            ],
            'json' => [
                'inspectable_type' => $this->inspectable_type,
                'inspectable_id'   => $this->inspectable_id,
                'method'           => $this->method,
                'original'         => $this->original,
                'changes'          => $this->changes,
            ],
        ]);
    }
}
