<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = ['category_id', 'year', 'term','description','amount'];

    public function clients()
    {
        return $this->belongsToMany('App\Models\Api\Client');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Api\Category');
    }
    public function revenues()
    {
        return $this->hasMany('App\Models\Api\Revenue');
    }
    public function customers()
    {
        return $this->belongsToMany('App\Models\Api\Customer');
    }
    public function takings()
    {
        return $this->hasMany('App\Models\Api\Taking');
    }
}
