<?php

namespace MargaTampu\LaravelInspector\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MargaTampu\LaravelInspector\InsAuth;
use MargaTampu\LaravelInspector\InsRequest;
use MargaTampu\LaravelInspector\Http\Resources\InsRequestCollection;
use MargaTampu\LaravelInspector\Http\Resources\InsRequestResource;

class InsRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new InsRequestCollection(
            InsRequest::latest()->paginate(config('inspector.paginate'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Return error code 404 if token not exist
        if (!$insAuth = InsAuth::where('token', $request->bearerToken())->first()) {
            return response()->json([], 404);
        }

        $insRequest = new InsRequest();

        $insRequest->ins_auth_id = $insAuth->id;
        $insRequest->method      = $request->input('method');
        $insRequest->uri         = $request->input('uri');
        $insRequest->ip          = $request->input('ip');
        $insRequest->headers     = $request->input('headers');
        $insRequest->start_time  = $request->input('start_time');
        $insRequest->end_time    = $request->input('end_time');

        $insRequest->save();

        // Delete expired rows
        InsRequest::removeExpired(config('inspector.limit.days.request'));

        // Delete over limit records
        InsRequest::removeOverLimit(config('inspector.limit.records.request'));

        // Return response 200
        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InsRequest  $insRequest
     * @return \Illuminate\Http\Response
     */
    public function show(InsRequest $insRequest)
    {
        return new InsRequestResource(
            InsRequest::where('id', $insRequest->id)->firstOrFail()
        );
    }
}
