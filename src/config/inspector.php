<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | All models in array will listened by inspector and store it to database.
    | If you would like no models listened, change this to an empty array.
    |
    */
    'models' => [
        'App\User'
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Inspector - Enable
    |--------------------------------------------------------------------------
    |
    | Enable model inspector to listening to all listed models.
    | Use value 'true' to enable and 'false' to disable model inspector.
    |
    */
    'enableModelInspector' => true,

    /*
    |--------------------------------------------------------------------------
    | Log Inspector - Enable
    |--------------------------------------------------------------------------
    |
    | Enable log inspector to listening to all listed log.
    | Use value 'true' to enable and 'false' to disable log inspector.
    |
    */
    'enableLogInspector' => true,

    /*
    |--------------------------------------------------------------------------
    | Request Inspector - Enable
    |--------------------------------------------------------------------------
    |
    | Enable request inspector to listening to all incoming requests.
    | Use value 'true' to enable and 'false' to disable request inspector.
    |
    */
    'enableRequestInspector' => true,

    /*
    |--------------------------------------------------------------------------
    | Limit
    |--------------------------------------------------------------------------
    |
    | Store all expiration limit for days and records total.
    |
    */
    'limit' => [
        /*
        |--------------------------------------------------------------------------
        | Days Limit
        |--------------------------------------------------------------------------
        |
        | By default, every record on database stored for 30 days. You can set your
        | own expiration time (in days) here. During each time a change is added to
        | the database, any records older than the expiration time will be removed.
        | Set the numbers below to 0 if you would like to have no expiration.
        |
        */
        'days' => [
            'model'   => 30,
            'log'     => 30,
            'request' => 30,
        ],
        /*
        |--------------------------------------------------------------------------
        | Records Limit
        |--------------------------------------------------------------------------
        |
        | By default, we store 1200 record on database at any given time.
        | Set the numbers below to 0 if you would like to have no limit.
        |
        */
        'records' => [
            'model'   => 1200,
            'log'     => 1200,
            'request' => 1200,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Tags
    |--------------------------------------------------------------------------
    |
    | Configurable html tags to dispay different tag for new and old data to
    | suite your frontend html style.
    |
    */
    'tags' => [
        'new' => [
            'open'  => '<span style="color:green">',
            'close' => '</span>',
        ],
        'old' => [
            'open'  => '<span style="color:red">',
            'close' => '</span>',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Paginate
    |--------------------------------------------------------------------------
    |
    | Use to limiting records display per page.
    |
    */
    'paginate' => 10,

    /*
    |--------------------------------------------------------------------------
    | Endpoints
    |--------------------------------------------------------------------------
    |
    | By default, inspector use built in api to store model event to database.
    | Set this each endpoint to your project api method to store data.
    |
    */
    'endpoints' => [
        'model'   => env('INSPECTOR_MODEL_ENDPOINT', ''),
        'log'     => env('INSPECTOR_LOG_ENDPOINT', ''),
        'request' => env('INSPECTOR_REQUEST_ENDPOINT', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    |
    | By default, inspector authorization used to authorize any hit to endpoint.
    |
    */
    'authorization' => env('INSPECTOR_AUTHORIZATION', null),
];
