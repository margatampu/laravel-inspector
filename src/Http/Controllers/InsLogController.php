<?php

namespace MargaTampu\LaravelInspector\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MargaTampu\LaravelInspector\Models\InsAuth;
use MargaTampu\LaravelInspector\Models\InsLog;

class InsLogController extends Controller
{
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

        $insLog = new InsLog();

        $insLog->ins_auth_id      = $insAuth->id;
        $insLog->level            = $request->input('level');
        $insLog->message          = $request->input('message');
        $insLog->trace            = $request->input('trace');

        $insLog->save();

        // Delete expired rows
        InsLog::removeExpired(config('inspector.limit.days.log'));

        // Delete over limit records
        InsLog::removeOverLimit(config('inspector.limit.records.log'));

        // // Return response 200
        return response()->json([], 200);
    }
}
