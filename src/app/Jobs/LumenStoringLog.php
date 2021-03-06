<?php

namespace MargaTampu\LaravelInspector\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LumenStoringLog implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Storing log variables
     */
    public $level;
    public $message;
    public $trace;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($level, $message, $trace)
    {
        $this->queue = config('inspector.queueName');

        $this->level   = $level;
        $this->message = $message;
        $this->trace   = $trace;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Use endpoint from config frist
        $endpoint = config('inspector.endpoints.log');

        // No endpoint mean use default endpoint
        if (!$endpoint) {
            $endpoint = route('inspector::logs.store');
        }

        // Define client using guzzle http client
        $client = new Client(['timeout' => 5]);

        // Send json data using guzzle http post
        $client->post($endpoint, [
            'headers' => [
                'Authorization'     => 'Bearer ' . config('inspector.authorization'),
            ],
            'json' => [
                'level'   => $this->level,
                'message' => $this->message,
                'trace'   => $this->trace,
            ],
        ]);
    }
}
