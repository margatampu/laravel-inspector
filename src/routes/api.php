<?php

use Illuminate\Http\Request;
use MargaTampu\LaravelInspector\Models\InsModel;

Route::post('test', function (Request $request) {
    dd($request->all());
    // InsModel::create($request->all());
});

Route::group(['as' => 'inspector::', 'prefix' => 'inspector'], function () {
    Route::post('/models', [
        'as'    => 'models.store',
        'uses'  => uses('InsModelController@store')
    ]);

    Route::post('/logs', [
        'as'    => 'logs.store',
        'uses'  => uses('InsLogController@store')
    ]);

    Route::post('/requests', [
        'as'    => 'requests.store',
        'uses'  => uses('InsRequestController@store')
    ]);
});

function uses($uses)
{
    return '\MargaTampu\LaravelInspector\Http\Controllers\\' . $uses;
}
