<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Pupil extends JsonResource
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
            'name'=> $this->name,
            'surname'=> $this->surname,
            'grade'=> $this->grade,
            'class'=> $this->class,
            'sex'=> $this->sex,
            'dob'=> $this->dob,
            'deleted_at'=> $this->deleted_at,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
    public function with($request)
    {
        return [
            'version' => '1.2',
            'author' => 'felix shava',
            'student'=>'tanyaradzwa',
            'year'=>'2019'
        ];
    }
}
