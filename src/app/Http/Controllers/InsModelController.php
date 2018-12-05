<?php

namespace MargaTampu\LaravelInspector\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MargaTampu\LaravelInspector\InsModel;
use MargaTampu\LaravelInspector\InsAuth;
use MargaTampu\LaravelInspector\Http\Resources\InsModelCollection;
use MargaTampu\LaravelInspector\Http\Resources\InsModelResource;

class InsModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new InsModelCollection(
            InsModel::latest()->paginate(config('inspector.paginate'))
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

        $insModel = new InsModel();

        $insModel->ins_auth_id      = $insAuth->id;
        $insModel->inspectable_type = $request->input('inspectable_type');
        $insModel->inspectable_id   = $request->input('inspectable_id');
        $insModel->method           = $request->input('method');
        $insModel->original         = $request->input('original');
        $insModel->changes          = $request->input('changes');

        $insModel->save();

        // Delete expired rows
        InsModel::removeExpired(config('inspector.limit.days.model'));

        // Delete over limit records
        InsModel::removeOverLimit(config('inspector.limit.records.model'));

        // Return response 200
        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InsModel  $insModel
     * @return \Illuminate\Http\Response
     */
    public function show(InsModel $insModel)
    {
        return new InsModelResource(
            InsModel::where('id', $insModel->id)->firstOrFail()
        );
    }
}
