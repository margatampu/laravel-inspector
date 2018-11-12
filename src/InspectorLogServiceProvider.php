<?php

namespace MargaTampu\LaravelInspector;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use MargaTampu\LaravelInspector\Listeners\StoringLog;

class InspectorLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(MessageLogged::class, StoringLog::class);
    }
}
