<?php

namespace MargaTampu\LaravelInspector\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use GuzzleHttp\Client;

class StoringRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Storing request variables
     */
    public $method;
    public $uri;
    public $ip;
    public $header;
    public $start;
    public $end;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($method, $uri, $ip, $header, $start, $end)
    {
        $this->method = $method;
        $this->uri    = $uri;
        $this->ip     = $ip;
        $this->header = $header;
        $this->start  = $start;
        $this->end    = $end;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Use endpoint from config frist
        $endpoint = config('inspector.endpoints.request');

        // No endpoint mean use default endpoint
        if (!$endpoint) {
            $endpoint = route('inspector::requests.store');
        };

        // Define client using guzzle http client
        $client = new Client();

        // Send json data using guzzle http post
        $client->post($endpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . config('inspector.authorization'),
            ],
            'json' => [
                'method'     => $this->method,
                'uri'        => $this->uri,
                'ip'         => $this->ip,
                'headers'    => $this->header,
                'start_time' => $this->start,
                'end_time'   => $this->end,
            ],
        ]);
    }
}
