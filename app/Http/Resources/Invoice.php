<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Invoice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'=> $this->id,
            'pupil_id'=> $this->pupil_id,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
    public function with($request)
    {
        return [
            'version' => '1.2',
            'author' => 'felix shava',
        ];
    }
}
