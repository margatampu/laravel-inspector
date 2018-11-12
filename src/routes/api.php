<?php

Route::group(['as' => 'inspector::', 'prefix' => 'inspector'], function () {
    // Ins model routes
    Route::get('/models', [
        'as'    => 'models.index',
        'uses'  => uses('InsModelController@index')
    ]);

    Route::post('/models', [
        'as'    => 'models.store',
        'uses'  => uses('InsModelController@store')
    ]);

    Route::get('/models/{insModel}', [
        'as'    => 'models.show',
        'uses'  => uses('InsModelController@show')
    ]);

    // Ins log routes
    Route::get('/logs', [
        'as'    => 'logs.index',
        'uses'  => uses('InsLogController@index')
    ]);

    Route::post('/logs', [
        'as'    => 'logs.store',
        'uses'  => uses('InsLogController@store')
    ]);

    Route::get('/logs/{insLog}', [
        'as'    => 'logs.show',
        'uses'  => uses('InsLogController@show')
    ]);

    // Ins log requests
    Route::get('/requests', [
        'as'    => 'requests.index',
        'uses'  => uses('InsRequestController@index')
    ]);

    Route::post('/requests', [
        'as'    => 'requests.store',
        'uses'  => uses('InsRequestController@store')
    ]);

    Route::get('/requests/{insRequest}', [
        'as'    => 'requests.show',
        'uses'  => uses('InsRequestController@show')
    ]);
});

function uses($uses)
{
    return '\MargaTampu\LaravelInspector\Http\Controllers\\' . $uses;
}
