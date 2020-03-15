<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','partner_number','id_number','address_1','address_2','contact_number','email','balance'];

    public function fees()
    {
        return $this->belongsToMany('App\Models\Api\Fee')->withPivot(['balance','updated_at']);
    }
    public function takings()
    {
        return $this->hasMany('App\Models\Api\Taking');
    }
}
