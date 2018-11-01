<?php

namespace MargaTampu\LaravelInspector\Models;

use Illuminate\Database\Eloquent\Model;

class InsAuth extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'token'
    ];
}
