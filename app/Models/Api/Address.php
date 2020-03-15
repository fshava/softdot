<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['address_1', 'address_2', 'town'];

    public function clients()
    {
        return $this->belongsToMany('App\Models\Api\Client');
    }

}
