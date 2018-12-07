<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use MargaTampu\LaravelInspector\Jobs\LumenStoringLog;
use MargaTampu\LaravelInspector\Jobs\StoringLog;

class InspectorLogServiceProvider extends ServiceProvider
{
    /**
     * The Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Normalized Laravel Version
     *
     * @var string
     */
    protected $version;

    /**
     * True when enabled, false disabled an null for still unknown
     *
     * @var bool
     */
    protected $enabled = null;

    /**
     * True when this is a Lumen application
     *
     * @var bool
     */
    protected $is_lumen = false;

    /**
     * @param Application $app
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }
        $this->app      = $app;
        $this->version  = $app->version();
        $this->is_lumen = str_contains($this->version, 'Lumen');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $is_lumen = $this->is_lumen;

        // Event::listen(MessageLogged::class, StoringLog::class);
        Event::listen(MessageLogged::class, function (MessageLogged $e) use ($is_lumen) {
            try {
                $trace   = '[]';
                $context = $e->context;
                if (count($context) && array_key_exists('exception', $context)) {
                    if (is_string($context['exception'])) {
                        $trace = $context['exception'];
                    } else {
                        $exceptions = collect($context['exception']->getTrace());

                        $exceptions = $exceptions->map(function ($exception) {
                            if (isset($exception['class']) && isset($exception['function'])) {
                                return [
                                    'main'      => $exception['class'],
                                    'separator' => '@',
                                    'detail'    => $exception['function'],
                                ];
                            } else {
                                if (isset($exception['file'])) {
                                    return [
                                        'main'      => $exception['file'],
                                        'separator' => ':',
                                        'detail'    => $exception['line'],
                                    ];
                                }
                            }

                            return null;
                        })->filter(function ($exception) {
                            return $exception;
                        });

                        $trace = json_encode($exceptions->toArray());
                    }
                }

                // Separate job for lumen and laravel
                if ($this->is_lumen) {
                    dispatch(new LumenStoringLog($e->level, $e->message->getMessage(), $trace));
                } else {
                    StoringLog::dispatch($e->level, $e->message, $trace);
                }
            } catch (\Exception $e) {
                // Ignore exception
            }
        });
    }
}
