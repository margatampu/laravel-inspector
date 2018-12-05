<?php

namespace MargaTampu\LaravelInspector\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InsAuthCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
