<?php

namespace MargaTampu\LaravelInspector\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InsLogResource extends JsonResource
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
            'level'       => $this->level,
            'message'     => $this->message,
            'trace'       => $this->trace,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'links'       => [
                [
                    'rel'  => 'self',
                    'href' => route('inspector::logs.show', ['insLog' => $this->id]),
                    'type' => 'GET'
                ]
            ],
        ];
    }
}
