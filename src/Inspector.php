<?php

namespace MargaTampu\LaravelInspector;

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
}
