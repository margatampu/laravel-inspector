<?php

namespace MargaTampu\LaravelInspector\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsRequestResource extends JsonResource
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
            'id'          => $this->id,
            'ins_auth_id' => $this->ins_auth_id,
            'method'      => $this->method,
            'uri'         => $this->uri,
            'ip'          => $this->ip,
            'headers'     => $this->headers,
            'start_time'  => $this->start_time,
            'end_time'    => $this->end_time,
            'exec_time'   => floor(($this->end_time - $this->start_time) * 1000) . 'ms',
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'links'       => [
                [
                    'rel'  => 'self',
                    'href' => route('inspector::requests.show', ['insRequest' => $this->id]),
                    'type' => 'GET'
                ]
            ],
        ];
    }
}
