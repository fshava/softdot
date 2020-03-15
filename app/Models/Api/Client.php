<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'surname', 'grade', 'class', 'sex','dob'];
    protected $dates = ['deleted_at'];

    public function fees()
    {
        return $this->belongsToMany('App\Models\Api\Fee')->withPivot('balance','created_at','updated_at');
    }
    public function addresses()
    {
        return $this->belongsToMany('App\Models\Api\Address');
    }
    public function guardians()
    {
        return $this->belongsToMany('App\Models\Api\Gardian');
    }
    public function revenues()
    {
        return $this->hasMany('App\Models\Api\Revenue');
    }
}
