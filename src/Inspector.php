<?php

namespace MargaTampu\LaravelInspector;

class Inspector
{
    /**
     * Indicates if Passport migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

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
     * Configure Passport to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }
}
