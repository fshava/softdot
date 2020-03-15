<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category'];

    public function fees()
    {
        return $this->hasMany('App\Models\Api\Fee');
    }
    public function products()
    {
        return $this->hasMany('App\Models\Api\Product');
    }
    public function payments()
    {
        return $this->hasMany('App\Models\Api\Payment');
    }

}
