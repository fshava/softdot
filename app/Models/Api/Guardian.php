<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = ['full_name', 'contact_1', 'contact_2'];

    public function clients()
    {
        return $this->belongsToMany('App\Models\Api\Client');
    }

}
