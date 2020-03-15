<?php

namespace Felix\Sms\Models\sms;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name'];

    // public function clients()
    // {
    //     return $this->belongsToMany('App\Models\Api\Client');
    // }

}
