<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['description','quantity','unit_price','total_price','balance'];

    public function category()
    {
        return $this->belongsTo('App\Models\Api\Category');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Models\Api\Supplier');
    }
    public function payments()
    {
        return $this->hasMany('App\Models\Api\Payment');
    }
}
