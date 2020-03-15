<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name','partner_number','id_number','address_1','address_2','contact_number','email','balance'];

    public function payments()
    {
        return $this->hasMany('App\Models\Api\Payment');
    }
    public function products()
    {
        return $this->hasMany('App\Models\Api\Product');
    }
}
